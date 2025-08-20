@extends('admin.layouts.app')
@section('title', ' ادارة رواتب الموظفين ')
@section('content')
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="mb-2 content-header-left col-md-6 col-12 breadcrumb-new">
                    <h3 class="mb-0 content-header-title d-inline-block"> ادارة رواتب الموظفين </h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.welcome') }}">الرئيسية </a>
                                </li>
                                <li class="breadcrumb-item active"> ادارة رواتب الموظفين
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
                                <a href="{{ route('dashboard.employee_salary.create') }}" class="btn btn-primary btn-sm">
                                    اضافة راتب </a>
                                <a style="margin:5px" target="_blank" class="btn btn-info btn-sm"
                                    href="{{ route('dashboard.employee_salary.pdf') }}">
                                    استخراج ملف Pdf </a>
                                <a style="margin:5px" target="_blank" class="btn btn-warning btn-sm"
                                    href="{{ route('dashboard.employee_salary.excel') }}"> استخراج
                                    ملف Excel </a>
                            </div>
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th> الاسم </th>
                                                    <th> الراتب </th>
                                                    <th> الحالة </th>
                                                    <th> تاريخ الانشاء </th>
                                                    <th> العمليات </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($salaries as $salary)
                                                    <tr>
                                                        <th scope="row">{{ $loop->iteration }}</th>
                                                        <td> {{ $salary->employee->name }} </td>
                                                        <td> {{ number_format($salary->salary, 2) }} دينار </td>
                                                        <td>
                                                            @if ($salary->status = 1)
                                                                <span class="badge badge-success"> نشط </span>
                                                            @else
                                                                <span class="badge badge-danger"> غير نشط </span>
                                                            @endif
                                                        </td>
                                                        <td> {{ $salary->created_at->format('Y-m-d') }} </td>
                                                        <td>
                                                            <div class="dropdown float-md-left">
                                                                <button class="px-2 btn btn-primary dropdown-toggle"
                                                                    id="dropdownBreadcrumbButton" type="button"
                                                                    data-toggle="dropdown" aria-haspopup="true"
                                                                    aria-expanded="false"> العمليات </button>
                                                                <div class="dropdown-menu"
                                                                    aria-labelledby="dropdownBreadcrumbButton"><a
                                                                        class="dropdown-item"
                                                                        href="{{ route('dashboard.employee_salary.edit', $salary->id) }}"><i
                                                                            class="la la-edit"></i> تعديل </a>
                                                                    <form
                                                                        action="{{ route('dashboard.employee_salary.destroy', $salary->id) }}"
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
                                        {{ $salaries->links() }}
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
