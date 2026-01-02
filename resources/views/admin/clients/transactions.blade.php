@extends('admin.layouts.app')
@section('title', 'كشف حساب العميل')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
    <style>
        .dt-layout-row {
            display: flex;
            justify-content: space-between;
        }

        #printButton {
            margin-bottom: 20px;
        }

        .summary {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .summary p {
            padding: 10px;
            border-radius: 10px;
            color: #fff;
        }

        .report-header {
            display: none;
            /* مخفي في عرض المتصفح */
            background: #f8f9fa;

            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
            font-family: 'Tajawal', sans-serif;
        }

        .report-header img {
            max-width: 180px;
            height: auto;
            margin-bottom: 15px;
        }

        .report-header h2 {
            font-size: 28px;
            font-weight: bold;
            color: #2a3b4b;
            margin: 0 0 10px 0;
        }

        .report-header .details {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
            font-size: 16px;
            color: #333;
        }

        .report-header .details p {
            margin: 5px 0;
        }

        .report-header .client-details {
            flex: 1;
            text-align: right;
            display: flex;
            justify-content: space-around;
        }

        .report-footer {
            display: none;
            /* مخفي في عرض المتصفح */
            background: #f8f9fa;

            padding: 15px;
            margin-top: 20px;
            text-align: center;
            font-family: 'Tajawal', sans-serif;
        }

        .report-footer .company-details {
            font-size: 16px;
            color: #333;
            display: flex;
            justify-content: space-around;
        }

        .report-footer .company-details p {
            margin: 5px 0;
        }
    </style>
@endsection
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
                <div class="content-header-right col-md-6 col-12">
                    <button id="printButton" class="btn btn-warning float-end">طباعة كـ PDF</button>
                </div>
            </div>
            <div class="content-body">
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
                                    <!-- نموذج البحث -->
                                    <div class="">
                                        <form method="GET"
                                            action="{{ route('dashboard.clients.transactions', $client->id) }}">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label for="from_date">من تاريخ</label>
                                                    <input type="date" name="from_date" id="from_date"
                                                        class="form-control" value="{{ $fromDate ?? '' }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="to_date">إلى تاريخ</label>
                                                    <input type="date" name="to_date" id="to_date" class="form-control"
                                                        value="{{ $toDate ?? '' }}">
                                                </div>
                                                <div class="col-md-4 d-flex align-items-end">
                                                    <button type="submit" class="btn btn-primary">فلترة <i
                                                            class="bi bi-search"></i></button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <hr>

                                    <div id="reportContent">
                                        <!-- رأس التقرير -->
                                        <div class="report-header">
                                            <img src="{{ asset('uploads/settings/logo.png') }}" alt="{{ $setting->site_name }}">
                                            <h2>{{ $setting->site_name }}</h2>
                                            <div class="details">
                                                <div class="client-details">
                                                    <p><strong>اسم العميل:</strong> {{ $client->name ?? 'غير محدد' }}</p>
                                                    <p><strong>رقم الهاتف:</strong> {{ $client->mobile ?? 'غير متوفر' }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        @if (!$fromDate || !$toDate)
                                            <!-- ملخص الحساب -->
                                            @if ($transactions->count() > 0 || $invoices->count() > 0)
                                                <div class="summary">
                                                    <p style="border: 1px solid #2a3b4b; background: #2a3b4b;">
                                                        <strong>اجمالي قيم الفواتير الكلي :</strong>
                                                        {{ number_format($total_invoices, 2) }}
                                                        د.ل
                                                    </p>
                                                    <p style="border: 1px solid #29d094; background: #29d094;">
                                                        <strong>إجمالي المدفوع (Debit):</strong>
                                                        {{ number_format($total_credit, 2) }}
                                                        د.ل
                                                    </p>
                                                    <p style="border: 1px solid #FE4961; background: #FE4961;">
                                                        <strong>الرصيد المستحق من العميل:</strong>
                                                        {{ number_format($client_balance, 2) }}
                                                        د.ل
                                                    </p>
                                                </div>
                                            @endif
                                            <hr>

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
                                                                <td>{{ $transaction->saleInvoice->referance_number ?? 'غير مرتبط' }}
                                                                </td>
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
                                                                <td>{{ $transaction->saleInvoice->bayan_txt ?? 'غير محدد' }}</td>
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
                                        @if ($fromDate && $toDate)
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
                                                            <td colspan="3" class="text-center">لا يوجد فواتير في الفترة
                                                                المحددة
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        @else
                                            @if ($transactions->count() > 0)
                                            @endif
                                        @endif

                                        <!-- تذييل التقرير -->
                                        <div class="report-footer">
                                            <div class="company-details">
                                                <p><strong>رقم الهاتف:</strong> {{ $setting->site_phone ?? 'غير متوفر' }}
                                                </p>
                                                <p><strong>العنوان:</strong> {{ $setting->site_address ?? 'غير متوفر' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <hr>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
        document.getElementById('printButton').addEventListener('click', function() {
            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF({
                orientation: 'portrait',
                unit: 'mm',
                format: 'a4'
            });

            const element = document.getElementById('reportContent');
            const header = document.querySelector('.report-header');
            const footer = document.querySelector('.report-footer');
            const logo = header.querySelector('img');

            // إظهار الهيدر والتذييل مؤقتًا لالتقاطهما بواسطة html2canvas
            header.style.display = 'block';
            footer.style.display = 'block';

            // التحقق من تحميل الشعار قبل إنشاء PDF
            const loadImage = (src) => {
                return new Promise((resolve, reject) => {
                    const img = new Image();
                    img.src = src;
                    img.crossOrigin = 'Anonymous'; // للتعامل مع CORS
                    img.onload = () => resolve(img);
                    img.onerror = () => reject(new Error('فشل تحميل الشعار'));
                });
            };

            // التقاط الصورة بعد تحميل الشعار
            loadImage(logo.src)
                .then(() => {
                    return html2canvas(element, {
                        scale: 2,
                        useCORS: true // السماح بتحميل الصور من مصادر خارجية
                    });
                })
                .then(canvas => {
                    const imgData = canvas.toDataURL('image/png');
                    const imgProps = doc.getImageProperties(imgData);
                    const pdfWidth = doc.internal.pageSize.getWidth();
                    const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

                    doc.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
                    const fromDate = '{{ $fromDate ?? '' }}';
                    const toDate = '{{ $toDate ?? '' }}';
                    const fileName = fromDate && toDate ?
                        `كشف_حساب_العميل_${fromDate}_إلى_${toDate}.pdf` :
                        `كشف_حساب_العميل.pdf`;
                    doc.save(fileName);
                })
                .catch(error => {
                    console.error('خطأ أثناء إنشاء PDF:', error);
                    alert('حدث خطأ أثناء إنشاء ملف PDF. تأكد من تحميل الشعار.');
                })
                .finally(() => {
                    // إعادة إخفاء الهيدر والتذييل بعد الطباعة
                    header.style.display = 'none';
                    footer.style.display = 'none';
                });
        });
    </script>
@endsection
