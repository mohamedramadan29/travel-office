@extends('admin.layouts.app')
@section('title', 'كشف حساب العميل')
@section('content')
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="mb-2 content-header-left col-md-6 col-12 breadcrumb-new">
                    <h3 class="mb-0 content-header-title d-inline-block">كشف حساب العميل</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.welcome') }}">الرئيسية</a></li>
                                <li class="breadcrumb-item active">إدارة العملاء</li>
                                <li class="breadcrumb-item active">كشف حساب العميل</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="content-header-right col-md-6 col-12"></div>
            </div>
            <div class="content-body">
                <!-- نموذج البحث -->
                <div class="">
                    <form method="GET" action="{{ route('dashboard.clients.transactions', $client->id) }}">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="from_date">من تاريخ</label>
                                <input type="date" name="from_date" id="from_date" class="form-control" value="{{ $fromDate ?? '' }}">
                            </div>
                            <div class="col-md-4">
                                <label for="to_date">إلى تاريخ</label>
                                <input type="date" name="to_date" id="to_date" class="form-control" value="{{ $toDate ?? '' }}">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary">فلترة <i class="bi bi-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
                <hr>

                @if (!$fromDate || !$toDate)
                <!-- ملخص الحساب -->
                @if ($transactions->count() > 0 || $invoices->count() > 0)
                    <div class="summary d-flex justify-content-between">
                        <p style="padding: 10px;border: 1px solid #2a3b4b;border-radius: 10px;background: #2a3b4b;color: #fff;">
                            <strong>إجمالي قيم الفواتير:</strong> {{ number_format($total_invoices, 2) }} د.ل
                        </p>
                        <p style="padding: 10px;border: 1px solid #29d094;border-radius: 10px;background: #29d094;color: #fff;">
                            <strong>إجمالي المدفوع (Debit):</strong> {{ number_format($total_credit, 2) }} د.ل
                        </p>
                        <p style="padding: 10px;border: 1px solid #FE4961;border-radius: 10px;background: #FE4961;color: #fff;">
                            <strong>الرصيد المستحق من العميل:</strong> {{ number_format($balance, 2) }} د.ل
                        </p>
                    </div>
                @endif
                <hr>
                @endif

                @if (!$fromDate || !$toDate)
                <!-- جدول المعاملات -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الرقم المرجعي</th>
                                <th>دائن (مدفوع)</th>
                                <th>مدين (مستحق)</th>
                                <th>طريقة الدفع</th>
                                <th>الوصف</th>
                                <th>التاريخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transactions as $transaction)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $transaction->saleInvoice->referance_number ?? 'غير مرتبط' }}</td>
                                    <td>
                                        <span class="text-success">
                                            <strong>
                                                @if ($transaction->type == 'credit')
                                                    {{ number_format($transaction->amount, 2) }}
                                                @else
                                                    -
                                                @endif
                                            </strong>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-danger">
                                            <strong>
                                                @if ($transaction->type == 'debit')
                                                    {{ number_format($transaction->amount, 2) }}
                                                @else
                                                    -
                                                @endif
                                            </strong>
                                        </span>
                                    </td>
                                    <td>{{ $transaction->payment_method ?? '-' }}</td>
                                    <td>{{ $transaction->description ?? 'غير محدد' }}</td>
                                    <td>{{ $transaction->created_at->format('Y-m-d') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">لا يوجد معاملات</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @endif

                <!-- جدول كشف الحساب -->
                @if($fromDate && $toDate)
                    <h5 style="text-align: center; font-weight: bold; margin-bottom: 20px">
                        كشف حساب من تاريخ {{ $fromDate }} إلى تاريخ {{ $toDate }}
                    </h5>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>القيمة الافتتاحية</th>
                                <th>{{ number_format($opening_balance, 2) }}</th>
                                <th>المدة السابقة</th>
                            </tr>
                            <tr>
                                <th>رقم الفاتورة</th>
                                <th>قيمة الفاتورة</th>
                                <th>تاريخ الفاتورة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($invoices as $invoice)
                                <tr>
                                    <td>{{ $invoice->referance_number ?? 'غير محدد' }}</td>
                                    <td>{{ number_format($invoice->total_price, 2) }}</td>
                                    <td>{{ $invoice->created_at->format('Y-m-d') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">لا يوجد فواتير في الفترة المحددة</td>
                                </tr>
                            @endforelse
                        </tbody>
                        {{-- <tfoot>
                            <tr>
                                <td colspan="2"><strong>إجمالي الفواتير</strong></td>
                                <td><strong>{{ number_format($total_invoices, 2) }}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="2"><strong>الرصيد المستحق</strong></td>
                                <td><strong>{{ number_format($balance, 2) }}</strong></td>
                            </tr>
                        </tfoot> --}}
                    </table>
                @else
                    <div class="alert alert-info">
                        يرجى تحديد تاريخ البداية والنهاية لعرض كشف الحساب.
                    </div>
                @endif

                <hr>
            </div>
        </div>
    </div>
@endsection
