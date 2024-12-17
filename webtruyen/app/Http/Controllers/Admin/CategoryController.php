<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Level\StoreRequest;
use App\Http\Requests\Level\UpdateRequest;
use App\Models\Category;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class CategoryController extends Controller
{
    private Builder $model;
    private string $table;
    private string $title;

    public function __construct()
    {
        $this->model = Category::query();
        $this->table = (new Category())->getTable();

        View::share('table', $this->table);
    }


    public function index(Request $request)
    {
        $q = $request->get('q');
        $query = $this->model->where('name', 'like', "%$q%");
        $data = $query->paginate(10);

        $this->title = 'Danh sách thể loại';
        View::share('title', $this->title);

        return view("user.$this->table.index", [
            'data' => $data,
            'q' => $q,
        ]);
    }

    public function create()
    {
        $this->title = 'Thêm thể loại';
        View::share('title', $this->title);
        return view("admin.$this->table.create");
    }

    public function store(StoreRequest $request)
    {
        $this->model->create($request->validated());
        return redirect()->route("admin.$this->table.index")
            ->with('success', 'Đã thêm thành công');
    }


    public function edit($id)
    {
        $category = $this->model->find($id);
        $this->title = 'Sửa thể loại';
        View::share('title', $this->title);

        return view("admin.$this->table.edit", [
            'category' => $category,
        ]);
    }

    public function update(UpdateRequest $request, $id)
    {
        $slug = SlugService::createSlug(Category::class, 'slug', $request->name);
        $data = $request->validated();
        $data['slug'] = $slug;
        $this->model->where('id', $id)->update($data);

        return redirect()->route("admin.$this->table.index")
            ->with('success', 'Đã sửa thành công');
    }

    public function destroy($id)
    {
        $this->model->find($id)->delete();
        return redirect()->route("admin.$this->table.index")
            ->with('success', 'Đã xóa thành công');
    }
}
