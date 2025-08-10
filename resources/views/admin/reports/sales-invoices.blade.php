@extends('admin.layouts.app')
@section('title', ' تقارير عن فواتير البيع ')
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
                    <h3 class="mb-0 content-header-title d-inline-block"> تقارير عن فواتير البيع </h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.welcome') }}">الرئيسية </a>
                                </li>
                                <li class="breadcrumb-item active"> تقارير عن فواتير البيع
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Bordered striped start -->
                <div class="mb-3 row">
                    <div class="col-12">
                        @if(isset($monthlySalesInvoices))
                        <form method="GET" action="{{ route('dashboard.reports.SalesInvoicesReport') }}">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="month">الشهر</label>
                                    <input type="month" name="month" id="month" class="form-control"
                                        value="{{ $month ?? '' }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="from_date">من تاريخ</label>
                                    <input type="date" name="from_date" id="from_date" class="form-control"
                                        value="{{ $fromDate ?? '' }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="to_date">إلى تاريخ</label>
                                    <input type="date" name="to_date" id="to_date" class="form-control"
                                        value="{{ $toDate ?? '' }}">
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary">فلترة</button>
                                </div>
                            </div>
                        </form>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    @if(isset($monthlySalesInvoices))
                                                    <h5 class="card-title"> تقرير فواتير البيع الشهرية </h5>
                                                    <div class="product-list" style="height: 300px;">
                                                        <canvas id="monthlySalesInvoicesChart"></canvas>
                                                    </div>
                                                    @endif
                                                    <div>
                                                        <h5> تقرير فواتير البيع </h5>
                                                        <table class="table table-striped table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th> # </th>
                                                                    <th> العميل  </th>
                                                                    <th> البيان </th>
                                                                    <th> الرقم المرجعي </th>
                                                                    <th> المورد </th>
                                                                    <th> التصنيف </th>
                                                                    <th> الكمية </th>
                                                                    <th> السعر الكلي </th>
                                                                    <th> تاريخ الانشاء </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @if ($allSalesInvoices->isEmpty())
                                                                    <tr>
                                                                        <td colspan="4" class="text-center">لا يوجد
                                                                            بيانات</td>
                                                                    </tr>
                                                                @else
                                                                    @php $total = 0; @endphp
                                                                    @foreach ($allSalesInvoices as $invoice)
                                                                        @php $total += $invoice['total_price']; @endphp
                                                                        <tr>
                                                                            <td> {{ $loop->iteration }} </td>
                                                                            <td>
                                                                                <a href="{{ route('dashboard.clients.transactions', $invoice->client->id) }}"> {{ $invoice->client->name }}</a>
                                                                            </td>
                                                                            <td> {{ $invoice['bayan_txt'] }} </td>
                                                                            <td> {{ $invoice['referance_number'] }} </td>
                                                                            <td> {{ $invoice->supplier->name }} </td>
                                                                            <td> {{ $invoice->category->name ?? 'غير محدد' }}
                                                                            </td>
                                                                            <td> {{ $invoice['qyt'] }} </td>
                                                                            <td> {{ number_format($invoice['total_price'], 2) }}
                                                                                دينار </td>
                                                                            <td> {{ $invoice['created_at'] }} </td>
                                                                        </tr>
                                                                    @endforeach
                                                                @endif
                                                            </tbody>
                                                            @if(isset($total))
                                                            <tfoot>
                                                                <tr>
                                                                    <td colspan="7" class="text-center">إجمالي فواتير
                                                                        الشراء</td>
                                                                    <td colspan="2"> <span
                                                                            class="badge badge-pill badge-danger">
                                                                            {{ number_format($total, 2) }} دينار</span>
                                                                    </td>
                                                                </tr>
                                                            </tfoot>
                                                            @endif
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


@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    @if(isset($monthlySalesInvoices))
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            /************** Report For Order Monthly  ********/
            const dataFromServer = @json($monthlySalesInvoices);
            const ctx = document.getElementById('monthlySalesInvoicesChart').getContext('2d');

            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: dataFromServer.labels,
                    datasets: [{
                        label: 'فواتير الشراء',
                        data: dataFromServer.data,
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(153, 102, 255, 0.6)',
                            'rgba(255, 159, 64, 0.6)',
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(75, 192, 192, 0.6)'
                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(75, 192, 192, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'فواتير الشراء'
                            },
                            ticks: {
                                stepSize: 1,
                                precision: 0
                            },
                            suggestedMin: 0,
                            suggestedMax: 10
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'الشهر'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'فواتير الشراء الشهرية (2025)'
                        },
                        tooltip: {
                            enabled: true
                        }
                    }
                }
            });
            console.log('Charts created');
        });
    </script>
    @endif
@endsection
