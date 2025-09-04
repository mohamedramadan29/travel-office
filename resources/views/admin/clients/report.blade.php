
@extends('admin.layouts.app')
@section('title', 'كشف شامل للعملاء')
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
        .table-responsive {
            margin-top: 20px;
        }
    </style>
@endsection
@section('content')
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="mb-2 content-header-left col-md-6 col-12 breadcrumb-new">
                    <h3 class="mb-0 content-header-title d-inline-block">كشف شامل للعملاء</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.welcome') }}">الرئيسية</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.clients.index') }}">العملاء</a></li>
                                <li class="breadcrumb-item active">كشف شامل للعملاء</li>
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
                                <div class="card-body">
                                    <div id="reportContent" class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>الاسم</th>
                                                    <th>الرصيد</th>
                                                    <th>دائن / مدين</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($clients as $client)
                                                    <tr>
                                                        <th scope="row">{{ $loop->iteration }}</th>
                                                        <td>{{ $client->name }}</td>
                                                        <td>{{ number_format($client->balance(), 2) }} د.ل</td>
                                                        <td>{{ $client->balance() > 0 ? 'مدين' : ($client->balance() < 0 ? 'دائن' : '') }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center">لا يوجد بيانات</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                        {{ $clients->links() }}
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
                doc.save('كشف_شامل_للعملاء.pdf');
            });
        });
    </script>
@endsection
