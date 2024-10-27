<?php

namespace App\Http\Controllers\HandleAPI;

use App\Enums\UserGenderEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LinksAPIController extends Controller
{
    public static function linksAPIHandle(): array
    {
        return [
            'auth' =>[
                'login' => [
                    'method' => 'POST',
                    'url' => route('api.auth.login'),
                    'descriptions' => 'Gửi form đăng nhập đến, thêm các trường bắt buộc là name và email'
                ],
                'register' => [
                    'method' => 'POST',
                    'url' => route('api.auth.register'),
                    'descriptions' => 'Gửi form đăng ký hoặc thêm mật khẩu đến, ' .
                        'thêm các trường bắt buộc là name, email và password'
                ],
                'redirect' => [
                    'method' => 'GET',
                    'url' => route('api.auth.redirect', ':app'),
                    'descriptions' => 'Đăng nhập bằng google hoặc facebook, ' .
                        'thay thế ":app" bằng "google" hoặc "facebook" để đăng nhập'
                ],
                'change_avatar' => [
                    'method' => 'POST',
                    'url' => route('api.auth.changeAvatar'),
                    'descriptions' => 'Gửi form thay đổi ảnh, ' .
                        'thêm trường bắt buộc là avatar, sử dụng Authorization để có thể thay đổi ảnh'
                ],
                'change_info' => [
                    'method' => 'POST',
                    'url' => route('api.auth.changeInfo'),
                    'descriptions' => [
                        'content' =>
                            'Gửi form thay đổi thông tin cá nhân, thêm trường bắt buộc là name, email, gender, ' .
                            'sử dụng Authorization để có thể thay đổi thông tin',
                        'gender_value' => UserGenderEnum::getArrayView()
                    ]
                ],
                'change_password' => [
                    'method' => 'POST',
                    'url' => route('api.auth.changePassword'),
                    'descriptions' =>
                        'Gửi form thay đổi mật khẩu, thêm trường bắt buộc là old_password, new_password, ' .
                            'sử dụng Authorization để có thể thay đổi mật khẩu'
                ]
            ],
            'categories' => [
                'list' => [
                    'method' => 'GET',
                    'url' => route('api.categories.list'),
                    'descriptions' => 'Trả về danh sách các thể loại'
                ],
                'show' => [
                    'method' => 'GET',
                    'url' => route('api.categories.show', ':slug'),
                    'descriptions' => 'Trả về tất cả các truyện có thể loại ":slug", ' .
                        'thay thế ":slug" thành slug của thể loại',
                ]
            ],
            'stories' => [
                'list' => [
                    'method' => 'GET',
                    'url' => route('api.stories.list'),
                    'descriptions' => 'Trả về danh sách truyện từ mới đến cũ, ' .
                        'nếu có query thì trả về truyện với tên theo query'
                ],
                'pin' => [
                    'method' => 'GET',
                    'url' => route('api.stories.pin'),
                    'descriptions' => 'Trả về những truyện được ghim'
                ],
                'show' => [
                    'method' => 'GET',
                    'url' => route('api.stories.show', ':slug'),
                    'descriptions' => 'Trả về thông tin của một truyện nào đó, " .
                        "thay thế ":slug" thành slug của truyện'
                ],
                'search' => [
                    'method' => 'GET',
                    'url' => route('api.stories.search') . '?q=:name_story',
                    'descriptions' => 'Trả về thông tin của những truyện với tên có query trong đó, ' .
                        'thay thế ":name_story" bằng tên truyện bạn muốn tìm'
                ],
                'tool_advanced_search' => [
                    'method' => 'GET',
                    'url' => route('api.stories.toolAdvancedSearch'),
                    'descriptions' => 'Trả về bộ lọc tìm kiếm nâng cao'
                ],
                'advanced_search' => [
                    'method' => 'GET',
                    'url' => route('api.stories.advancedSearch') .
                        '?categories%5B%5D=:cat&status=:status&author=:auth',
                    'descriptions' => 'Trả về danh sách truyện với những thông tin tìm kiếm nâng cao' .
                        'Thay thế ":cat", ":status", ":auth" bằng thông tin do "tool_advanced_search" trả về'
                ]
            ],
            'chapters' => [
                'list' => [
                    'method' => 'GET',
                    'url' => route('api.chapters.list', ':storySlug'),
                    'descriptions' => 'Trả về danh sách các chương của truyện với ":slug", ' .
                        'thay thế ":slug" thành slug của truyện'
                ],
                'show' => [
                    'method' => 'GET',
                    'url' => route('api.chapters.show', [':storySlug', ':number']),
                    'descriptions' => 'Trả về chương ":number" và danh sách các chương của truyện với ":slug", ' .
                        'thay thế ":slug" và ":number" bằng slug và number có trong: chapter > list.' .
                        '* lưu ý: sử dụng Authorization trong header để có thể lưu lịch sử đọc truyện'
                ]
            ],
            'histories' => [
                'list' => [
                    'method' => 'GET',
                    'url' => route('api.histories.list'),
                    'descriptions' => 'Trả về danh sách lịch sử đã đọc của người dùng, ' .
                        '* lưu ý: sử dụng Authorization trong header mới sử dụng được'
                ],
                'show' => [
                    'method' => 'GET',
                    'url' => route('api.histories.show', ':storySlug'),
                    'descriptions' => 'Trả về lịch sử truyện nào đó đã đọc của người dùng, ' .
                        '* lưu ý: sử dụng Authorization trong header mới sử dụng được'
                ],
                'destroy' => [
                    'method' => 'POST',
                    'url' => route('api.histories.destroy', ':storySlug'),
                    'descriptions' => 'Xóa lịch sử đọc truyện của người dùng, ' .
                        '* lưu ý: sử dụng Authorization trong header và truyền một tham số là "story_slug" trong form'
                ]
            ],
            'ranks' => [
                'list_top_views' => [
                    'method' => 'GET',
                    'url' => route('api.ranks.listTopViews'),
                    'descriptions' => 'Trả về danh sách truyện theo lượt view từ cao đến thấp'
                ],
                'list_top_stars' => [
                    'method' => 'GET',
                    'url' => route('api.ranks.listTopStars'),
                    'descriptions' => 'Trả về danh sách truyện theo số đánh giá từ cao đến thấp'
                ],
                'show_stars_by_story' => [
                    'method' => 'GET',
                    'url' => route('api.ranks.showStarsByStory', ':storySlug'),
                    'descriptions' => 'Trả về số đánh giá và số lượt đánh giá của truyện, ' .
                        'thay thế ":storySlug" bằng slug của truyện.'
                ],
                'create_stars' => [
                    'method' => 'POST',
                    'url' => route('api.ranks.createStars', ':storySlug'),
                    'descriptions' => 'Đánh giá truyện từ 1 sao đến 5 sao, ' .
                        'thêm trường "stars" vào form để có thể đánh giá, ' .
                        'thay thế ":storySlug" bằng slug của truyện, ' .
                        'Có thể thêm Authorization vào header để đánh giá hoặc không.'
                ],
            ]
        ];
    }
}
