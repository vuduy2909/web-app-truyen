<?php


use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ChapterController;
use App\Http\Controllers\Admin\LevelController;
use App\Http\Controllers\Admin\StoryController;
use App\Http\Controllers\Admin\UserController;
use App\Models\Category;
use App\Models\Chapter;
use App\Models\Story;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;
use Illuminate\Support\Facades\Route;

Route::get('/', [AdminController::class, 'index'])->name('index');

Route::prefix('levels')->name('levels.')
    ->middleware('admin')  // kiểm tra phải là admin
    ->controller(LevelController::class)->group(function() {
    Route::get('/', 'index')->name('index');
    Route::get('/create', 'create')->name('create');
    Route::post('/create', 'store')->name('store');
    Route::get('/edit/{id}', 'edit')->name('edit');
    Route::put('/edit/{id}', 'update')->name('update');
    Route::delete('/destroy/{id}', 'destroy')->name('destroy');
});


Route::prefix('users')->name('users.')
    ->middleware('admin')  // kiểm tra phải là admin
    ->controller(UserController::class)->group(function() {
    Route::get('/', 'index')->name('index');
    Route::get('/black_list', 'blackList')->name('black_list');
    Route::get('/create', 'create')->name('create');
    Route::post('/create', 'store')->name('store');
    Route::delete('/destroy/{id}', 'destroy')->name('destroy');

    Route::post('/restore/{id}', 'restore')->name('restore');
    Route::delete('/kill/{id}', 'kill')->name('kill');
});


Route::prefix('categories')->name('categories.')
    ->controller(CategoryController::class)->group(function() {
    Route::get('/', 'index')->name('index');
    Route::get('/create', 'create')->name('create');
    Route::post('/create', 'store')->name('store');
    Route::get('/edit/{id}', 'edit')->name('edit');
    Route::put('/edit/{id}', 'update')->name('update');
    Route::delete('/destroy/{id}', 'destroy')->name('destroy')
        ->middleware('admin'); // kiểm tra phải là admin
});

Route::prefix('stories')->name('stories.')->group(function () {

    Route::controller(StoryController::class)->group(function() {
        Route::get('/', 'index')->name('index');
        Route::get('/view/{id}', 'view')->name('view');
        Route::get('/find', 'find')->name('find');

        Route::get('/create', 'create')->name('create');
        Route::post('/create', 'store')->name('store');

        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::put('/edit/{id}', 'update')->name('update');

        Route::delete('/destroy/{id}', 'destroy')->name('destroy');

        Route::post('/restore/{id}', 'restore')->name('restore');
        Route::delete('/kill/{id}', 'kill')->name('kill');

        Route::post('/approve/{id}', 'approve')->name('approve');
        Route::post('/un_approve/{id}', 'un_approve')->name('un_approve');
        Route::post('/pinned/{id}', 'pinned')->name('pinned');
    });

    Route::controller(ChapterController::class)->name('chapters.')->group(function () {
        Route::get('/view/{id}/chuong-{number}', 'show')->name('show');
        Route::post('/approve/{id}/chuong-{number}', 'approve')->name('approve');
        Route::post('/approve_all/{id}/', 'approve')->name('approve_all');
        Route::post('/un_approve/{id}/chuong-{number}', 'unApprove')->name('un_approve');
    });
});

Route::prefix('chapters')->name('chapters.')->controller(ChapterController::class)
    ->group(function (){
    Route::get('/', 'index')->name('index');
});





Breadcrumbs::for('admin.index', function(BreadcrumbTrail $trail) {
    $trail->parent('index');
    $trail->push('Quản lý', route('admin.index'));
});

// Cấp bậc
Breadcrumbs::for('admin.levels.index', function(BreadcrumbTrail $trail) {
    $trail->parent('admin.index');
    $trail->push('Danh sách Cấp bậc', route('admin.levels.index'));
});
Breadcrumbs::for('admin.levels.create', function(BreadcrumbTrail $trail) {
    $trail->parent('admin.levels.index');
    $trail->push('Thêm cấp bậc', route('admin.levels.create'));
});
Breadcrumbs::for('admin.levels.edit', function(BreadcrumbTrail $trail) {
    $trail->parent('admin.levels.index');
    $trail->push('Sửa cấp bậc', route('admin.levels.edit'));
});

// Người dùng
Breadcrumbs::for('admin.users.index', function(BreadcrumbTrail $trail) {
    $trail->parent('admin.index');
    $trail->push('Danh sách Người dùng', route('admin.users.index'));
});
Breadcrumbs::for('admin.users.create', function(BreadcrumbTrail $trail) {
    $trail->parent('admin.users.index');
    $trail->push('Thêm người dùng', route('admin.users.create'));
});
Breadcrumbs::for('admin.users.black_list', function(BreadcrumbTrail $trail) {
    $trail->parent('admin.users.index');
    $trail->push('Danh sách người dùng bị xóa', route('admin.users.black_list'));
});

// Thể loại
Breadcrumbs::for('admin.categories.index', function(BreadcrumbTrail $trail) {
    $trail->parent('admin.index');
    $trail->push('Danh sách Thể loại', route('admin.categories.index'));
});
Breadcrumbs::for('admin.categories.create', function(BreadcrumbTrail $trail) {
    $trail->parent('admin.categories.index');
    $trail->push('Thêm Thể loại', route('admin.categories.create'));
});
Breadcrumbs::for('admin.categories.edit', function(BreadcrumbTrail $trail, Category $category) {
    $trail->parent('admin.categories.index');
    $trail->push('Sửa thể loại', route('admin.categories.edit', $category));
});

// truyện
Breadcrumbs::for('admin.stories.index', function(BreadcrumbTrail $trail) {
    $trail->parent('admin.index');
    $trail->push('Danh sách truyện', route('admin.stories.index'));
});
Breadcrumbs::for('admin.stories.view', function(BreadcrumbTrail $trail, Story $story) {
    $trail->parent('admin.stories.index');
    $trail->push($story->name, route('admin.stories.view', $story->id));
});

// chương truyện
Breadcrumbs::for('admin.chapters.show', function(BreadcrumbTrail $trail, Story $story, Chapter $chapter) {
    $trail->parent('admin.stories.view', $story);
    $trail->push("Chương $chapter->number", route('admin.stories.chapters.show', [$story->id, $chapter->number]));
});

Breadcrumbs::for('admin.chapters.index', function(BreadcrumbTrail $trail) {
    $trail->parent('admin.index');
    $trail->push('Danh sách Chương chờ duyệt', route('admin.chapters.index'));
});
