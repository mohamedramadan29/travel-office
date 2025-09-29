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
                                <a href="{{ route('dashboard.suppliers.create') }}" class="btn btn-primary btn-sm"> اضافة
                                    مورد </a>
                                <a style="margin:5px" target="_blank" class="btn btn-info btn-sm"
                                    href="{{ route('dashboard.suppliers.pdf') }}">
                                    استخراج ملف Pdf </a>
                                <a style="margin:5px" target="_blank" class="btn btn-warning btn-sm"
                                    href="{{ route('dashboard.suppliers.excel') }}"> استخراج
                                    ملف Excel </a>
                            </div>
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="datatable" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th> الاسم </th>
                                                    <th> رقم الهاتف </th>
                                                    <th> رقم التيلغرام </th>
                                                    <th> رقم الواتساب </th>
                                                    <th> الحالة </th>
                                                    <th> تاريخ الانشاء </th>
                                                    <th> العمليات </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($suppliers as $supplier)
                                                    <tr>
                                                        <th scope="row">{{ $loop->iteration }}</th>
                                                        <td> {{ $supplier->name }} </td>
                                                        <td> {{ $supplier->mobile }} </td>
                                                        <td> {{ $supplier->telegram }} </td>
                                                        <td> {{ $supplier->whatsapp }} </td>
                                                        <td> <span
                                                                class="badge badge-pill badge-{{ $supplier->status == 'نشط' ? 'success' : 'danger' }}">{{ $supplier->status }}</span>
                                                        </td>
                                                        <td> {{ $supplier->created_at->format('Y-m-d') }} </td>
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
                                                    <td colspan="4"> لا يوجد بيانات </td>
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


        });
    </script>
@endsection
