@extends('admin.layouts.app')
@section('title')
    تعديل فاتورة شراء
@endsection
@section('content')
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="mb-2 content-header-left col-md-6 col-12 breadcrumb-new">
                    <h3 class="mb-0 content-header-title d-inline-block">تعديل فاتورة شراء</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.welcome') }}">الرئيسية </a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.purches_invoices.index') }}">فواتير
                                        الشراء</a>
                                </li>
                                <li class="breadcrumb-item active"><a href="#">تعديل فاتورة شراء</a>
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
                                        <form class="form" action="{{ route('dashboard.purches_invoices.update',$invoice->id) }}"
                                            method="POST">

                                            @csrf
                                            @method('PUT')
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label> نوع الفاتورة </label>
                                                            <select class="form-control" name="type" id="invoiceType">
                                                                <optgroup label="نوع الفاتورة">
                                                                    <option
                                                                        @if ($invoice->type == 'فاتورة مؤقتة') selected @endif
                                                                        value="فاتورة مؤقتة"> فاتورة مؤقتة
                                                                    </option>
                                                                    @can('official_purches_invoices')
                                                                        <option
                                                                            @if ($invoice->type == 'فاتورة رسمية' || session('type') == 'official' ) selected @endif
                                                                            value="فاتورة رسمية"> فاتورة رسمية </option>
                                                                    @endcan
                                                                </optgroup>
                                                            </select>
                                                            <span id="temporaryInvoiceNote"> الفاتورة المؤقتة غير نهائية ولا
                                                                تؤثر على المخزون </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        const invoiceTypeSelect = document.getElementById('invoiceType');
                                                        const temporaryInvoiceNote = document.getElementById('temporaryInvoiceNote');

                                                        function toggleNoteVisibility() {
                                                            if (invoiceTypeSelect.value === 'فاتورة رسمية') {
                                                                temporaryInvoiceNote.style.display = 'none';
                                                            } else {
                                                                temporaryInvoiceNote.style.display = 'block';
                                                            }
                                                        }
                                                        // Run on page load
                                                        toggleNoteVisibility();
                                                        // Run on select change
                                                        invoiceTypeSelect.addEventListener('change', toggleNoteVisibility);
                                                    });
                                                </script>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="userinput1"> البيان / الوصف </label>
                                                            <input required type="text" id="userinput1"
                                                                class="form-control" name="bayan_txt"
                                                                value="{{ $invoice->bayan_txt }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="userinput1"> الرقم المرجعي </label>
                                                            <input required type="text" id="userinput1"
                                                                class="form-control" name="referance_number"
                                                                value="{{ $invoice->referance_number }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="category_id"> التصنيف  </label>
                                                            <select name="category_id" id="category_id" class="form-control">
                                                                <option value="">اختر التصنيف </option>
                                                                @foreach ($categories as $category)
                                                                    <option value="{{ $category->id }}" @if ($category->id == $invoice->category_id) selected @endif>{{ $category->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                @livewire('dashboard.purches-invoice-create', ['suppliers' => $suppliers, 'safes' => $safes,'invoice'=>$invoice])
                                            </div>
                                            <div class="form-actions right">
                                                <a href="{{ route('dashboard.purches_invoices.index') }}" type="button"
                                                    class="mr-1 btn btn-warning">
                                                    <i class="ft-x"></i> الغاء
                                                </a>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="la la-check-square-o"></i> تعديل الفاتورة
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
