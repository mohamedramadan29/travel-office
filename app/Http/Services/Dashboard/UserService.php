<?php
namespace App\Http\Services\Dashboard;

use App\Http\Repositories\Dashboard\UserRepository;
use Yajra\DataTables\Facades\DataTables;
class UserService
{
    public $userRepository;
    public function __construct(UserRepository $user_repository)
    {
        $this->userRepository = $user_repository;
    }
    public function getAll()
    {
        $users = $this->userRepository->getAll();
        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('country', function ($user) {
                return $user->country->name;
            })
            ->addColumn('governorate', function ($user) {
                return $user->governorate->name;
            })
            ->addColumn('city', function ($user) {
                return $user->city->name;
            })
            ->addColumn('num_orders', function ($user) {
                return $user->orders->count() > 0 ? $user->orders->count() : 'لا يوجد';
            })
            ->addColumn('is_active', function ($user) {
                return $user->status();
            })
            ->addColumn('action', function ($user) {
                return view('admin.users.datatables.actions', compact('user'));
            })
            ->make(true);
    }

    public function store($data){
        $user = $this->userRepository->store($data);
        return $user;
    }
}
