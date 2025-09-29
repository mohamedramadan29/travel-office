@extends('admin.layouts.app')
@section('title', ' فواتير الارجاع للشراء ')
@section('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">
@endsection
@section('content')
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="mb-2 content-header-left col-md-6 col-12 breadcrumb-new">
                    <h3 class="mb-0 content-header-title d-inline-block"> فواتير الارجاع للشراء </h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.welcome') }}">الرئيسية </a>
                                </li>
                                <li class="breadcrumb-item"><a
                                        href="{{ route('dashboard.purches_invoices_return.index') }}"> فواتير الارجاع للشراء
                                    </a>
                                </li>
                                <li class="breadcrumb-item active"> فواتير الارجاع
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
                                <a href="{{ route('dashboard.purches_invoices_return.pdf') }}" class="btn btn-info btn-sm">
                                    استخراج ملف Pdf </a>
                                <a href="{{ route('dashboard.purches_invoices_return.excel') }}"
                                    class="btn btn-warning btn-sm"> استخراج ملف Excel </a>
                            </div>
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped" id="datatable">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th> نوع الفاتورة </th>
                                                    <th> البيان </th>
                                                    <th> الرقم المرجعي </th>
                                                    <th> المورد </th>
                                                    <th> التصنيف </th>
                                                    <th> الكمية </th>
                                                    <th> السعر الكلي </th>
                                                    <th> السعر الارجاع </th>
                                                    <th> تاريخ الانشاء </th>
                                                    <th> العمليات </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($invoices as $invoice)
                                                    <tr>
                                                        <th scope="row">{{ $loop->iteration }}</th>
                                                        <td>
                                                            <span class="badge badge-pill badge-warning">
                                                                فاتورة ارجاع </span>
                                                        </td>
                                                        <td> {{ $invoice->bayan_txt }} </td>
                                                        <td> {{ $invoice->referance_number }} </td>
                                                        <td> {{ $invoice->supplier->name }} </td>
                                                        <td> {{ $invoice->category->name ?? 'غير محدد' }} </td>
                                                        <td> {{ $invoice->qyt }} </td>
                                                        <td> {{ $invoice->total_price }} د.ل </td>
                                                        <td> {{ $invoice->return_price }} د.ل </td>
                                                        <td> {{ $invoice->created_at->format('Y-m-d H:i') }} </td>
                                                        <td>
                                                            <div class="dropdown float-md-left">
                                                                <button class="px-2 btn btn-primary dropdown-toggle"
                                                                    id="dropdownBreadcrumbButton" type="button"
                                                                    data-toggle="dropdown" aria-haspopup="true"
                                                                    aria-expanded="false"> العمليات </button>
                                                                <div class="dropdown-menu"
                                                                    aria-labelledby="dropdownBreadcrumbButton">
                                                                    @can('purches_invoices_return')
                                                                        {{-- <a class="dropdown-item"
                                                                            href="{{ route('dashboard.purches_invoices_return.edit', $invoice->id) }}"><i
                                                                                class="la la-edit"></i> تعديل
                                                                        </a> --}}
                                                                    @endcan
                                                                    <form
                                                                        action="{{ route('dashboard.purches_invoices_return.destroy', $invoice->id) }}"
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
                                        {{ $invoices->links() }}
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
