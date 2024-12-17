<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\View as ViewAlias;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class AdminController extends Controller
{
    public function index()
    {
        $allUsers = User::withTrashed()->count();
        $userCount = User::query()->where('level_id', '=', 1)->count();
        $censorCount = User::query()->where('level_id', '=', 2)->count();
        $adminCount = User::query()->where('level_id', '=', 3)->count();
        $blackListCount = User::onlyTrashed()->count();

        $userPercentage = round($userCount/$allUsers * 100);
        $censorPercentage = round($censorCount/$allUsers * 100);
        $adminPercentage = round($adminCount/$allUsers * 100);
        $blackListPercentage = round($blackListCount/$allUsers * 100);

        $maxViewStory = ViewAlias::showTopViewAll()
            ->first();
        $minViewStory = ViewAlias::showTopViewAll()->last();

        View::share('title', 'Quản lý');
        return view('admin.index', [
            'allUsers' => $allUsers,
            'userCount' => $userCount,


            'userPercentage' => $userPercentage,


            'maxViewStory' => $maxViewStory,
            'minViewStory' => $minViewStory,
        ]);
    }
}
