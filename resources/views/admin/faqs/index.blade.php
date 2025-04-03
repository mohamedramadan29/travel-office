@extends('admin.layouts.app')
@section('title', ' ادارة الاسئلة الشائعة ')
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
                    <h3 class="mb-0 content-header-title d-inline-block"> ادارة الاسئلة الشائعة </h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.welcome') }}">الرئيسية </a>
                                </li>
                                <li class="breadcrumb-item active"> ادارة الاسئلة الشائعة
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
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createfaq">
                                    اضافة اسئلة
                                </button>

                                @include('admin.faqs._create')
                                @include('admin.faqs._edit')
                            </div>
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    @include('admin.layouts.toaster_error')
                                    @include('admin.layouts.toaster_success')

                                    <!-- Collapsible with Border Color -->
                                    <div class="col-xl-12 col-lg-12">
                                        <div class="card card-faq" id="headingCollapse51">

                                            @forelse($faqs as $faq)
                                                <div id="headingCollapse{{ $faq->id }}" role="tabpanel"
                                                    class="mt-1 card-header border-warning">
                                                    <a data-toggle="collapse" href="#collapse{{ $faq->id }}"
                                                        aria-expanded="false" aria-controls="collapse{{ $faq->id }}"
                                                        class="font-medium-1 warning collapsed @if ($loop->index == 0) show @endif">
                                                        {{ $faq->question }}
                                                    </a>
                                                    <a href='' class="float-right btn btn-primary btn-sm"> <i
                                                            class="la la-edit"></i> </a>
                                                    <a href='' class="float-right btn btn-danger btn-sm"> <i
                                                            class="la la-trash"></i> </a>
                                                </div>
                                                <div id="collapse{{ $faq->id }}" role="tabpanel"
                                                    aria-labelledby="headingCollapse{{ $faq->id }}"
                                                    class="card-collapse collapse @if ($loop->index == 0) show @endif"
                                                    aria-expanded="false" style="height: 0px;">
                                                    <div class="card-body">
                                                        {{ $faq->answer }}
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="alert alert-danger">
                                                    لا يوجد اسئلة شائعة
                                                </div>
                                            @endforelse

                                        </div>
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

    <script>
        $(document).on('submit', '#createfaq_form', function(e) {
            e.preventDefault();
            var formData = new FormData($('#createfaq_form')[0]);
            var local = "{{ app()->getLocale() }}";
            $.ajax({
                url: "{{ route('dashboard.faqs.store') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    if (data.status == 'success') {
                        var question = local == 'ar' ? data.faq.question.ar : data.faq.question.en;
                        var answer = local == 'ar' ? data.faq.answer.ar : data.faq.answer.en;
                        $('#createfaq_form')[0].reset();
                        $('#createfaq').modal('hide');
                        $(".card-faq").prepend(`
                            <div id="headingCollapse${data.faq.id}" role="tabpanel"
                            class="mt-1 card-header border-warning">
                            <a data-toggle="collapse" href="#collapse${data.faq.id}"
                                aria-expanded="false" aria-controls="collapse${data.faq.id}"
                                class="font-medium-1 warning collapsed show">
                                ${question}
                                </a>
                            <a href='' class="float-right btn btn-primary btn-sm"> <i
                                    class="la la-edit"></i> </a>
                            <a href='' class="float-right btn btn-danger btn-sm"> <i
                                    class="la la-trash"></i> </a>
                            </div>
                            <div id="collapse${data.faq.id}" role="tabpanel"
                            aria-labelledby="headingCollapse${data.faq.id}"
                            class="card-collapse collapse show"
                            aria-expanded="false" style="height: 0px;">
                            <div class="card-body">
                                ${answer}
                            </div>
                            </div>
                        `);
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
                },
            });
        });
    </script>

@endsection
