<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FrontPage\HistoryController;
use App\Http\Controllers\FrontPage\HomeController;
use App\Http\Controllers\FrontPage\StarController;
use App\Models\Category;
use App\Models\Story;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

