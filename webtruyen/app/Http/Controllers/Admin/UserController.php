<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StoryPinEnum;
use App\Enums\UserGenderEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreRequest;
use App\Models\Level;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View as ViewAlias;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Throwable;
use function PHPUnit\Framework\directoryExists;

class UserController extends Controller
{
    private Builder $model;
    private string $table;
    private string $title;

    public function __construct()
    {
        $this->model = User::query();
        $this->table = (new User())->getTable();

        View::share('table', $this->table);
    }

    /**
     * @param Request $request
     * @return Application|Factory|ViewAlias
     */
    //    : ViewAlias|Factory|Application
    public function index(Request $request)
    {
        $q = $request->get('q');
        $genderFilter = $request->get('gender');
        $levelFilter = $request->get('level_id');

        $query = $this->model
            ->select('*')
            ->selectSub('Select count(*) from stories where user_id = users.id and pin > '. StoryPinEnum::UPLOADING , 'story_count')
            ->with('level')
            ->where('name', 'like', "%$q%")
            ->whereNot('id', Auth::user()->id)
            ->latest();

        if (isset($genderFilter) && $genderFilter !== 'All') {
            $query = $query->where('gender', $genderFilter);
        }
        if (isset($levelFilter) && $levelFilter !== 'All') {
            $query = $query->where('level_id', $levelFilter);
        }

        $data = $query->paginate();

        //        genders
        $gendersEnum = UserGenderEnum::getValues();
        $genders = [];
        foreach ($gendersEnum as $gender) {
            $genders[$gender] = UserGenderEnum::getNameByValue($gender);
        }

        //       level
        $levels = Level::query()->get([
            'id',
            'name',
        ]);


        $this->title = 'Quản lý người dùng';
        View::share('title', $this->title);

        return view("admin.$this->table.index", [
            'data' => $data,
            'q' => $q,
            'genders' => $genders,
            'levels' => $levels,
            'genderFilter' => $genderFilter,
            'levelFilter' => $levelFilter,
        ]);
    }

    
 



 




}
