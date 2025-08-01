@extends('admin.layouts.app')
@section('title')
    اضافة موظف جديد
@endsection
@section('content')
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="mb-2 content-header-left col-md-6 col-12 breadcrumb-new">
                    <h3 class="mb-0 content-header-title d-inline-block">اضافة موظف جديد</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.welcome') }}">الرئيسية </a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.admins.index') }}">الموظفين</a>
                                </li>
                                <li class="breadcrumb-item active"><a href="#">اضافة موظف جديد</a>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <section id="basic-form-layouts">
                    <div class="row match-height">
                        <div class="col-md-12">

                            <div class="card">

                                <div class="card-header">
                                    <h4 class="card-title" id="basic-layout-colored-form-control">اضافة موظف جديد</h4>
                                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>

                                </div>
                                <div class="card-content collapse show">
                                    <div class="card-body">
                                        @include('admin.layouts.validation_errors')
                                        <form class="form" action="{{ route('dashboard.admins.store') }}" method="POST">
                                            @csrf
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="userinput1"> اسم الموظف</label>
                                                            <input type="text" id="userinput1"
                                                                class="form-control"
                                                                name="name">
                                                        </div>

                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="userinput1"> البريد الالكتروني</label>
                                                            <input type="text" id="userinput1"
                                                                class="form-control"
                                                                name="email">
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="userinput1"> كلمة المرور</label>
                                                            <input type="passwrod" id="userinput1"
                                                                class="form-control"
                                                                placeholder="" name="password">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="userinput1"> تاكيد كلمة المرور</label>
                                                            <input type="password" id="userinput1"
                                                                class="form-control"
                                                                placeholder=""
                                                                name="password_confirmation">
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>الصلاحيه</label>
                                                            <select class="form-control" name="role_id">
                                                                <optgroup label="الصلاحيه">
                                                                    @foreach ($roles as $role)
                                                                        <option value="{{ $role->id }}">
                                                                            {{ $role->role }}
                                                                        </option>
                                                                    @endforeach
                                                                </optgroup>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mt-1 form-group">
                                                            <label>الحالة</label>
                                                            <select class="form-control" name="status">
                                                                <optgroup label="الحالة">
                                                                    <option value="1">نشط</option>
                                                                    <option value="0">غير نشط</option>
                                                                </optgroup>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions right">
                                                <a href="{{ route('dashboard.admins.index') }}" type="button" class="mr-1 btn btn-warning">
                                                    <i class="ft-x"></i> الغاء
                                                </a>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="la la-check-square-o"></i> حفظ
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
