@extends('admin.layouts.app')
@section('title', 'كشف حساب العميل ')
@section('content')
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="mb-2 content-header-left col-md-6 col-12 breadcrumb-new">
                    <h3 class="mb-0 content-header-title d-inline-block"> كشف حساب العميل </h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.welcome') }}">الرئيسية </a>
                                </li>
                                <li class="breadcrumb-item active"> ادارة العملاء
                                </li>
                                <li class="breadcrumb-item active"> كشف حساب العميل
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="content-header-right col-md-6 col-12">

                </div>
            </div>
            <div class="content-body">
                <!-- Bordered striped start -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <button style="margin-right: 3px" type="button" class="btn btn-primary btn-sm"
                                    data-toggle="modal" data-target="#addtransaction{{ $client->id }}">
                                    <i class="bi bi-plus"></i> اضافة دفعة من العميل
                                </button>
                                @include('admin.clients._add_transaction', ['client' => $client])
                            </div>
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    @if ($transactions->count() > 0)
                                        <div class="summary d-flex justify-content-between">
                                            <p
                                                style="padding: 10px;border: 1px solid #2a3b4b;border-radius: 10px;background: #2a3b4b;color: #fff;">
                                                <strong>إجمالي قيم الفواتير:</strong> {{ $total_invoices }} د.ل
                                            </p>
                                            <p
                                                style="padding: 10px;border: 1px solid #29d094;border-radius: 10px;background: #29d094;color: #fff;">
                                                <strong>إجمالي المدفوع (Debit):</strong> {{ $total_credit }} د.ل
                                            </p>
                                            <p
                                                style="padding: 10px;border: 1px solid #FE4961;border-radius: 10px;background: #FE4961;color: #fff;">
                                                <strong>الرصيد المستحق من العميل:</strong> {{ $balance }} د.ل
                                            </p>
                                        </div>
                                    @endif
                                    <hr>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th> # </th>
                                                    <th> الرقم المرجعي </th>
                                                    <th>النوع</th>
                                                    <th>المبلغ (د.ل)</th>
                                                    <th>طريقة الدفع</th>
                                                    <th>الوصف</th>
                                                    <th>التاريخ</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($transactions as $transaction)
                                                    <tr>
                                                        <td> {{ $loop->iteration }} </td>
                                                        <td>{{ $transaction->saleInvoice->referance_number ?? 'غير مرتبط' }}
                                                        </td>
                                                        <td>
                                                            @if ($transaction->type == 'debit')
                                                                <span class="badge badge-pill badge-danger">مدين (مستحق من
                                                                    العميل)</span>
                                                            @else
                                                                <span class="badge badge-pill badge-success">دائن
                                                                    (مدفوع)
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $transaction->amount }}</td>
                                                        <td>{{ $transaction->payment_method ?? '-' }}</td>
                                                        <td>{{ $transaction->description }}</td>
                                                        <td>{{ $transaction->created_at->format('Y-m-d') }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" class="text-center">لا يوجد معاملات</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                        <hr>

                                        {{-- {{ $transactions->links() }} --}}
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
