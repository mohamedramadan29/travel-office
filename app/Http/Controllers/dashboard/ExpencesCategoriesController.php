<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Http\Traits\Message_Trait;
use App\Models\admin\ExpenceCategory;
use Illuminate\Http\Request;
use Laravel\Ui\Presets\React;

class ExpencesCategoriesController extends Controller
{


    use Message_Trait;

    public function index(){
        $categories = ExpenceCategory::orderBy('id','DESC')->paginate(10);
        return view('admin.expenses-categories.index',compact('categories'));
    }
    public function create(){
        return view('admin.expenses-categories.create');
    }
    public function store(Request $request){
        $data = $request->all();
        ExpenceCategory::create($data);
        return $this->success_message(' تم اضافة التصنيف بنجاح  ');
    }
    public function edit($id){
        $category = ExpenceCategory::findOrFail($id);
        return view('admin.expenses-categories.update',compact('category'));
    }
    public function update(Request $request,$id){
        $category = ExpenceCategory::findOrFail($id);
        $data = $request->all();
        $category->update($data);
        return $this->success_message(' تم تعديل التصنيف بنجاح ');
    }
    public function destroy($id){
        $category = ExpenceCategory::findOrFail($id);
        $category->delete();
        return $this->success_message(' تم حذف التصنيف بنجاح ');
    }
}
