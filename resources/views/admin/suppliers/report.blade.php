@extends('admin.layouts.app')
@section('title', 'كشف شامل للموردين')
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .filter-card {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            padding: 20px;
            margin-bottom: 25px;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .filter-title {
            color: #2c3e50;
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .action-buttons .btn {
            padding: 10px 20px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .action-buttons .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .select2-container .select2-selection--multiple {
            min-height: 45px;
            border-color: #ced4da;
        }
    </style>
@endsection
@section('content')
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="mb-2 content-header-left col-md-6 col-12 breadcrumb-new">
                    <h3 class="mb-0 content-header-title d-inline-block">التقارير</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.welcome') }}">الرئيسية</a></li>
                                <li class="breadcrumb-item active">كشف شامل للموردين</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">خيارات التصدير</h4>
                        <div class="heading-elements">
                             <!-- Action Buttons -->
                             <div class="action-buttons text-center mt-4">
                                <a href="{{ route('dashboard.suppliers.pdf') }}" class="btn btn-danger" id="exportPdfBtn" target="_blank">
                                    <i class="la la-file-pdf-o"></i> تصدير PDF
                                </a>
                                <a href="{{ route('dashboard.suppliers.excel') }}" class="btn btn-success" id="exportExcelBtn">
                                    <i class="la la-file-excel-o"></i> تصدير Excel
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="card-body">

                            <!-- Suppliers Table -->
                            <div class="table-responsive mt-4">
                                <table class="table table-bordered table-striped">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="selectAll">
                                                    <label class="custom-control-label" for="selectAll"></label>
                                                </div>
                                            </th>
                                            <th>#</th>
                                            <th>الاسم</th>
                                            <th>رقم الهاتف</th>
                                            <th>الرصيد</th>
                                            <th>دائن / مدين</th>
                                            <th>الحالة</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($suppliers as $supplier)
                                            <tr>
                                                <td>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input client-checkbox" id="client_{{ $supplier->id }}" value="{{ $supplier->id }}">
                                                        <label class="custom-control-label" for="client_{{ $supplier->id }}"></label>
                                                    </div>
                                                </td>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $supplier->name }}</td>
                                                <td>{{ $supplier->mobile }}</td>
                                                <td>{{ number_format($supplier->balance(), 2) }}</td>
                                                <td>{{ $supplier->balance() > 0 ? 'دائن' : ($supplier->balance() < 0 ? 'مدين' : '') }}</td>
                                                 <td> <span
                                                    class="badge badge-pill badge-{{ $supplier->status == 'نشط' ? 'success' : 'danger' }}">{{ $supplier->status }}</span>
                                            </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "اختر الموردين",
                allowClear: true,
                dir: "rtl"
            });
        });

        // Select All Checkbox
        $('#selectAll').click(function() {
            $('.client-checkbox').prop('checked', this.checked);
            updateExportLinks();
        });

        // Individual Checkbox Click
        $('.client-checkbox').click(function() {
            if ($('.client-checkbox:checked').length == $('.client-checkbox').length) {
                $('#selectAll').prop('checked', true);
            } else {
                $('#selectAll').prop('checked', false);
            }
            updateExportLinks();
        });

        // Update Export Links with selected suppliers
        function updateExportLinks() {
            var selectedSuppliers = [];
            $('.client-checkbox:checked').each(function() {
                selectedSuppliers.push($(this).val());
            });

            var pdfLink = "{{ route('dashboard.suppliers.pdf') }}";
            var excelLink = "{{ route('dashboard.suppliers.excel') }}";

            if (selectedSuppliers.length > 0) {
                var params = 'supplier_ids=' + selectedSuppliers.join(',');
                $('#exportPdfBtn').attr('href', pdfLink + '?' + params);
                $('#exportExcelBtn').attr('href', excelLink + '?' + params);
            } else {
                $('#exportPdfBtn').attr('href', pdfLink);
                $('#exportExcelBtn').attr('href', excelLink);
            }
        }
    </script>
@endsection
