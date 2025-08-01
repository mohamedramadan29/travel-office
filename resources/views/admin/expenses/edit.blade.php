@extends('admin.layouts.app')

@section('title', ' تعديل مصروف  ')

@section('content')
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="mb-2 content-header-left col-md-6 col-12 breadcrumb-new">
                    <h3 class="mb-0 content-header-title d-inline-block"> ادارة المصروفات </h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.welcome') }}">الرئيسية </a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.expenses.index') }}"> ادارة المصروفات
                                    </a>
                                </li>
                                <li class="breadcrumb-item active"><a href="#"> تعديل المصروف  </a>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>

            </div>
            <div class="content-body">
                <!-- Basic form layout section start -->
                <section id="basic-form-layouts">
                    <div class="row match-height">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title" id="basic-layout-form"> تعديل مصروف  </h4>

                                </div>
                                <div class="card-content collapse show">
                                    <div class="card-body">
                                        @include('admin.layouts.validation_errors')
                                        <form class="form" method="POST"
                                            action="{{ route('dashboard.expenses.update',$expense->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="projectinput1"> حدد  تصنيف المصروف    </label>
                                                            <select name="category_id" id="" class="form-control">
                                                                <option value="" selected disabled> -- حدد -- </option>
                                                                @foreach ($categories as $category)
                                                                    <option value="{{ $category->id }}" {{ old('category_id',$expense->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="projectinput1"> المبلغ   </label>
                                                            <input type="number" id="projectinput1" class="form-control"
                                                                placeholder="  " name="price"
                                                                value="{{ old('price',$expense->price) }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="projectinput1"> الخزينة   </label>
                                                            <select name="safe_id" id="" class="form-control">
                                                                <option value="" selected disabled> -- الخزينة  --
                                                                </option>
                                                                @foreach ($safes as $safe)
                                                                    <option value="{{ $safe->id }}"
                                                                        {{ old('safe_id',$expense->safe_id) == $safe->id ? 'selected' : '' }}>
                                                                        {{ $safe->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="projectinput1">  اضافة ملاحظات    </label>
                                                            <textarea name="description" id="" cols="30" rows="5" class="form-control">{{ old('description',$expense->description) }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="la la-check-square-o"></i> حفظ
                                                </button>
                                                <a href="{{ route('dashboard.expenses.index') }}" type="button" class="mr-1 btn btn-warning">
                                                    <i class="ft-x"></i> رجوع
                                                </a>

                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </section>
                <!-- // Basic form layout section end -->
            </div>
        </div>
    </div>
@endsection
