@extends('admin.layouts.app')

@section('title', ' تعديل القسم ')

@section('content')
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="mb-2 content-header-left col-md-6 col-12 breadcrumb-new">
                    <h3 class="mb-0 content-header-title d-inline-block"> الاقسام </h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.welcome') }}">الرئيسية </a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.categories.index') }}"> الاقسام
                                    </a>
                                </li>
                                <li class="breadcrumb-item active"><a href="#"> تعديل القسم </a>
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
                                    <h4 class="card-title" id="basic-layout-form"> تعديل القسم </h4>
                                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="mb-0 list-inline">
                                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                            <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                            <li><a data-action="close"><i class="ft-x"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-content collapse show">
                                    <div class="card-body">
                                        @include('admin.layouts.validation_errors')
                                        <form class="form" method="POST"
                                            action="{{ route('dashboard.categories.update', $category->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="id" value="{{ $category->id }}">
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="projectinput1"> اسم القسم </label>
                                                            <input type="text" id="projectinput1" class="form-control"
                                                                placeholder="  " name="name[ar]"
                                                                value="{{ $category->getTranslation('name', 'ar') }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="projectinput1"> اسم القسم بالانجليزي </label>
                                                            <input type="text" id="projectinput1" class="form-control"
                                                                placeholder="  " name="name[en]"
                                                                value="{{ $category->getTranslation('name', 'en') }}">
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="row">

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="projectinput3"> حدد الاب </label>
                                                            <select name="parent" id="" class="form-control">
                                                                <option value=""> -- حدد الاب -- </option>
                                                                <option value="" {{ $category->parent == null ? 'selected' : '' }}> قسم رئيسي </option>
                                                                @foreach ($categories as $cat)
                                                                    <option value="{{ $cat['id'] }}" @selected($cat['id'] == $category->parent)>
                                                                        {{ $cat['name'] }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="projectinput1"> حالة القسم </label>
                                                            <select name="status" id="" class="form-control">
                                                                <option value="" selected disabled> -- حالة القسم --
                                                                </option>
                                                                <option value="1"
                                                                    {{ $category->status == 1 ? 'selected' : '' }}> مفعل
                                                                </option>
                                                                <option value="0"
                                                                    {{ $category->status == 0 ? 'selected' : '' }}> غير مفعل
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="form-actions">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="la la-check-square-o"></i> حفظ
                                                </button>
                                                <a href="{{ url()->previous() }}" type="button" class="mr-1 btn btn-warning">
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
