@extends('admin.layouts.app')

@section('title', ' الاعدادات العامة')

@section('content')
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="mb-2 content-header-left col-md-6 col-12 breadcrumb-new">
                    <h3 class="mb-0 content-header-title d-inline-block"> الاعدادات العامة </h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.welcome') }}">الرئيسية </a>
                                </li>
                                <li class="breadcrumb-item active"><a href="#"> الاعدادات العامة </a>
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
                                    <h4 class="card-title" id="basic-layout-form"> الاعدادات العامة </h4>
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
                                        <form class="form setting_form" method="POST" enctype="multipart/form-data"
                                            action="{{ route('dashboard.settings.update', $setting['id']) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="site_name1"> اسم الموقع </label>
                                                            <input type="text" id="site_name1" class="form-control"
                                                                placeholder="  " name="site_name[ar]"
                                                                value="{{ old('site_name.ar', $setting->getTranslation('site_name','ar')) }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="site_name2"> اسم الموقع بالانجليزي </label>
                                                            <input type="text" id="site_name2" class="form-control"
                                                                placeholder="  " name="site_name[en]"
                                                                value="{{ old('site_name.en', $setting->getTranslation('site_name','en')) }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="site_desc1"> الوصف </label>
                                                            <input type="text" id="site_desc1" class="form-control"
                                                                placeholder="  " name="site_desc[ar]"
                                                                value="{{ old('site_desc.ar', $setting->getTranslation('site_desc','ar')) }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="site_desc1"> الوصف بالانجليزي </label>
                                                            <input type="text" id="site_desc1" class="form-control"
                                                                placeholder="  " name="site_desc[ar]"
                                                                value="{{ old('site_desc.ar', $setting->getTranslation('site_desc','ar')) }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="site_phone"> رقم الهاتف </label>
                                                            <input type="text" id="site_phone" class="form-control"
                                                                placeholder="  " name="site_phone"
                                                                value="{{ old('site_phone', $setting->site_phone) }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="site_email"> البريد الالكتروني </label>
                                                            <input type="text" id="site_email" class="form-control"
                                                                placeholder="  " name="site_email"
                                                                value="{{ old('site_email', $setting->site_email) }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="site_address"> العنوان </label>
                                                            <input type="text" id="site_address" class="form-control"
                                                                placeholder="  " name="site_address"
                                                                value="{{ old('site_address', $setting->site_address) }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="email_support"> البريد الالكتروني للدعم </label>
                                                            <input type="text" id="email_support" class="form-control"
                                                                placeholder="  " name="email_support"
                                                                value="{{ old('email_support', $setting->email_support) }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="facebook_url"> رابط الفيسبوك </label>
                                                            <input type="url" id="facebook_url" class="form-control"
                                                                placeholder="  " name="facebook_url"
                                                                value="{{ old('facebook_url', $setting->facebook_url) }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="twitter_url"> رابط منصة X </label>
                                                            <input type="url" id="twitter_url" class="form-control"
                                                                placeholder="  " name="twitter_url"
                                                                value="{{ old('twitter_url', $setting->twitter_url) }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="youtube_url"> رابط اليوتيوب </label>
                                                            <input type="url" id="youtube_url" class="form-control"
                                                                placeholder="  " name="youtube_url"
                                                                value="{{ old('youtube_url', $setting->youtube_url) }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="meta_description"> وصف ال meta </label>
                                                            <input type="text" id="meta_description" class="form-control"
                                                                placeholder="  " name="meta_description[ar]"
                                                                value="{{ old('meta_description[ar]', $setting->getTranslation('meta_description','ar')) }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="meta_description"> وصف ال meta En </label>
                                                            <input type="text" id="meta_description" class="form-control"
                                                                placeholder="  " name="meta_description[en]"
                                                                value="{{ old('meta_description[en]', $setting->getTranslation('meta_description','en')) }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="site_copyright"> حقوق الملكية   </label>
                                                            <input type="text" id="site_copyright" class="form-control"
                                                                placeholder="  " name="site_copyright[ar]"
                                                                value="{{ old('site_copyright[ar]', $setting->getTranslation('site_copyright','ar')) }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="site_copyright"> حقوق الملكية بالانجليزي  </label>
                                                            <input type="text" id="site_copyright" class="form-control"
                                                                placeholder="  " name="site_copyright[en]"
                                                                value="{{ old('site_copyright[en]', $setting->getTranslation('site_copyright','en')) }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="projectinput3"> لوجو المنصة </label>
                                                        <input type="file" class="form-control" name="logo"
                                                            id="single-image-edit">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="projectinput3">  Favicon </label>
                                                        <input type="file" class="form-control" name="favicon"
                                                            id="favicon">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions">
                                                <button hidden type="submit" id="submit_button" class="btn btn-primary">
                                                    <i class="la la-check-square-o"></i> حفظ
                                                </button>
                                                <button hidden type="button" id="cancel_button" class="mr-1 btn btn-danger">
                                                    <i class="la la-check-square-o"></i> رجوع
                                                </button>
                                                <button type="button" id="edit_button" class="mr-1 btn btn-warning">
                                                    <i class="la la-edit"></i> تعديل
                                                </button>

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
            language: lang,
            maxFileCount: 1,
            enableResumableUpload: false,
            showUpload: false,
            initialPreviewAsData: true,
            initialPreview: [
                "{{ asset($setting->logo) }}"
            ],
        });
        $("#favicon").fileinput({
            theme: 'fa5',
            allowedFileTypes: ['image'],
            language: lang,
            maxFileCount: 1,
            enableResumableUpload: false,
            showUpload: false,
            initialPreviewAsData: true,
            initialPreview: [
                "{{ asset($setting->favicon) }}"
            ],
        });
    </script>

    <script>

        $(document).ready(function() {
            $(".setting_form input").attr('readonly', true);
            $(".setting_form input").attr('disabled', true);
            $("#submit_button").attr('hidden', true);
            $("#cancel_button").attr('hidden', true);
        });

        $(document).on('click','#edit_button',function(){
            $("#edit_button").attr('hidden',true);
            $("#submit_button").removeAttr('hidden');
            $("#cancel_button").removeAttr('hidden');
            $(".setting_form input").removeAttr('readonly');
            $(".setting_form input").removeAttr('disabled');
        });

        $(document).on('click','#cancel_button',function(){
            $("#edit_button").removeAttr('hidden');
            $("#submit_button").attr('hidden',true);
            $("#cancel_button").attr('hidden',true);
            $(".setting_form input").attr('readonly',true);
            $(".setting_form input").attr('disabled',true);
            
        });
    </script>
    <!-- End File Input -->
@endsection
