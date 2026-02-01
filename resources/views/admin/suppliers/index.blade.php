@extends('admin.layouts.app')
@section('title', 'الموردين ')
@section('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">
@endsection
@section('content')
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="mb-2 content-header-left col-md-6 col-12 breadcrumb-new">
                    <h3 class="mb-0 content-header-title d-inline-block"> ادارة الموردين </h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.welcome') }}">الرئيسية </a>
                                </li>
                                <li class="breadcrumb-item active"> ادارة الموردين
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
                            <div class="card-header d-flex align-items-center">
                                <a href="{{ route('dashboard.suppliers.report') }}" class="btn btn-success btn-sm"> كشف شامل
                                    للعملاء </a>
                                <a href="{{ route('dashboard.suppliers.create') }}" class="btn btn-primary btn-sm"> اضافة
                                    مورد </a>
                                <a style="margin:5px" target="_blank" class="btn btn-info btn-sm"
                                    href="#" id="exportPdfBtn">
                                    استخراج ملف Pdf </a>
                                <a style="margin:5px" target="_blank" class="btn btn-warning btn-sm"
                                    href="#" id="exportExcelBtn"> استخراج
                                    ملف Excel </a>
                            </div>
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="datatable" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="selectAll">
                                                            <label class="custom-control-label" for="selectAll"></label>
                                                        </div>
                                                    </th>
                                                    <th>#</th>
                                                    <th> الاسم </th>
                                                    <th> رقم الهاتف </th>
                                                    <th> الرصيد </th>
                                                    <th> دائن / مدين </th>
                                                    <th> الحالة </th>
                                                    <th> العمليات </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($suppliers as $supplier)
                                                    <tr>
                                                         <td>
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input client-checkbox" id="client_{{ $supplier->id }}" value="{{ $supplier->id }}">
                                                                <label class="custom-control-label" for="client_{{ $supplier->id }}"></label>
                                                            </div>
                                                        </td>
                                                        <th scope="row">{{ $loop->iteration }}</th>
                                                        <td> <a href="{{ route('dashboard.suppliers.transactions', $supplier->id) }}">{{ $supplier->name }}</a> </td>
                                                        <td> {{ $supplier->mobile }} </td>
                                                        <td> {{ number_format($supplier->balance(), 2) }} </td>
                                                        <td> {{ $supplier->balance() > 0 ? 'دائن' : ($supplier->balance() < 0 ? 'مدين' : '') }} </td>
                                                        <td> <span
                                                                class="badge badge-pill badge-{{ $supplier->status == 'نشط' ? 'success' : 'danger' }}">{{ $supplier->status }}</span>
                                                        </td>
                                                        <td>
                                                            <div class="dropdown float-md-right">
                                                                <button class="px-2 btn btn-primary dropdown-toggle"
                                                                    id="dropdownBreadcrumbButton" type="button"
                                                                    data-toggle="dropdown" aria-haspopup="true"
                                                                    aria-expanded="false"> العمليات </button>
                                                                <div class="dropdown-menu"
                                                                    aria-labelledby="dropdownBreadcrumbButton"><a
                                                                        class="dropdown-item"
                                                                        href="{{ route('dashboard.suppliers.edit', $supplier->id) }}"><i
                                                                            class="la la-edit"></i> تعديل </a>
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('dashboard.suppliers.transactions', $supplier->id) }}"><i
                                                                            class="la la-edit"></i> كشف حساب المورد </a>
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('dashboard.suppliers.status', $supplier->id) }}"><i
                                                                            class="la la-edit"></i> تعديل الحالة </a>
                                                                    <form
                                                                        action="{{ route('dashboard.suppliers.destroy', $supplier->id) }}"
                                                                        method="post">
                                                                        @csrf
                                                                        @method('delete')
                                                                        <button type="submit"
                                                                            class="dropdown-item delete_confirm"><i
                                                                                class="la la-trash"></i> حذف </button>
                                                                    </form>

                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <td colspan="8"> لا يوجد بيانات </td>
                                                @endforelse
                                            </tbody>
                                        </table>
                                        {{ $suppliers->links() }}
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
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable({
                "language": {
                    "sSearch": "ابحث:",
                },
                "bLengthChange": false,
                "bInfo": false,
                "bPaginate": false,
                "ordering": false
            });

            // Select All Checkbox
            $('#selectAll').click(function() {
                $('.client-checkbox').prop('checked', this.checked);
                updateExportLinks();
            });

            // Individual Checkbox Click
            $(document).on('click', '.client-checkbox', function() {
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

             updateExportLinks();
        });
    </script>
@endsection
