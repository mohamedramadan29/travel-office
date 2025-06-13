<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Http\Services\Dashboard\UserService;
use Illuminate\Http\Request;

use function Pest\Laravel\json;

class UserController extends Controller
{

    protected $userService;
    public function __construct(UserService $user_service)
    {
     $this->userService = $user_service;
    }
    public function index()
    {
        return view('admin.users.index');
    }
    public function getAll()
    {
        return $this->userService->getAll();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $data = $request->except(['_token']);
       $user = $this->userService->store($data);
       if($user){
       return response()->json([
        'status' => 'success',
        'message' => 'تم اضافة المستخدم بنجاح',
       ],201);
       }else{
        return response()->json([
            'status' => 'error',
            'message' => 'حدث خطأ اثناء اضافة المستخدم',
        ],500);
       }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
