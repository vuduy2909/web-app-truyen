<?php

namespace App\Http\Controllers\HandleAPI;

use App\Enums\UserGenderEnum;
use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    // [GET] /api/auth/redirect/{provider}
    public function redirect($provider): \Symfony\Component\HttpFoundation\RedirectResponse|\Illuminate\Http\RedirectResponse
    {
        config(["services.$provider.redirect" => env('API_CALLBACK_URI') . "/$provider"]);

        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider): \Illuminate\Http\JsonResponse
    {
        try {
            config(["services.$provider.redirect" => env('API_CALLBACK_URI') . "/$provider"]);
            $data = Socialite::driver($provider)->user();

            // Kiểm tra người dùng đã bị khóa chưa
            $onlyTrashed = $this->checkOnlyTrashed($data['email']);

            if (!$onlyTrashed['type'])
                return handleResponseAPI($onlyTrashed['body']);

            // kiểm tra email có trong csdl chưa
            $user = User::query()
                ->where('email', $data['email'])
                ->first();
            //      Nếu người dùng tồn tại thì đăng nhập
            if (!is_null($user)) {
                return handleResponseAPI("Đăng nhập bằng $provider",
                    true,
                    $this->generateTokenAndReplaceUserInfo($user)
                );
            }

            //      Nếu người dùng không tồn tại thì đăng ký
            $user = User::query()->create([
                'email' => $data->getEmail(),
                'name' => $data->getName(),
                'avatar' => $data->getAvatar(),
                'level_id' => 1,
            ]);

            $user = $this->generateTokenAndReplaceUserInfo($user);
            return handleResponseAPI(
                'Bạn cần chuyển hướng đến trang đăng ký để thêm mật khẩu cho user',
                true,
                $user);

        } catch (\Exception $e) {
            return handleResponseAPI('Có lỗi xảy ra! ' . $e->getMessage());
        }
    }

    // [GET] /api/auth/login
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $validateUser = Validator::make($request->all(), [
                'email' => 'email|required',
                'password' => 'required',
            ]);
            //      Kiếm tra các trường có nhập đúng giá trị không
            if ($validateUser->fails()) {
                $array = $validateUser->errors()->toArray();
                return handleResponseAPI(array_shift($array)[0]);
            }

            //      Kiếm tra user có tồn tại không
            $user = $this->checkUserSurviveByEmailLogin($request->get('email'));
            if (!$user['type'])
                return handleResponseAPI($user['body']);

            $user = $user['body'];

            //      Kiếm tra đúng mật khẩu không nếu user tồn tại
            if (!Hash::check($request->get('password'), $user->password))
                return handleResponseAPI('Sai mật khẩu');

            $user = $this->generateTokenAndReplaceUserInfo($user);

            return handleResponseAPI(
                'Trả về thông tin user và token để kiểm tra user thành công',
                true,
                $user
            );
        } catch (\Throwable $e) {
            return handleResponseAPI('Có lỗi xảy ra! ' . $e->getMessage());
        }
    }

    // [GET] /api/auth/register
    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $validateUser = Validator::make($request->all(), [
                'email' => [
                    'required',
                    'email',
                ],
                'name' => [
                    'required',
                    'string',
                ],
                'password' => [
                    'required',
                ],
            ]);
            //      Kiếm tra các trường có nhập đúng giá trị không
            if ($validateUser->fails()) {
                $array = $validateUser->errors()->toArray();
                return handleResponseAPI(array_shift($array)[0]);
            }

            //      Kiếm tra user có tồn tại không
            $user = $this->checkUserSurviveByEmailRegister($request->get('email'));
            if (!$user['type'])
                return handleResponseAPI($user['body']);

            //      Nếu không lỗi gì ở trên thì tạo password cho user
            $password = Hash::make($request->get('password'));

            if ($user['role'] === 'no_password') {
                $user = $user['body'];
                User::query()->find($user->id)->update([
                    'password' => $password,
                ]);

                $user = $this->generateTokenAndReplaceUserInfo($user);
                return handleResponseAPI('Thêm mật khẩu cho user thành công', true, $user);
            }

            //      Lọt vào role 2: không có email thì tạo user mới
            $user = User::query()->create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => $password,
                'level_id' => 1,
            ]);

            $user = $this->generateTokenAndReplaceUserInfo($user);

            return handleResponseAPI('Tạo user mới thành công', true, $user);

        } catch (\Throwable $e) {
            return handleResponseAPI('Có lỗi xảy ra! ' . $e->getMessage());
        }
    }

    // [POST] /api/auth/change-avatar
    public function changeAvatar(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $validateFile = Validator::make($request->all(), [
                'avatar' => [
                    'mimes:jpeg,jpg,png,gif',
                    'required',
                ],
            ]);
            if ($validateFile->fails()) {
                $array = $validateFile->errors()->toArray();
                return handleResponseAPI(array_shift($array)[0]);
            }

            $user = User::query()->find($request->user()->id);
            $userAvatar = $user->avatar;

            if (!(is_null($userAvatar) || str_contains($userAvatar, 'https')))
                unlink("storage/$userAvatar");

            $fileAvatarExtension = $request->file('avatar')->extension();
            $fileAvatarName = "avatar.$fileAvatarExtension";
            $fileAvatarUrl = Storage::disk('public')
                ->putFileAs("avatars/$user->id",
                    $request->file('avatar'),
                    $fileAvatarName
                );

            $user->update([
                'avatar' => $fileAvatarUrl,
            ]);

            return handleResponseAPI('Thay đổi ảnh đại diện thành công', true, $user->avatar_url);
        } catch (\Throwable $e) {
            return handleResponseAPI('Có lỗi xảy ra! ' . $e->getMessage());
        }
    }

    // [POST] /api/auth/change-info
    public function changeInfo(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $userId = $request->user()->id;
            $validateInfo = Validator::make($request->all(), [
                'name' => [
                    'required',
                    'string',
                ],
                'gender' => [
                    'required',
                    Rule::in(UserGenderEnum::asArray()),
                ],
                'email' => [
                    'email',
                    'required',
                    Rule::unique(User::class, 'email')->ignore($userId),
                ]
            ]);

            if ($validateInfo->fails()) {
                $array = $validateInfo->errors()->toArray();
                return handleResponseAPI(array_shift($array)[0]);
            }

            $user = User::query()->find($userId);
            $user->update($request->all());

            $userUpdate = $this->generateTokenAndReplaceUserInfo($user->fresh());

            return handleResponseAPI('Thay đổi thông tin cá nhân thành công', true, $userUpdate);
        } catch (\Throwable $e) {
            return handleResponseAPI('Có lỗi xảy ra! ' . $e->getMessage());
        }
    }

    // [POST] /api/auth/change-password
    public function changePassword(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $userId = $request->user()->id;
            $validatePassword = Validator::make($request->all(), [
                'old_password' => [
                    'string',
                    'required',
                ],
                'new_password' => [
                    'string',
                    'required'
                ]
            ]);

            if ($validatePassword->fails()) {
                $array = $validatePassword->errors()->toArray();
                return handleResponseAPI(array_shift($array)[0]);
            }

            $user = User::query()->find($userId);

            if (!Hash::check($request->get('old_password'), $user->password))
                return handleResponseAPI('Mật khẩu cũ bị sai');

            $user->update(['password' => Hash::make($request->new_password)]);

            return handleResponseAPI('Đổi mật khẩu thành công', true);
        } catch (\Throwable $e) {
            return handleResponseAPI('Có lỗi xảy ra! ' . $e->getMessage());
        }
    }

    public function checkUserSurviveByEmailLogin($email): array
    {
        $user = User::query()
            ->where('email', $email)
            ->first();

        if (!is_null($user)) {
            return [
                'type' => true,
                'body' => $user,
            ];
        }

        // Kiểm tra người dùng đã bị khóa chưa
        $onlyTrashed = $this->checkOnlyTrashed($email);

        if (!$onlyTrashed['type'])
            return $onlyTrashed;

        return [
            'type' => false,
            'body' => 'Không có người dùng nào với email này',
        ];
    }

    public function checkUserSurviveByEmailRegister($email): array
    {
        $user = User::query()
            ->where('email', $email)
            ->first();

        //      Nếu mà user tồn tại thì tiếp tục kiếm tra password có tồn tại không
        if (!is_null($user)) {
            //  nếu mà password null thì trả về true với thông tin là người dùng chưa có password
            if (is_null($user->password)) {
                return [
                    'type' => true,
                    'role' => 'no_password',
                    'body' => $user,
                ];
            }
            //  nếu mà password khác null thì người dùng này đã tồn tại
            return [
                'type' => false,
                'body' => 'Email đã tồn tại',
            ];
        }

        // Kiểm tra người dùng đã bị khóa chưa
        $onlyTrashed = $this->checkOnlyTrashed($email);

        if (!$onlyTrashed['type'])
            return $onlyTrashed;

        //      Trường hợp còn lại trả về true với thông báo không có email
        return [
            'type' => true,
            'role' => 'no_email',
            'body' => $email,
        ];
    }

    public function checkOnlyTrashed($email): bool|array
    {
        $user = User::onlyTrashed()
            ->where('email', $email)
            ->first();

        //      Kiểm tra xem người dùng này có bị cho vào sổ đen không
        if (!is_null($user))
            return [
                'type' => false,
                'body' => 'Tài khoản đã bị khóa',
            ];
        return [
            'type' => true,
            'body' => 'Tài khoản chưa bị khóa',
        ];
    }

    public function generateTokenAndReplaceUserInfo($user): array
    {
        //      Tạo ra token đăng nhập cho user
        $token = encrypt($user->id);

        //      Lấy ra level name
        $userLevel = Level::query()->find($user->level_id, 'name')->name;

        //      Replace user lại trả về những thứ cần thiết thâu
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'gender' => $user->gender_name,
            'level' => $userLevel,
            'avatar' => $user->avatar_url,
            'password' => $user->password,
            'token' => $token,
        ];
    }

    public function test(Request $request)
    {
        $user = $request->user();
        return handleResponseAPI('oke', 'success', $user);
    }
}
