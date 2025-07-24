@extends('admin.layouts.app')

@section('title', ' تعديل خزينة  ')

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
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.safes.index') }}"> ادارة الخزائن
                                    </a>
                                </li>
                                <li class="breadcrumb-item active"><a href="#"> تعديل الخزينة  </a>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>

            </div>
            <div class="content-body">
                <!-- Basic form layout section start -->
                <section id="basic-form-layouts">
                    <div class="row match-height">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title" id="basic-layout-form"> تعديل خزينة  </h4>
                                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="mb-0 list-inline">
                                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                            <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                            <li><a data-action="close"><i class="ft-x"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-content collapse show">
                                    <div class="card-body">
                                        @include('admin.layouts.validation_errors')
                                        <form class="form" method="POST"
                                            action="{{ route('dashboard.safes.update', $safe->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="projectinput1"> الاسم  </label>
                                                            <input type="text" id="projectinput1" class="form-control"
                                                                placeholder="  " name="name"
                                                                value="{{ $safe->name }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="projectinput1"> الحالة  </label>
                                                            <select name="status" id="" class="form-control">
                                                                <option value="" selected disabled> -- حالة الخزينة  --
                                                                </option>
                                                                <option value="1"
                                                                    {{ $safe->status == 1 ? 'selected' : '' }}> مفعل
                                                                </option>
                                                                <option value="0"
                                                                    {{ $safe->status == 2 ? 'selected' : '' }}> غير مفعل
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="la la-check-square-o"></i> حفظ
                                                </button>
                                                <a href="{{ route('dashboard.safes.index') }}" type="button" class="mr-1 btn btn-warning">
                                                    <i class="ft-x"></i> رجوع
                                                </a>

                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </section>
                <!-- // Basic form layout section end -->
            </div>
        </div>
    </div>
@endsection
