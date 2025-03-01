@extends('admin.layouts.app')
@section('title', ' ادارة الدول ')
@section('css')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/admin') }}/vendors/css/forms/toggle/bootstrap-switch.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin') }}/vendors/css/forms/toggle/switchery.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin') }}/css-rtl/plugins/forms/switch.css">
@endsection
@section('content')
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="mb-2 content-header-left col-md-6 col-12 breadcrumb-new">
                    <h3 class="mb-0 content-header-title d-inline-block"> ادارة الدول </h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.welcome') }}">الرئيسية </a>
                                </li>
                                <li class="breadcrumb-item active"> ادارة الدول
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
                                <a href="#" class="btn btn-primary"> اضافة دولة </a>
                            </div>
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    @include('admin.layouts.toaster_error')
                                    @include('admin.layouts.toaster_success')
                                    <form action="{{ url()->current() }}" method="GET">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <input type="text" name="keyword" class="form-control" placeholder="بحث">
                                            </div>
                                            <div class="col-lg-3">
                                                <button type="submit" name="" id="search" class="btn btn-primary"> بحث </button>
                                            </div>
                                        </div>
                                    </form>
                                    <br>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th> اسم الدول </th>
                                                    <th> كود الجوال </th>
                                                    <th> عدد المحافظات </th>
                                                    <th> عدد المستخدمين  </th>
                                                    <th> الحالة </th>
                                                    <th> ادارة الحالة </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($countries as $country)
                                                    <tr>
                                                        <th scope="row">{{ $loop->iteration }}</th>
                                                        <td> <a href="{{ route('dashboard.world.governorates', $country->id) }}"> {{ $country->getTranslation('name', 'ar') }}   </a></td>
                                                        <td> {{ $country->phone_code }} </td>
                                                        <td>
                                                            <span class="badge badge-info">
                                                                {{ $country->governorates_count }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-warning">
                                                                {{ $country->users_count }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="badge {{ $country->is_active == 'مفعل' ? 'badge-success' : 'badge-danger' }}"
                                                                id="status_{{ $country->id }}"> {{ $country->is_active }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <fieldset>
                                                                <input type="checkbox" class="switch change_status"
                                                                    @if ($country->is_active == 'مفعل') checked @endif
                                                                    id="change_status_{{ $country->id }}"
                                                                    data-group-cls="btn-group-sm"
                                                                    country-id="{{ $country->id }}">
                                                            </fieldset>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <td colspan="4"> لا يوجد بيانات </td>
                                                @endforelse
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

    <script src="{{ asset('assets/admin') }}/vendors/js/forms/toggle/bootstrap-checkbox.min.js" type="text/javascript">
    </script>
    <!-- BEGIN PAGE LEVEL JS-->
    <script src="{{ asset('assets/admin') }}/js/scripts/forms/switch.js" type="text/javascript"></script>

    <script>
        $(document).ready(function() {
            $('.change_status').change(function() {
                var id = $(this).attr('country-id');
                var url = "{{ route('dashboard.world.update_status', ':id') }}";
                url = url.replace(':id', id);
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        if (response.status == 'success') {
                            $('.tostar_success').text(response.message).fadeIn().delay(5000)
                                .fadeOut('slow');

                            /// Change Status Text
                            $('#status_' + response.data.id).empty();
                            $('#status_' + response.data.id).text(response.data.is_active);
                        } else {
                            $('.tostar_error').text(response.message).fadeIn().delay(5000)
                                .fadeOut('slow');
                        }
                        setTimeout(function() {
                            $('tostar_success').fadeOut('slow');
                        }, 5000);
                    }
                });
            });
        });
    </script>
@endsection
