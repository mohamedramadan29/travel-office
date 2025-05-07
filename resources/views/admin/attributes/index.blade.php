@extends('admin.layouts.app')
@section('title', ' سمات المنتجات ')
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
                    <h3 class="mb-0 content-header-title d-inline-block"> ادارة سمات المنتجات </h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.welcome') }}">الرئيسية </a>
                                </li>
                                <li class="breadcrumb-item active"> ادارة سمات المنتجات
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
                                    data-target="#createattribute">
                                    اضافة سمة جديدة
                                </button>

                                @include('admin.attributes._create')
                                @include('admin.attributes._edit')

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
                                                    <th> الاسم </th>
                                                    <th> القيم </th>
                                                    <th> تاريخ الانشاء </th>
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
                $("#createbrand").modal('show');
            });
        </script>
    @endif

    <script>
        var lang = "{{ app()->getLocale() }}";
        $(document).ready(function() {
            $('#yajra_datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('dashboard.attributes.all') }}",
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
                        data: 'attributevalues',
                        name: 'attributevalues',
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
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
            let valueIndex = 2;
            $('#add_more').on('click', function(e) {
                // e.preventDefault();
                let newRow = `
                    <div class="row attribute_values_row d-flex align-items-center">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label for=""> اسم المتغير باللغة العربية </label>
                                <input type="text" class="form-control" name="value[${valueIndex}][ar]"
                                    value="{{ old('value[${valueIndex}][ar]') }}">
                                <strong class="text-danger" id="error_value"></strong>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label for=""> اسم المتغير باللغة الانجليزية </label>
                                <input type="text" class="form-control" name="value[${valueIndex}][en]"
                                    value="{{ old('value[${valueIndex}][en]') }}">
                                <strong class="text-danger" id="error_value"></strong>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <button type="button" class="btn btn-danger btn-sm remove"> <i class="la la-close"></i> </button>
                        </div>
                        <br>
                        <br>
                    </div>
                `;
                $('.attribute_values_row:last').after(newRow);
                valueIndex++;
            });
            $(document).on('click', '.remove', function() {
                $(this).closest('.attribute_values_row').remove();
            });
        });
    </script>

    <script>
        $(document).on('click', '.edit_attribute', function(e) {
            e.preventDefault();

            let id = $(this).data('id');
            let name_ar = $(this).data('name-ar');
            let name_en = $(this).data('name-en');
            let values = $(this).data('values');
            console.log(values);

            $(".attributevaluescontainer").empty(); // Remove Old Row


            $('#attribute_id').val(id);
            $("#AttributeNameAr").val(name_ar);
            $("#AttributeNameEn").val(name_en);

            let ValuesContainer = $(".attributevaluescontainer:last");
            ValuesContainer.empty();
            values.forEach(value => {
                ValuesContainer.after(`
                    <div class="attributevaluescontainer">
                     <div class='row'>
                      <div class="col-lg-5">
                            <div class="form-group">
                                <label for=""> اسم المتغير باللغة العربية </label>
                                <input type="text" class="form-control" name="value[${value.id}][ar]"
                                    value="${value.value_ar}">
                                <strong class="text-danger" id="error_value"></strong>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label for=""> اسم المتغير باللغة الانجليزية </label>
                                <input type="text" class="form-control" name="value[${value.id}][en]"
                                    value="${value.value_en}">
                                <strong class="text-danger" id="error_value"></strong>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <button type="button" class="btn btn-danger btn-sm remove"> <i class="la la-close"></i> </button>
                        </div>
                        </div>
                    </div>
                `);
            })
            ////// Delete Validations Error #
            $("#error_list_edit_attribute").empty();
            $("#error_div_edit_attribute").hide();
            $('#editattribute').modal('show');
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.add_more_attribute').on('click', function(e) {
                e.preventDefault();
                let valueIndex = 100;
                let newRow =
                    `
                 <div class="row attributevaluescontainer d-flex align-items-center">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label for=""> اسم المتغير باللغة العربية </label>
                                <input type="text" class="form-control" name="value[${valueIndex}][ar]"
                                    value="{{ old('value[${valueIndex}][ar]') }}">
                                <strong class="text-danger" id="error_value"></strong>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label for=""> اسم المتغير باللغة الانجليزية </label>
                                <input type="text" class="form-control" name="value[${valueIndex}][en]"
                                    value="{{ old('value[${valueIndex}][en]') }}">
                                <strong class="text-danger" id="error_value"></strong>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <button type="button" class="btn btn-danger btn-sm remove"> <i class="la la-close"></i> </button>
                        </div>
                        <br>
                        <br>
                    </div>
                `;
                $('.attributevaluescontainer:last').after(newRow);
                valueIndex++;
            });
            $(document).on('click', '.remove', function() {
                $(this).closest('.attributevaluescontainer').remove();
            });
        });
    </script>


    <!-- Update Attribute Using Ajax  -->

    <script>
        $("#editattributeform").on('submit', function(e) {
            e.preventDefault();
            var currentPage = $("#yajra_datatable").DataTable().page(); // Get the current page number
            var attribute_id = $("#attribute_id").val();
            var formData = new FormData(this);
            $.ajax({
                type: 'post',
                url: "{{ route('dashboard.attributes.update', 'id') }}".replace('id', attribute_id),
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    if (data.status == 'success') {
                        $("#yajra_datatable").DataTable().page(currentPage).draw(
                            false); // Draw the table with the current page
                        $('#editattribute').modal('hide');
                        $('#editattributeform')[0].reset();
                        Swal.fire({
                            position: "center",
                            icon: "success",
                            title: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                    if (data.status == 'error') {
                        Swal.fire({
                            position: "center",
                            icon: "error",
                            title: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                },
                error: function(data) {
                    if (data.status == 'error') {
                        $("#error_list_edit_attribute").empty();

                        let errors = response.responseJSON.errors;
                        $.each(errors, function(key, error) {
                            $("#error_list_edit_attribute").append('<li>' + error[0] + '</li>');
                            $("#error_div_edit_attribute").show();
                        });
                    }
                },
            })
        });
    </script>

@endsection
