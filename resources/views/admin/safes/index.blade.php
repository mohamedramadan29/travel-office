@extends('admin.layouts.app')
@section('title', ' ادارة الخزائن ')
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
                    <h3 class="mb-0 content-header-title d-inline-block"> ادارة الخزائن </h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.welcome') }}">الرئيسية </a>
                                </li>
                                <li class="breadcrumb-item active"> ادارة الخزائن
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
                                <a href="{{ route('dashboard.safes.create') }}" class="btn btn-primary btn-sm"> اضافة خزينة جديدة
                                </a>
                                <a style="margin:5px" target="_blank" class="btn btn-info btn-sm"
                                href="{{ route('dashboard.safes.pdf') }}">
                                استخراج ملف Pdf </a>
                                <a style="margin:5px" target="_blank" class="btn btn-warning btn-sm"
                                href="{{ route('dashboard.safes.excel') }}"> استخراج
                                    ملف Excel </a>
                            </div>
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
                                                    <th> الاسم </th>
                                                    <th> الرصيد الحالي  </th>
                                                    <th> الحالة </th>
                                                    <th> تاريخ الانشاء </th>
                                                    <th> العمليات </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($safes as $safe)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $safe->name }}</td>
                                                        <td>{{ $safe->balance }} دينار </td>
                                                        <td>
                                                            @if($safe->status == 1)
                                                                <span class="badge badge-success">مفعل</span>
                                                            @else
                                                                <span class="badge badge-danger">غير مفعل</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $safe->created_at }}</td>
                                                        <td>
                                                            <div class="dropdown float-md-left">
                                                                <button class="px-2 btn btn-primary btn-sm dropdown-toggle"
                                                                    id="dropdownBreadcrumbButton" type="button"
                                                                    data-toggle="dropdown" aria-haspopup="true"
                                                                    aria-expanded="false"> العمليات </button>
                                                                <div class="dropdown-menu"
                                                                    aria-labelledby="dropdownBreadcrumbButton">
                                                                    <a
                                                                        class="dropdown-item"
                                                                        href="{{ route('dashboard.safes.edit', $safe->id) }}"><i
                                                                            class="la la-edit"></i> تعديل </a>
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('dashboard.safes.status', $safe->id) }}"><i
                                                                            class="la la-edit"></i> تعديل الحالة </a>
                                                                    <form
                                                                        action="{{ route('dashboard.safes.destroy', $safe->id) }}"
                                                                        method="post">
                                                                        @csrf
                                                                        @method('delete')
                                                                        <button type="submit"
                                                                            class="dropdown-item delete_confirm"><i
                                                                                class="la la-trash"></i> حذف </button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                            <button style="margin-right: 3px" type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#addbalance{{ $safe->id }}">
                                                                <i class="bi bi-plus"></i>
                                                              </button>
                                                              <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#removebalance{{ $safe->id }}">
                                                                <i class="bi bi-dash"></i>
                                                              </button>
                                                              <a href="{{ route('dashboard.safes.movements',$safe->id) }}" class="btn btn-warning btn-sm">  <i class="bi bi-arrow-left-right"></i> </a>
                                                        </td>
                                                    </tr>
                                                    @include('admin.safes._add_balance', ['safe' => $safe])
                                                    @include('admin.safes._remove_balance', ['safe' => $safe])
                                                @empty
                                                    <td colspan="4"> لا يوجد بيانات </td>
                                                @endforelse

                                            </tbody>
                                        </table>
                                        {{ $safes->links() }}
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
