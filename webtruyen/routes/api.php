<?php

use App\Http\Controllers\HandleAPI\AuthController;
use App\Http\Controllers\HandleAPI\CategoryController;
use App\Http\Controllers\HandleAPI\ChapterController;
use App\Http\Controllers\HandleAPI\CommentController;
use App\Http\Controllers\HandleAPI\HistoryController;
use App\Http\Controllers\HandleAPI\RankController;
use App\Http\Controllers\HandleAPI\StoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// -----------------URL Authentication-----------------
Route::prefix('auth')->name('auth.')
    ->controller(AuthController::class)->group(function () {
        Route::get('/redirect/{provider}', 'redirect')->name('redirect');
        Route::get('/callback/{provider}', 'callback')->name('callback');
        Route::get('test', 'test')->middleware('api_auth')
            ->name('test');

        Route::post('/login', 'login')->name('login');
        Route::post('/register', 'register')->name('register');

        Route::post('/change-avatar', 'changeAvatar')->middleware('api_auth')
            ->name('changeAvatar');
        Route::post('/change-info', 'changeInfo')->middleware('api_auth')
            ->name('changeInfo');
        Route::post('/change-password', 'changePassword')->middleware('api_auth')
            ->name('changePassword');
    });

// -----------------URL Categories-----------------

Route::prefix('categories')->name('categories.')
    ->controller(CategoryController::class)->group(function () {
        Route::get('/list', 'listCategory')->name('list');
        Route::get('/show/{slug}', 'showStoriesByCategory')->name('show');
    });

// -----------------URL Stories-----------------
Route::prefix('stories')
    ->name('stories.')
    ->controller(StoryController::class)
    ->group(function () {
        Route::get('/list', 'listStories')->name('list');
        Route::get('/pin', 'pinStories')->name('pin');
        Route::get('/show/{slug}', 'showStory')->name('show');
        Route::get('/search', 'searchStories')->name('search');
        Route::get('/tool-advanced-search', 'toolAdvancedSearch')->name('toolAdvancedSearch');
        Route::get('/advanced-search', 'advancedSearchStories')->name('advancedSearch');
    });

// -----------------URL Chapter-----------------
Route::prefix('chapters')
    ->name('chapters.')
    ->controller(ChapterController::class)
    ->group(function () {
        Route::get('/list/{storySlug}', 'listChaptersByStory')->name('list');
        Route::get('/download/{storySlug}', 'listChaptersDownLoadByStory')->name('download');
        Route::get('/show/{storySlug}/chapter-{number}', 'showChapterByStory')->name('show');
    });

// -----------------URL History-----------------
Route::prefix('histories')
    ->name('histories.')
    ->middleware('api_auth')
    ->controller(HistoryController::class)
    ->group(function () {
        Route::get('list', 'listHistories')->name('list');
        Route::get('show/{slug}', 'showHistoryByStory')->name('show');
        Route::post('destroy/{slug}', 'destroyHistory')->name('destroy');
    });

Route::prefix('ranks')
    ->name('ranks.')
    ->controller(RankController::class)
    ->group(function () {
        Route::get('/list-top-views', 'listTopViews')->name('listTopViews');
        Route::get('list-top-stars', 'listTopStars')->name('listTopStars');
        Route::get('/show-stars/{storySlug}', 'showStarsByStory')->name('showStarsByStory');
        Route::post('/create-stars/{storySlug}', 'createStars')->name('createStars');
    });

//  Comment
Route::name('comments.')
    ->controller(CommentController::class)
    ->group(function () {
        Route::prefix('/stories/{slug}/comments')->group(function () {
            Route::get('/', 'listCommentsByStory')->name('listCommentsByStory');
            Route::middleware('api_auth')
                ->post('/', 'createComment')->name('createComment');
        });
        Route::middleware('api_auth')
        ->post('/comments/update/{id}', 'updateComment')->name('updateComment');
        Route::middleware('api_auth')
        ->post('/comments/delete/{id}', 'deleteComment')->name('deleteComment');
    });
