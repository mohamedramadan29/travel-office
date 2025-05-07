@extends('admin.layouts.app')
@section('title', ' ادارة المنتجات ')
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
                    <h3 class="mb-0 content-header-title d-inline-block"> ادارة المنتجات </h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.welcome') }}">الرئيسية </a>
                                </li>
                                <li class="breadcrumb-item active"> ادارة المنتجات
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
                                <!-- Button trigger modal -->
                                <a href="{{ route('dashboard.products.create') }}" type="button" class="btn btn-primary">
                                    اضافة منتج
                                </a>
                            </div>
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    @include('admin.layouts.toaster_error')
                                    @include('admin.layouts.toaster_success')

                                    <div class="table-responsive">
                                        <table id="yajra_datatable"
                                            class="table table-striped table-bordered column-rendering">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th> اسم المنتج </th>
                                                    <th> منتج متغير </th>
                                                    <th> صور المنتج </th>
                                                    <th> الحالة </th>
                                                    <th> sku </th>
                                                    <th> متاح الي </th>
                                                    <th> القسم </th>
                                                    <th> العلامة التجارية </th>
                                                    <th> السعر </th>
                                                    <th> الكمية </th>
                                                    <th> العمليات </th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
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

    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>

    {{-- <script src="https://cdn.datatables.net/buttons/3.2.2/js/dataTables.buttons.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.html5.min.js"></script> --}}
    <!--------- Show Model If Have Error Validations -->


    @if ($errors->any())
        <script>
            $(document).ready(function() {
                $("#createcoupon").modal('show');
            });
        </script>
    @endif

    <script>
        var lang = "{{ app()->getLocale() }}";
        $(document).ready(function() {
            $('#yajra_datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('dashboard.products.all') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name',
                    },
                    {
                        data: 'has_variant',
                        name: 'has_variant',
                    },
                    {
                        data: 'images',
                        name: 'images',
                    },
                    {
                        data: 'status',
                        name: 'status',
                    },
                    {
                        data: 'sku',
                        name: 'sku',
                    },
                    {
                        data: 'available_for',
                        name: 'available_for',
                    },
                    {
                        data: 'category',
                        name: 'category',
                    },
                    {
                        data: 'brand',
                        name: 'brand',
                    },
                    {
                        data: 'price',
                        name: 'price',
                    },
                    {
                        data: 'qty',
                        name: 'qty',
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                // layout: {
                //     topStart: {
                //         buttons: [
                //              'copy'
                //         ]
                //     }
                // },
                language: lang === 'ar' ? {
                    url: '//cdn.datatables.net/plug-ins/2.2.2/i18n/ar.json',
                } : {},
            });
        });
    </script>
    <script>
        $(document).on('click', '.change_status', function(e) {
            e.preventDefault();
            var currentPage = $("#yajra_datatable").DataTable().page(); // Get the current page number
            var product_id = $(this).data('product-id');
            $.ajax({
                url: "{{ route('dashboard.product.status') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: product_id,
                },
                success: function(data) {
                    if (data.status == 'success') {
                        $("#yajra_datatable").DataTable().page(currentPage).draw(
                            false); // Draw the table with the current page
                        Swal.fire({
                            position: "center",
                            icon: "success",
                            title: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        Swal.fire({
                            position: "center",
                            icon: "error",
                            title: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                }
            });
        })
    </script>


@endsection
