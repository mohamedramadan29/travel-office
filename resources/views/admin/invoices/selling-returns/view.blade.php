@extends('admin.layouts.app')
@section('title')
    فاتورة ارجاع
@endsection
@section('content')
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="mb-2 content-header-left col-md-6 col-12 breadcrumb-new">
                    <h3 class="mb-0 content-header-title d-inline-block"> فاتورة ارجاع </h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">الرئيسية </a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ url('dashboard/selling_invoices') }}">فواتير
                                        البيع </a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ url('dashboard/selling_invoices_return') }}">فواتير
                                        الارجاع </a>
                                </li>
                                <li class="breadcrumb-item active"><a href="#"> مشاهدة فاتورة الارجاع </a>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <section class="users-list">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title" id="basic-layout-colored-form-control">
                                        <strong>عرض فاتورة الإرجاع #{{ $invoice->id ?? '' }}</strong>
                                    </h4>
                                </div>
                                <div class="card-content collapse show">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><strong>الرقم المرجعي:</strong></label>
                                                    <p>{{ $invoice->referance_number ?? '' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><strong>البيان / الوصف:</strong></label>
                                                    <p>{{ $invoice->bayan_txt ?? '' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><strong>التصنيف:</strong></label>
                                                    <p>{{ $invoice->category->name ?? '' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><strong>الكمية:</strong></label>
                                                    <p>{{ $invoice->qyt ?? '' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><strong>سعر البيع:</strong></label>
                                                    <p>{{ number_format($invoice->selling_price ?? 0, 3) }} د.ل</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><strong>السعر الكلي:</strong></label>
                                                    <p>{{ number_format($invoice->total_price ?? 0, 3) }} د.ل</p>
                                                </div>
                                            </div>
                                        </div>

                                        <hr>
                                        <h4 class="card-title"><strong>بيانات العميل</strong></h4>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><strong>العميل:</strong></label>
                                                    <p>{{ $invoice->client->name ?? '' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><strong>رقم الهاتف:</strong></label>
                                                    <p>{{ $invoice->client->mobile ?? '' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><strong>الواتساب:</strong></label>
                                                    <p>{{ $invoice->client->whatsapp ?? '' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><strong>البريد الإلكتروني:</strong></label>
                                                    <p>{{ $invoice->client->email ?? '' }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <hr>
                                        <h4 class="card-title"><strong>بيانات المورد</strong></h4>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><strong>المورد:</strong></label>
                                                    <p>{{ $invoice->supplier->name ?? '' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><strong>رقم الهاتف:</strong></label>
                                                    <p>{{ $invoice->supplier->mobile ?? '' }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <hr>
                                        <h4 class="card-title"><strong>بيانات الدفع</strong></h4>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><strong>طريقة الدفع:</strong></label>
                                                    <p>{{ $invoice->payment_method ?? '' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><strong>الخزينة:</strong></label>
                                                    <p>{{ $invoice->safe->name ?? '' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><strong>المدفوع:</strong></label>
                                                    <p>{{ number_format($invoice->paid ?? 0, 3) }} د.ل</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><strong>الباقي:</strong></label>
                                                    <p>{{ number_format($invoice->remaining ?? 0, 3) }} د.ل</p>
                                                </div>
                                            </div>
                                        </div>

                                        <hr>
                                        <h4 class="card-title"><strong>بيانات الإرجاع</strong></h4>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><strong>سعر الإرجاع:</strong></label>
                                                    <p>{{ number_format($invoice->return_price ?? 0, 3) }} د.ل</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><strong>تاريخ الإرجاع:</strong></label>
                                                    <p>{{ $invoice->created_at->format('Y-m-d H:i') ?? '' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><strong>ملاحظات الإرجاع:</strong></label>
                                                    <p>{{ $invoice->return_notes ?? '' }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-actions right">
                                            <a href="{{ route('dashboard.selling_invoices_return.index') }}"
                                                class="btn btn-warning">
                                                <i class="la la-arrow-right"></i> عودة
                                            </a>
                                        </div>
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
