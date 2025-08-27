@extends('admin.layouts.app')
@section('title', ' تصنيفات الإيرادات ')
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
                    <h3 class="mb-0 content-header-title d-inline-block"> ادارة تصنيفات الإيرادات </h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.welcome') }}">الرئيسية </a>
                                </li>
                                <li class="breadcrumb-item active"> ادارة تصنيفات الإيرادات
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
                                <a href="{{ route('dashboard.income_services_categories.create') }}" class="btn btn-primary btn-sm"> اضافة
                                    تصنيف جديد
                                </a>
                                <a style="margin:5px" target="_blank" class="btn btn-info btn-sm"
                                href="{{ route('dashboard.income_services_categories.pdf') }}">
                                استخراج ملف Pdf </a>
                            <a style="margin:5px" target="_blank" class="btn btn-warning btn-sm"
                                href="{{ route('dashboard.income_services_categories.excel') }}"> استخراج
                                    ملف Excel </a>
                            </div>
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    @include('admin.layouts.toaster_error')
                                    @include('admin.layouts.toaster_success')

                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered column-rendering">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th> الاسم </th>
                                                    <th> الحالة </th>
                                                    <th> تاريخ الانشاء </th>
                                                    <th> العمليات </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($categories as $category)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $category->name }}</td>
                                                        <td>
                                                            @if ($category->status == 1)
                                                                <span class="badge badge-success">مفعل</span>
                                                            @else
                                                                <span class="badge badge-danger">غير مفعل</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $category->created_at }}</td>
                                                        <td>
                                                            <a href="{{ route('dashboard.income_services_categories.edit', $category->id) }}"
                                                                class="btn btn-primary btn-sm"> تعديل </a>

                                                            <form
                                                                action="{{ route('dashboard.income_services_categories.destroy', $category->id) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="delete_confirm btn btn-danger btn-sm"> حذف
                                                                </button>
                                                            </form>

                                                        </td>
                                                    </tr>
                                                @empty
                                                    <td colspan="4"> لا يوجد بيانات </td>
                                                @endforelse

                                            </tbody>
                                        </table>
                                        {{ $categories->links() }}
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
