@extends('admin.layouts.app')
@section('title')
    تعديل راتب
@endsection
@section('content')
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="mb-2 content-header-left col-md-6 col-12 breadcrumb-new">
                    <h3 class="mb-0 content-header-title d-inline-block">تعديل راتب</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.welcome') }}">الرئيسية </a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.employee_salary.index') }}"> ادارة رواتب الموظفين </a>
                                </li>
                                <li class="breadcrumb-item active"><a href="#"> تعديل راتب الموظف  </a>
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
                                    <h4 class="card-title" id="basic-layout-colored-form-control"> تعديل راتب الموظف   </h4>
                                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>

                                </div>
                                <div class="card-content collapse show">
                                    <div class="card-body">
                                        @include('admin.layouts.validation_errors')
                                        <form class="form" action="{{ route('dashboard.employee_salary.update',$salary->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="userinput1"> حدد الموظف  </label>
                                                             <select name="admin_id" id="" class="form-control">
                                                                <option value=""> -- حدد الموظف  -- </option>
                                                                @foreach ($employees as $admin)
                                                                    <option @if ($admin->id == $salary->admin_id) selected @endif value="{{ $admin->id }}">{{ $admin->name }}</option>
                                                                @endforeach
                                                             </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="userinput1">   ادخل المبلغ  </label>
                                                            <input type="number" id="userinput1"
                                                                class="form-control"
                                                                name="salary" value="{{ $salary->salary }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label> حدد الخزينة  </label>
                                                            <select class="form-control" name="safe_id">
                                                                <optgroup label="حدد الخزينة">
                                                                    @foreach ($safes as $safe)
                                                                        <option @if ($salary->safe_id == $safe->id) selected @endif value="{{ $safe->id }}">{{ $safe->name }}</option>
                                                                    @endforeach
                                                                </optgroup>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions right">
                                                <a href="{{ route('dashboard.employee_salary.index') }}" type="button" class="mr-1 btn btn-warning">
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
