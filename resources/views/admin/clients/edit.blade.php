@extends('admin.layouts.app')
@section('title')
    تعديل عميل
@endsection
@section('content')
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="mb-2 content-header-left col-md-6 col-12 breadcrumb-new">
                    <h3 class="mb-0 content-header-title d-inline-block">تعديل عميل</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.welcome') }}">الرئيسية </a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.clients.index') }}">العملاء</a>
                                </li>
                                <li class="breadcrumb-item active"><a href="#">تعديل عميل</a>
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
                                    <h4 class="card-title" id="basic-layout-colored-form-control">تعديل عميل</h4>
                                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>

                                </div>
                                <div class="card-content collapse show">
                                    <div class="card-body">
                                        @include('admin.layouts.validation_errors')
                                        <form class="form" action="{{ route('dashboard.clients.update', $client->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="userinput1"> اسم العميل</label>
                                                            <input type="text" id="userinput1" class="form-control"
                                                                name="name" value="{{ $client->name }}">
                                                        </div>

                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="userinput1"> البريد الالكتروني</label>
                                                            <input type="email" id="userinput1" class="form-control"
                                                                name="email" value="{{ $client->email }}">
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="userinput1"> رقم الهاتف</label>
                                                            <input type="number" id="userinput1" class="form-control"
                                                                placeholder="" name="mobile" value="{{ $client->mobile }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="userinput1"> رقم التيلغرام</label>
                                                            <input type="number" id="userinput1" class="form-control"
                                                                placeholder="" name="telegram" value="{{ $client->telegram }}">
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="userinput1"> رقم الواتساب</label>
                                                            <input type="number" id="userinput1" class="form-control"
                                                                placeholder="" name="whatsapp" value="{{ $client->whatsapp }}">
                                                        </div>

                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>الحالة</label>
                                                            <select class="form-control" name="status">
                                                                <optgroup label="الحالة">
                                                                    <option @selected($client->status == 1) value="1">نشط</option>
                                                                    <option @selected($client->status == 0) value="0">غير نشط</option>
                                                                </optgroup>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="userinput1"> العنوان</label>
                                                            <input type="text" id="userinput1" class="form-control"
                                                                placeholder="" name="address" value="{{ $client->address }}">
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions right">
                                                <a href="{{ route('dashboard.clients.index') }}" type="button"
                                                    class="mr-1 btn btn-warning">
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
