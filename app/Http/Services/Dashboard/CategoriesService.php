<?php
namespace App\Http\Services\Dashboard;

use Yajra\DataTables\Facades\DataTables;
use App\Http\Repositories\Dashboard\CategoriesRepository;

class CategoriesService
{

    protected $categoriesRepository;

    public function __construct(CategoriesRepository $categoriesRepository)
    {
        $this->categoriesRepository = $categoriesRepository;
    }

    public function getAllServices(){
        return $this->categoriesRepository->getAll();
    }

    ########### For Yajra Datatable ###########
    public function getAll()
    {
        $categories = $this->categoriesRepository->getAll();
        return DataTables::of($categories)
            ->addIndexColumn()
            ->addColumn('name', function ($category) {
                return $category->getTranslation('name', app()->getLocale());
            })
            ->addColumn('status', function ($category) {
                return $category->getStatusTranslated();
            })
            ->addColumn('products_count',function($category){
                return $category->Products()->count() == 0 ? 'لا يوجد' : $category->Products()->count();
            })
            ->addColumn('action', function ($category) {
                return view('admin.categories.actions', compact('category'));
            })
            ->make(true);
    }


    ########### Get Category By Id ###########
    public function findById($id)
    {
        return $this->categoriesRepository->findById($id);
    }

    ############ Store Category

    public function store($data)
    {
        $category = $this->categoriesRepository->store($data);
        return $category;
    }

    ################ Update Category

    public function update($data)
    {
        $category = $this->categoriesRepository->findById($data['id']);

        return $this->categoriesRepository->update($category, $data);

    }

    ########## Delete Category

    public function delete($id)
    {
        $category = $this->categoriesRepository->findById($id);
        return $this->categoriesRepository->delete($category);
    }

    ################ Get Parent Categories

    public function getParentCategories()
    {
        return $this->categoriesRepository->getParentCategories();
    }

    ######## Get Categories Except Children


    public function getCategoriesExceptionChildren($id)
    {
        return $this->categoriesRepository->getCategoriesExceptionChildren($id);
    }

}
