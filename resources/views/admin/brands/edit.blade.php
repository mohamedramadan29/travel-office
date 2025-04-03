@extends('admin.layouts.app')

@section('title', ' تعديل العلامة التجارية   ')

@section('content')
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="mb-2 content-header-left col-md-6 col-12 breadcrumb-new">
                    <h3 class="mb-0 content-header-title d-inline-block"> العلامات التجارية </h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.welcome') }}">الرئيسية </a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.brands.index') }}"> العلامات التجارية
                                    </a>
                                </li>
                                <li class="breadcrumb-item active"><a href="#"> تعديل العلامة التجارية </a>
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
                                    <h4 class="card-title" id="basic-layout-form">  تعديل العلامة التجارية </h4>
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
                                            action="{{ route('dashboard.brands.update',$brand->id) }}" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="id" value="{{ $brand->id }}">
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="projectinput1"> الاسم  </label>
                                                            <input type="text" id="projectinput1" class="form-control"
                                                                placeholder="  " name="name[ar]"
                                                                value="{{ $brand->getTranslation('name','ar') ??  old('name.ar') }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="projectinput1"> الاسم بالانجليزي </label>
                                                            <input type="text" id="projectinput1" class="form-control"
                                                                placeholder="  " name="name[en]"
                                                                value="{{ $brand->getTranslation('name','en') ?? old('name.en') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="projectinput3"> صورة العلامة التجارية </label>
                                                            <input type="file" class="form-control" name="logo" id="single-image-edit">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="projectinput1"> حالة العلامة التجارية </label>
                                                            <select name="status" id="" class="form-control">
                                                                <option value="" selected disabled> -- حالة العلامة التجارية --
                                                                </option>
                                                                <option value="1" @selected($brand['status'] == 1)> مفعل
                                                                </option>
                                                                <option value="0" @selected($brand['status'] == 0)> غير مفعل
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
                                                <a href="{{ url()->previous() }}" type="button" class="mr-1 btn btn-warning">
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


@section('js')

  <!-- Start file Input  -->
  <script>
    var lang = "{{ app()->getLocale() }}";
      $("#single-image-edit").fileinput({
          theme: 'fa5',
          allowedFileTypes: ['image'],
          language:lang,
          maxFileCount: 1,
          enableResumableUpload: false,
          showUpload: false,
          initialPreviewAsData:true,
          initialPreview:[
            "{{ asset($brand->logo) }}"
          ],
      });
  </script>
  <!-- End File Input -->
@endsection
