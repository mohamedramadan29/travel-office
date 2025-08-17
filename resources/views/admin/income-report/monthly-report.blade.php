@extends('admin.layouts.app')
@section('title', 'تقارير عن قائمة الدخل شهريا')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.min.css">
    <style>
        .dt-layout-row {
            display: flex;
            justify-content: space-between;
        }
        #printButton {
            margin-bottom: 20px;
        }
    </style>
@endsection
@section('content')
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="mb-2 content-header-left col-md-6 col-12 breadcrumb-new">
                    <h3 class="mb-0 content-header-title d-inline-block">تقارير عن قائمة الدخل عن شهر {{ $month }}</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.welcome') }}">الرئيسية</a></li>
                                <li class="breadcrumb-item active">تقارير عن قائمة الدخل</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="content-header-right col-md-6 col-12">
                    <button id="printButton" class="btn btn-warning float-end">طباعة كـ PDF</button>
                </div>
            </div>
            <div class="content-body">
                <!-- Bordered striped start -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-content collapse show">
                                <div class="card-body" id="reportContent">
                                    <h5 class="mt-4 card-title badge badge-info">  {{ $month }}</h5>

                                    <div>
                                        <table class="table table-striped table-bordered">
                                            <tbody>
                                                <!-- الإيرادات -->
                                                <tr>
                                                    <th style="font-size:18px; font-weight:bold">الإيرادات</th>
                                                    <td style="font-size:16px; font-weight:bold">{{ number_format($saleInvoicesTotal, 2) }} دينار</td>
                                                </tr>
                                                @forelse ($salesInvoicesByCategory as $categoryData)
                                                    <tr>
                                                        <th>{{ $categoryData['category']->name }}</th>
                                                        <td>{{ number_format($categoryData['total'], 2) }} دينار</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="2" class="text-center">لا توجد مبيعات حسب الفئات</td>
                                                    </tr>
                                                @endforelse

                                                <!-- المشتريات -->
                                                <tr>
                                                    <th style="font-size:18px; font-weight:bold">المشتريات</th>
                                                    <td style="font-size:16px; font-weight:bold">{{ number_format($purchesInvoicesTotal, 2) }} دينار</td>
                                                </tr>
                                                @forelse ($purchasesInvoicesByCategory as $categoryData)
                                                    <tr>
                                                        <th>{{ $categoryData['category']->name }}</th>
                                                        <td>{{ number_format($categoryData['total'], 2) }} دينار</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="2" class="text-center">لا توجد مشتريات حسب الفئات</td>
                                                    </tr>
                                                @endforelse

                                                <!-- المصروفات -->
                                                <tr>
                                                    <th style="font-size:18px; font-weight:bold">المصروفات</th>
                                                    <td style="font-size:16px; font-weight:bold">{{ number_format($expensesTotal, 2) }} دينار</td>
                                                </tr>
                                                @forelse ($expensesByCategory as $categoryData)
                                                    <tr>
                                                        <th>{{ $categoryData['category']->name }}</th>
                                                        <td>{{ number_format($categoryData['total'], 2) }} دينار</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="2" class="text-center">لا توجد مصروفات حسب الفئات</td>
                                                    </tr>
                                                @endforelse

                                                <!-- صافي الأرباح والخسائر -->
                                                <tr>
                                                    <th style="font-size:18px; font-weight:bold">صافي الأرباح والخسائر</th>
                                                    <td style="font-size:16px; font-weight:bold">
                                                        @if ($totalIncome >= 0)
                                                            <span class="text-success">{{ number_format($totalIncome, 2) }}</span>
                                                        @else
                                                            <span class="text-danger">{{ number_format($totalIncome, 2) }}</span>
                                                        @endif
                                                        دينار
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <!-- تفاصيل فواتير المبيعات -->
                                        <h6>تفاصيل فواتير المبيعات</h6>
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>التاريخ</th>
                                                    <th>الفئة</th>
                                                    <th>المبلغ</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($saleInvoices as $invoice)
                                                    <tr>
                                                        <td>{{ $invoice->created_at }}</td>
                                                        <td>{{ $invoice->category->name ?? 'غير محدد' }}</td>
                                                        <td>{{ number_format($invoice->total_price, 2) }} دينار</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="text-center">لا توجد فواتير مبيعات</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>

                                        <!-- تفاصيل فواتير المشتريات -->
                                        <h6>تفاصيل فواتير المشتريات</h6>
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>التاريخ</th>
                                                    <th>الفئة</th>
                                                    <th>المبلغ</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($purchesInvoices as $invoice)
                                                    <tr>
                                                        <td>{{ $invoice->created_at }}</td>
                                                        <td>{{ $invoice->category->name ?? 'غير محدد' }}</td>
                                                        <td>{{ number_format($invoice->total_price, 2) }} دينار</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="text-center">لا توجد فواتير مشتريات</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>

                                        <!-- تفاصيل المصروفات -->
                                        <h6>تفاصيل المصروفات</h6>
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>التاريخ</th>
                                                    <th>الفئة</th>
                                                    <th>الوصف</th>
                                                    <th>المبلغ</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($expenses as $expense)
                                                    <tr>
                                                        <td>{{ $expense->created_at }}</td>
                                                        <td>{{ $expense->category->name ?? 'غير محدد' }}</td>
                                                        <td>{{ $expense->description ?? 'غير محدد' }}</td>
                                                        <td>{{ number_format($expense->price, 2) }} دينار</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center">لا توجد مصروفات</td>
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
                <!-- Bordered striped end -->
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
        document.getElementById('printButton').addEventListener('click', function () {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({
                orientation: 'portrait',
                unit: 'mm',
                format: 'a4'
            });

            const element = document.getElementById('reportContent');
            html2canvas(element, { scale: 2 }).then(canvas => {
                const imgData = canvas.toDataURL('image/png');
                const imgProps = doc.getImageProperties(imgData);
                const pdfWidth = doc.internal.pageSize.getWidth();
                const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

                doc.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
                doc.save(`تقرير_الدخل_الشهري_${'{{ $month }}'}.pdf`);
            });
        });
    </script>
@endsection
