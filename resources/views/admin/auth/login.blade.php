@extends('admin.layouts.auth')
@section('title', 'تسجيل الدخول')

@section('content')
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <section class="flexbox-container">
                    <div class="col-12 d-flex align-items-center justify-content-center">
                        <div class="p-0 col-md-4 col-10 box-shadow-2">
                            <div class="m-0 card border-grey border-lighten-3">
                                <div class="border-0 card-header">
                                    <div class="text-center card-title">
                                        <img width="140px" src="{{ asset('uploads/settings/logo.png') }}"
                                            alt="branding logo">
                                    </div>
                                    <h6 class="pt-2 text-center card-subtitle line-on-side text-muted font-small-3">
                                        <span> تسجيل الدخول </span>
                                    </h6>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        <form class="form-horizontal" method="POST"
                                            action="{{ url('dashboard/register_login') }}" novalidate>
                                            @csrf
                                            <fieldset class="form-group position-relative has-icon-left">
                                                <input type="email" class="form-control input-lg" id="email"
                                                    name="email" placeholder="البريد الالكتروني" tabindex="1">
                                                <div class="form-control-position">
                                                    <i class="ft-user"></i>
                                                </div>
                                                <div class="help-block font-small-3"></div>
                                                @error('email')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </fieldset>
                                            <fieldset class="form-group position-relative has-icon-left">
                                                <input type="password" class="form-control input-lg" id="password"
                                                    name="password" placeholder="كلمه المرور" tabindex="2">
                                                <div class="form-control-position">
                                                    <i class="la la-key"></i>
                                                </div>
                                                <div class="help-block font-small-3"></div>
                                            </fieldset>
                                            {{-- <fieldset>
                                                {!! NoCaptcha::display() !!}
                                                @if ($errors->has('g-recaptcha-response'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                                    </span>
                                                @endif
                                            </fieldset> --}}
                                            <br>
                                            <div class="form-group row">
                                                <div class="text-center col-md-6 col-12 text-md-left">
                                                    <fieldset>
                                                        <input name="remember" type="checkbox" id="remember-me"
                                                            class="chk-remember">
                                                        <label for="remember-me"> تذكرني  </label>
                                                    </fieldset>
                                                </div>
                                                {{-- <div class="text-center col-md-6 col-12 text-md-right"><a
                                                        href="{{ route('dashboard.password.email') }}" class="card-link">
                                                        نسيت كلمة المرور ؟</a></div> --}}
                                            </div>
                                            <button type="submit" class="btn btn-danger btn-block btn-lg"><i
                                                    class="ft-unlock"></i> تسجيل الدخول </button>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
