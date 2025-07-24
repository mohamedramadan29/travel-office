@extends('admin.layouts.app')
@section('title')
    اضافة فاتورة شراء
@endsection
@section('content')
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="mb-2 content-header-left col-md-6 col-12 breadcrumb-new">
                    <h3 class="mb-0 content-header-title d-inline-block">اضافة فاتورة شراء</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.welcome') }}">الرئيسية </a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.purches_invoices.index') }}">فواتير
                                        الشراء</a>
                                </li>
                                <li class="breadcrumb-item active"><a href="#">اضافة فاتورة شراء</a>
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
                                        <form class="form" action="{{ route('dashboard.purches_invoices.store') }}"
                                            method="POST">
                                            @csrf
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label> نوع الفاتورة </label>
                                                            <select class="form-control" name="type">
                                                                <optgroup label="نوع الفاتورة">
                                                                    <option selected value="فاتورة مؤقتة"> فاتورة مؤقتة
                                                                    </option>
                                                                    <option value="فاتورة رسمية"> فاتورة رسمية </option>
                                                                </optgroup>
                                                            </select>
                                                            <span> الفاتورة المؤقتة غير نهائية ولا تؤثر على المخزون </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="userinput1"> البيان / الوصف </label>
                                                            <input required type="text" id="userinput1"
                                                                class="form-control" name="bayan_txt">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="userinput1"> الرقم المرجعي </label>
                                                            <input required type="text" id="userinput1"
                                                                class="form-control" name="referance_number">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="userinput1"> الكمية </label>
                                                            <input type="number" id="userinput1" class="form-control"
                                                                name="qyt" value="1" min="1">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="userinput1"> سعر الشراء </label>
                                                            <input type="number" step="0.01" id="userinput1"
                                                                class="form-control" name="purches_price">
                                                        </div>
                                                    </div>
                                                </div>

                                                <hr>
                                                <h4 class="card-title" id="basic-layout-colored-form-control"> <strong>
                                                        بيانات المورد </strong> </h4>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="userinput1"> المورد </label>
                                                            <select name="supplier_id" id="userinput1" class="form-control">
                                                                <option value="">اختر المورد</option>
                                                                @foreach ($suppliers as $supplier)
                                                                    <option value="{{ $supplier->id }}">
                                                                        {{ $supplier->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="supplier-details"
                                                        style="background: #f8f9fa; border-radius: 10px;  padding: 15px;  margin-top: 10px;  border: 1px solid rgba(44, 62, 80, 0.2);width:100%">
                                                        <div class="supplier-info"
                                                            style="display: flex;justify-content: space-between; margin-top: 10px;">
                                                            <div class="supplier-info-item"
                                                                style="font-size: 0.9rem;display: flex;align-items: center;gap: 8px;">
                                                                <i class="fas fa-phone"></i>
                                                                <div><strong>رقم الهاتف:</strong> <span
                                                                        id="purchase_supplier_phone">0551234567</span></div>
                                                            </div>
                                                            <div class="supplier-info-item"
                                                                style="font-size: 0.9rem;display: flex;align-items: center;gap: 8px;">
                                                                <i class="fab fa-whatsapp"></i>
                                                                <div><strong>رقم الواتساب:</strong> <span
                                                                        id="purchase_supplier_whatsapp">0551234567</span>
                                                                </div>
                                                            </div>
                                                            <div class="supplier-info-item"
                                                                style="font-size: 0.9rem;display: flex;align-items: center;gap: 8px;">
                                                                <i class="fas fa-envelope"></i>
                                                                <div><strong>البريد الإلكتروني:</strong> <span
                                                                        id="purchase_supplier_email">tech@example.com</span>
                                                                </div>
                                                            </div>
                                                            <div class="supplier-info-item"
                                                                style="font-size: 0.9rem;display: flex;align-items: center;gap: 8px;">
                                                                <i class="fas fa-map-marker-alt"></i>
                                                                <div><strong>العنوان:</strong> <span
                                                                        id="purchase_supplier_address">الرياض - حي
                                                                        المروج</span></div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                </div>
                                                <br>
                                                <br>
                                                <h4 class="card-title" id="basic-layout-colored-form-control"> <strong>
                                                        بيانات الدفع </strong> </h4>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="userinput1"> طريقة الدفع </label>
                                                            <select name="payment_method" id="userinput1"
                                                                class="form-control">
                                                                <option value="">اختر طريقة الدفع</option>
                                                                <option value="نقدا">نقدا</option>
                                                                <option value="شيك"> شيك </option>
                                                                <option value="تحويل بنكي"> تحويل بنكي </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="userinput1"> الخزينة </label>
                                                            <select name="safe_id" id="userinput1" class="form-control">
                                                                <option value="">اختر الخزينة</option>
                                                                @foreach ($safes as $safe)
                                                                    <option value="{{ $safe->id }}">{{ $safe->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="userinput1"> المدفوع (د.ل) </label>
                                                            <input type="number" id="userinput1" class="form-control"
                                                                name="paid">
                                                            <span> اتركه صفرًا للدفع لاحقًا </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="userinput1"> الباقي (د.ل) </label>
                                                            <input readonly type="number" id="userinput1"
                                                                class="form-control" name="paid">

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
