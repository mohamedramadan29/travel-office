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
        return $categories;
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


}
