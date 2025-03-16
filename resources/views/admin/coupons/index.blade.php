@extends('admin.layouts.app')
@section('title', ' ادارة الكوبونات ')
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
                    <h3 class="mb-0 content-header-title d-inline-block"> ادارة الكوبونات </h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.welcome') }}">الرئيسية </a>
                                </li>
                                <li class="breadcrumb-item active"> ادارة الكوبونات
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
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#createcoupon">
                                    اضافة كوبون
                                </button>

                                @include('admin.coupons._create')
                                @include('admin.coupons._edit')
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
                                                    <th> الكود </th>
                                                    <th> النسبة المخفضة </th>
                                                    <th> البدء </th>
                                                    <th> الانتهاء </th>
                                                    <th> الحالة </th>
                                                    <th> الحد </th>
                                                    <th> المستخدم </th>
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
                ajax: "{{ route('dashboard.coupons.all') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'code',
                        name: 'code',
                    },
                    {
                        data: 'discount_percentage',
                        name: 'discount_percentage',
                    },
                    {
                        data: 'start_date',
                        name: 'start_date',
                    },
                    {
                        data: 'end_date',
                        name: 'end_date',
                    },
                    {
                        data: 'is_active',
                        name: 'is_active',
                    },
                    {
                        data: 'limit',
                        name: 'limit',
                    },
                    {
                        data: 'is_used',
                        name: 'is_used',
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
        $(document).ready(function() {
            $("#createcoupon_form").submit(function(e) {
                e.preventDefault();
                var currentPage = $("#yajra_datatable").DataTable().page(); // Get the current page number
                $.ajax({
                    url: "{{ route('dashboard.coupons.store') }}",
                    type: "POST",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        if (data.status == 'success') {
                            $("#createcoupon_form")[0].reset();
                            $("#yajra_datatable").DataTable().page(currentPage).draw(false); // Draw the table with the current page
                            $("#createcoupon").modal('hide');
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
                    },
                    error: function(data) {
                        if (data.responseJSON.errors) {
                            $.each(data.responseJSON.errors, function(key, value) {
                                $("#" + key + "_error").html(value[0]);
                                $("#error_list").append('<li>' + value[0] + '</li>');
                                $("#error_div").show();
                            });
                        }
                    }
                });
            });
        });
    </script>
    <script>
        $(document).on('click', '.edit_coupon', function(e) {
            e.preventDefault();

            var coupon_id = $(this).attr('data-coupon-id');
            var coupon_code = $(this).attr('data-coupon-code');
            var coupon_discount_percentage = $(this).attr('data-coupon-discount-percentage');
            var coupon_start_date = $(this).attr('data-coupon-start-date');
            var coupon_end_date = $(this).attr('data-coupon-end-date');
            var coupon_limit = $(this).attr('data-coupon-limit');
            var coupon_time_used = $(this).attr('data-coupon-time-used');
            var coupon_is_active = $(this).attr('data-coupon-is-active');

            $('#coupon_id').val(coupon_id);
            $('#coupon_code').val(coupon_code);
            $('#coupon_discount_percentage').val(coupon_discount_percentage);
            $('#coupon_start_date').val(coupon_start_date);
            $('#coupon_end_date').val(coupon_end_date);
            $('#coupon_limit').val(coupon_limit);
            $('#coupon_is_active').val(coupon_is_active);
            $('#coupon_is_active_1').prop('selected', coupon_is_active == 1);
            $('#coupon_is_active_0').prop('selected', coupon_is_active == 0);
            $('#edit_coupon').modal('show');
        });
    </script>
    <!-- Edit Coupon -->
    <script>
        $(document).on('submit', '#edit_coupon_form', function(e) {
            e.preventDefault();
            var currentPage = $("#yajra_datatable").DataTable().page(); // Get the current page number
            var coupon_id = $('#coupon_id').val();
            $.ajax({
                url: "{{ route('dashboard.coupons.update', ':coupon_id') }}".replace(':coupon_id', coupon_id),
                type: "POST",
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function(data) {
                    if (data.status == 'success') {
                       // $("#createcoupon_form")[0].reset();
                        $("#yajra_datatable").DataTable().page(currentPage).draw(false); // Draw the table with the current page
                        $("#edit_coupon").modal('hide');
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
                },
                error: function(data) {
                    if (data.responseJSON.errors) {
                        $.each(data.responseJSON.errors, function(key, value) {
                            $("#" + key + "_error").html(value[0]);
                            $("#error_list_edit_coupon").append('<li>' + value[0] + '</li>');
                            $("#error_div_edit_coupon").show();
                        });
                    }
                }
            });
        });
    </script>
@endsection
