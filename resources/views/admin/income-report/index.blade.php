@extends('admin.layouts.app')
@section('title', ' تقارير عن قائمة الدخل ')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.min.css">
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.2/css/buttons.dataTables.min.css"> --}}

    <style>
        .dt-layout-row {
            display: flex;
            justify-content: space-between
        }
    </style>

@endsection
@section('content')
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="mb-2 content-header-left col-md-6 col-12 breadcrumb-new">
                    <h3 class="mb-0 content-header-title d-inline-block"> تقارير عن قائمة الدخل </h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.welcome') }}">الرئيسية </a>
                                </li>
                                <li class="breadcrumb-item active"> تقارير عن قائمة الدخل
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Bordered striped start -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <!-- التقرير الكلي -->
                                                    <h5 class="card-title">تقرير قائمة الدخل الكلي</h5>
                                                    <div>
                                                        <table class="table table-striped table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>اجمالي المصروفات</th>
                                                                    <th>اجمالي المشتريات</th>
                                                                    <th>اجمالي المبيعات</th>
                                                                    <th>اجمالي الدخل</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td> <strong> {{ number_format($expensesTotal + $mothlySalaryTotal, 2) }}
                                                                        </strong> دينار</td>
                                                                    <td> <strong>
                                                                            {{ number_format($purchesInvoicesTotal, 2) }}
                                                                        </strong> دينار</td>
                                                                    <td> <strong> {{ number_format($saleInvoicesTotal, 2) }}
                                                                        </strong> دينار</td>
                                                                    <td> <strong>
                                                                            @if ($totalIncome >= 0)
                                                                                <span class="text-success">
                                                                                    {{ number_format($totalIncome, 2) }}
                                                                                </span>
                                                                            @else
                                                                                <span class="text-danger">
                                                                                    {{ number_format($totalIncome, 2) }}
                                                                                </span>
                                                                            @endif
                                                                        </strong> دينار</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    <!-- التقرير الشهري -->
                                                    <h5 class="mt-4 card-title">تقرير قائمة الدخل الشهرية</h5>
                                                    <div>
                                                        <table class="table table-striped table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>الشهر</th>
                                                                    <th>اجمالي المصروفات</th>
                                                                    <th>اجمالي المشتريات</th>
                                                                    <th>اجمالي المبيعات</th>
                                                                    <th>اجمالي الدخل</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @forelse ($monthlyReport as $report)
                                                                    <tr>
                                                                        <td>
                                                                            <strong>
                                                                                <a
                                                                                    href="{{ route('dashboard.reports.income_report.monthly', $report->month) }}">
                                                                                    {{ $report->month }}
                                                                                </a>
                                                                            </strong>
                                                                        </td>
                                                                        <td> <strong>
                                                                                {{ number_format($report->expenses_total + $report->salary_total, 2) }}
                                                                            </strong> دينار</td>
                                                                        <td> <strong>
                                                                                {{ number_format($report->purchases_total, 2) }}
                                                                            </strong> دينار</td>
                                                                        <td> <strong>
                                                                                {{ number_format($report->sales_total, 2) }}
                                                                            </strong> دينار</td>
                                                                        <td> <strong>
                                                                                @if ($report->income >= 0)
                                                                                    <span class="text-success">
                                                                                        {{ number_format($report->income, 2) }}
                                                                                    </span>
                                                                                @else
                                                                                    <span class="text-danger">
                                                                                        {{ number_format($report->income, 2) }}
                                                                                    </span>
                                                                                @endif
                                                                            </strong> دينار</td>
                                                                    </tr>
                                                                @empty
                                                                    <tr>
                                                                        <td colspan="5" class="text-center">لا توجد
                                                                            بيانات شهرية</td>
                                                                    </tr>
                                                                @endforelse
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Bordered striped end -->
            </div>
        </div>
    </div>
@endsection
