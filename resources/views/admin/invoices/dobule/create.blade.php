@extends('admin.layouts.app')
@section('title')
    اضافة فاتورة مزدوجة
@endsection
@section('content')
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="mb-2 content-header-left col-md-6 col-12 breadcrumb-new">
                    <h3 class="mb-0 content-header-title d-inline-block">اضافة فاتورة مزدوجة</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.welcome') }}">الرئيسية </a>
                                </li>
                                <li class="breadcrumb-item active"><a href="#">اضافة فاتورة مزدوجة</a>
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
                                    <h4 class="card-title" id="basic-layout-colored-form-control"> <strong> تفاصيل الفاتورة
                                        </strong> </h4>
                                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                </div>
                                <div class="card-content collapse show">
                                    <div class="card-body">
                                        @include('admin.layouts.validation_errors')
                                        <form class="form" action="{{ route('dashboard.double_invoices.store') }}"
                                            method="POST">
                                            @csrf
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label> نوع الفاتورة </label>
                                                            <select class="form-control" name="type">
                                                                <optgroup label="نوع الفاتورة">
                                                                    <option selected value="فاتورة رسمية"> فاتورة رسمية </option>
                                                                </optgroup>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="userinput1"> البيان / الوصف </label>
                                                            <input required type="text" id="userinput1"
                                                                class="form-control" name="bayan_txt"
                                                                value="{{ old('bayan_txt') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="userinput1"> الرقم المرجعي </label>
                                                            <input required type="text" id="userinput1"
                                                                class="form-control" name="referance_number"
                                                                value="{{ old('referance_number') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="category_id"> التصنيف </label>
                                                            <select name="category_id" id="category_id"
                                                                class="form-control">
                                                                <option value="">اختر التصنيف </option>
                                                                @foreach ($categories as $category)
                                                                    <option
                                                                        @if (old('category_id') == $category->id) selected @endif
                                                                        value="{{ $category->id }}">{{ $category->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                @livewire('dashboard.double-invoice-create', ['suppliers' => $suppliers, 'safes' => $safes,'categories'=>$categories,'clients'=>$clients])
                                            </div>
                                            <div class="form-actions right">
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
