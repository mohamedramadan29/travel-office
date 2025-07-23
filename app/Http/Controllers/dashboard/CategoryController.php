<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Traits\Message_Trait;
use Illuminate\Http\Request;
use App\Models\admin\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Services\Dashboard\CategoriesService;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    use Message_Trait;

    protected $categoryService;
    public function __construct(CategoriesService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $categories = $this->categoryService->getAll();

        return view('admin.categories.index', compact('categories'));
    }

    public function CategoryAll()
    {

        return $this->categoryService->getAll();
    }

    public function create()
    {
        return view('admin.categories.create');
    }


    public function store(CategoryRequest $request)
    {
        $data = $request->only(['name', 'status', 'parent']);
        if (!$this->categoryService->store($data)) {
            return $this->Error_message(' لم تتم العملية بنجاح  ');
        } else {
            return $this->success_message(' تمت الاضافة بنجاح  ');
        }

    }

    public function edit(string $id)
    {
        $category = $this->categoryService->findById($id);
        return view('admin.categories.update', compact('category'));
    }

    public function update(CategoryRequest $request, string $id)
    {
        $data = $request->only(['name', 'parent', 'status', 'id']);
        if (!$this->categoryService->update($data)) {
            return $this->Error_message(' لم تتم العملية بنجاح  ');
        } else {
            return $this->success_message(' تمت عملية التعديل بنجاح  ');
        }
    }


    public function destroy(string $id)
    {
        if (!$this->categoryService->delete($id)) {
            return $this->Error_message(' لم تتم عملية الحذف بنجاح  ');
        }
        return $this->success_message(' تمت عملية الحذف بنجاح  ');
    }
}
