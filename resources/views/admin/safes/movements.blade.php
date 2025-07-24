@extends('admin.layouts.app')
@section('title', ' عمليات الخزينة ')
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
                    <h3 class="mb-0 content-header-title d-inline-block"> عمليات الخزينة </h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.welcome') }}">الرئيسية </a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.safes.index') }}">الخزائن </a>
                                </li>
                                <li class="breadcrumb-item active"> عمليات الخزينة
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
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    @include('admin.layouts.toaster_error')
                                    @include('admin.layouts.toaster_success')

                                    <div class="table-responsive">
                                        <table
                                            class="table table-striped table-bordered column-rendering">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th> الخزينة </th>
                                                    <th> نوع العملية </th>
                                                    <th>  المبلغ </th>
                                                    <th> الموظف  </th>
                                                    <th> تاريخ العملية </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ( $safe->movements as $movement)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $movement->safe->name }}</td>
                                                        <td>{{ $movement->movment_type }}</td>
                                                        <td>{{ $movement->amount }} دينار </td>
                                                        <td>{{ $movement->admin->name }}</td>
                                                        <td>{{ $movement->created_at }}</td>
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

    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>

@endsection
