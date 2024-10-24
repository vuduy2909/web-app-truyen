<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
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
}
