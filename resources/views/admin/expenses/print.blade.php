@extends('admin.layouts.app')
@section('title', ' طباعة مصروف ')
@section('content')
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="mb-2 content-header-left col-md-6 col-12 breadcrumb-new">
                    <h3 class="mb-0 content-header-title d-inline-block"> طباعة مصروف </h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.welcome') }}">الرئيسية</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.expenses.index') }}">
                                        المصروفات </a>
                                </li>
                                <li class="breadcrumb-item active"> طباعة مصروف
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <input type="hidden" value="{{ $expense->category->name ?? 'غير محدد' }}" id="customername">
                <section class="card">
                    <div id="invoice-template" class="card-body">
                        <!-- Invoice Company Details -->
                        <div id="invoice-company-details d-flex" class="row">
                            <div class="text-left col-12">
                                <div class="media-new" style="text-align: center">
                                    <img style="width: 120px;margin:auto" src="{{ asset('uploads/settings/logo.png') }}"
                                        alt="{{ $setting->site_name }}">
                                    <h5 style="margin-top: 20px;font-weight: bold;"> طباعة مصروف </h5>
                                </div>
                            </div>
                        </div>


                        <div id="invoice-items-details" class="pt-2">
                            <div class="row">
                                <div class="table-responsive col-sm-12">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> التصنيف </th>
                                                <th> المبلغ </th>
                                                <th> الخزينة </th>
                                                <th> تاريخ الانشاء </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ $expense->category->name ?? 'غير محدد' }}</td>
                                                <td>{{ $expense->price }} دينار </td>
                                                <td>
                                                    {{ $expense->safe->name }}
                                                </td>
                                                <td>{{ $expense->created_at }}</td>

                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="row">
                                <div class="text-center col-md-7 col-sm-12 text-md-left">
                                    <p class="lead"> للاستفسارات :</p>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <table class="table table-borderless table-sm">
                                                <tbody>
                                                    <tr>
                                                        <td> رقم الهاتف :</td>
                                                        <td class="text-right"> {{ $setting->site_phone }} </td>
                                                    </tr>
                                                    <tr>
                                                        <td> العنوان :</td>
                                                        <td class="text-right"> {{ $setting->site_address }} </td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- Invoice Footer -->
                        <div id="invoice-footer">
                            <div class="row">
                                <div class="text-center col-md-5 col-sm-12">
                                    <button onclick="setPrintTitle(); window.print();" type="button"
                                        class="my-1 btn btn-info btn-lg print_button"><i class="la la-paper-plane-o"></i>
                                        طباعة </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

@endsection


<style>
    @media print {
        footer {
            display: none;
        }

        .header-navbar .navbar-wrapper,
        body.vertical-layout.vertical-menu.menu-expanded .main-menu,
        .content-wrapper .content-header {
            display: none;
            width: 0
        }

        body.vertical-layout.vertical-menu.menu-expanded .content {
            margin-right: 0 !important;
        }

        @page {
            margin: 0;
            padding: 0;
            background-color: #fff
        }

        html body .content .content-wrapper {
            background-color: #fff;
        }

        .print_button {
            display: none !important;
        }
    }
</style>

<script>
    function setPrintTitle() {
        // تعيين عنوان مخصص للصفحة ليتم طباعته
        document.title = document.getElementById('customername').value;

        // التأكد من أن العنوان الجديد قد تم تعيينه بشكل صحيح
        console.log("تم تعيين عنوان مخصص للطباعة: " + document.title);

        // إضافة استماع لحدث اكتمال الطباعة لاستعادة العنوان الأصلي بعد الطباعة
        window.onafterprint = function() {
            document.title = document.getElementById('customername').value;
            console.log("استعادة عنوان الصفحة الأصلي: " + document.title);
        };
    }
</script>
