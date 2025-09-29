@extends('admin.layouts.app')
@section('title', ' فواتير البيع ')
@section('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">
@endsection
@section('content')
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="mb-2 content-header-left col-md-6 col-12 breadcrumb-new">
                    <h3 class="mb-0 content-header-title d-inline-block"> فواتير البيع </h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.welcome') }}">الرئيسية </a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.selling_invoices.index') }}">فواتير
                                        البيع </a>
                                </li>
                                <li class="breadcrumb-item active"> فواتير البيع
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
                            @can('selling_invoices_create')
                                <div class="card-header">
                                    <a href="{{ route('dashboard.selling_invoices.create') }}" class="btn btn-primary"> اضافة
                                        فاتورة </a>
                                </div>
                            @endcan
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="datatable" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th> العميل </th>
                                                    <th> البيان </th>
                                                    <th> الرقم المرجعي </th>
                                                    <th> المورد </th>
                                                    <th> التصنيف </th>
                                                    <th> الكمية </th>
                                                    <th> السعر الكلي </th>
                                                    <th> تاريخ الانشاء </th>
                                                    <th> العمليات </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($invoices as $invoice)
                                                    <tr>
                                                        <th scope="row">{{ $loop->iteration }}</th>
                                                        <td>
                                                            {{ $invoice->client->name }}
                                                        </td>
                                                        <td> {{ $invoice->bayan_txt }} </td>
                                                        <td> {{ $invoice->referance_number }} </td>
                                                        <td> {{ $invoice->supplier->name }} </td>
                                                        <td> {{ $invoice->category->name ?? 'غير محدد' }} </td>
                                                        <td> {{ $invoice->qyt }} </td>
                                                        <td> {{ $invoice->total_price }} د.ل </td>
                                                        <td> {{ $invoice->created_at->format('Y-m-d H:i') }} </td>
                                                        <td>
                                                            <a class="btn btn-primary btn-sm"
                                                                href="{{ route('dashboard.selling_invoices_return.show', $invoice->id) }}"><i
                                                                    class="la la-eye"></i> مشاهدة الفاتورة
                                                            </a>
                                                            {{-- <div class="dropdown float-md-left">
                                                                <button class="px-2 btn btn-primary dropdown-toggle"
                                                                    id="dropdownBreadcrumbButton" type="button"
                                                                    data-toggle="dropdown" aria-haspopup="true"
                                                                    aria-expanded="false"> العمليات </button>
                                                                <div class="dropdown-menu"
                                                                    aria-labelledby="dropdownBreadcrumbButton">
                                                                    @can('selling_invoices_edit')
                                                                        <a class="dropdown-item"
                                                                            href="{{ route('dashboard.selling_invoices_return.show', $invoice->id) }}"><i
                                                                                class="la la-eye"></i> مشاهدة الفاتورة
                                                                        </a>
                                                                    @endcan
                                                                    {{-- @can('selling_invoices_return')
                                                                        @if ($invoice->return_status == 'not_returned')
                                                                            <a class="dropdown-item"
                                                                                href="{{ route('dashboard.selling_return_invoice', $invoice->id) }}"><i
                                                                                    class="la la-edit"></i> ارجاع
                                                                            </a>
                                                                        @endif
                                                                    @endcan
                                                                    @can('selling_invoices_edit')
                                                                        @if ($invoice->type == 'فاتورة مؤقتة')
                                                                            <a class="dropdown-item"
                                                                                href="{{ route('dashboard.convert_to_official', $invoice->id) }}"><i
                                                                                    class="la la-edit"></i> تحويل الي فاتورة
                                                                                رسمية
                                                                            </a>
                                                                        @endif
                                                                    @endcan
                                                                    @can('selling_invoices_delete')
                                                                        <form
                                                                            action="{{ route('dashboard.selling_invoices.destroy', $invoice->id) }}"
                                                                            method="post">
                                                                            @csrf
                                                                            @method('delete')
                                                                            <button type="submit"
                                                                                class="dropdown-item delete_confirm"><i
                                                                                    class="la la-trash"></i> حذف </button>
                                                                        </form>
                                                                    @endcan --}}

                                                            {{-- </div> --}}
                                                            {{-- </div> --}}
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
