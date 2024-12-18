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
                                        <img src="{{ asset('assets/admin') }}/images/logo/logo-dark.png"
                                            alt="branding logo">
                                    </div>
                                    <h6 class="pt-2 text-center card-subtitle line-on-side text-muted font-small-3">
                                        <span>{{ __('auth.login') }}</span>
                                    </h6>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        <form class="form-horizontal" method="POST"
                                            action="{{ url('dashboard/register_login') }}" novalidate>
                                            @csrf
                                            <fieldset class="form-group position-relative has-icon-left">
                                                <input type="email" class="form-control input-lg" id="email"
                                                    name="email" placeholder="{{ __('auth.email') }}" tabindex="1">
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
                                                    name="password" placeholder="{{ __('auth.password') }}" tabindex="2">
                                                <div class="form-control-position">
                                                    <i class="la la-key"></i>
                                                </div>
                                                <div class="help-block font-small-3"></div>
                                            </fieldset>
                                            <fieldset>
                                                {!! NoCaptcha::display() !!}
                                                @if ($errors->has('g-recaptcha-response'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                                    </span>
                                                @endif
                                            </fieldset>
                                            <br>
                                            <div class="form-group row">
                                                <div class="text-center col-md-6 col-12 text-md-left">
                                                    <fieldset>
                                                        <input name="remember" type="checkbox" id="remember-me"
                                                            class="chk-remember">
                                                        <label for="remember-me"> {{ __('auth.remember_me') }} </label>
                                                    </fieldset>
                                                </div>
                                                <div class="text-center col-md-6 col-12 text-md-right"><a
                                                        href="{{ route('dashboard.password.email') }}" class="card-link">
                                                        {{ __('auth.forget_password') }} ?</a></div>
                                            </div>
                                            <button type="submit" class="btn btn-danger btn-block btn-lg"><i
                                                    class="ft-unlock"></i> {{ __('auth.login') }}</button>
                                        </form>
                                    </div>
                                </div>
                                <div class="border-0 card-footer">
                                    <p class="mx-2 my-1 text-center card-subtitle line-on-side text-muted font-small-3">
                                        <span>New to Modern ?</span>
                                    </p>
                                    <a href="register-advanced.html" class="mt-3 btn btn-info btn-block btn-lg"><i
                                            class="ft-user"></i> {{ __('auth.register') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
