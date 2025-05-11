@extends('front.layouts.app')
@section('title')
    {{ __('login') }}
@endsection
@section('content')
    <section class="login footer-padding"
        style="background-image: url({{ asset('assets/front/assets/images/homepage-one/login-bg.webp') }})">
        <div class="container">
            <div class="login-section">
                <div class="review-form">
                    <h5 class="comment-title">{{ __('website.log_in') }}</h5>
                    <form action="{{ route('website.login.post') }}" method="post">
                        @csrf
                        <div class="review-inner-form">
                            <div class="review-form-name">
                                <label for="email" class="form-label">{{ __('website.email_address') }}*</label>
                                <input type="email" id="email" class="form-control" placeholder="{{ __('website.email_address') }}"
                                    name="email" />
                            </div>
                            <div class="review-form-name">
                                <label for="password" class="form-label">{{ __('website.password') }}*</label>
                                <input type="password" id="password" class="form-control" placeholder="{{ __('website.password') }}"
                                    name="password" />
                            </div>
                            <div class="review-form-name checkbox">
                                <div class="checkbox-item">
                                    <input type="checkbox" name="remember" />
                                    <span class="address">{{ __('website.remember_me') }}</span>
                                </div>
                                <div class="forget-pass">
                                    <p>{{ __('website.forgot_password') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="text-center login-btn">
                            <button type="submit" class="shop-btn">{{ __('website.log_in') }}</button>
                            <span class="shop-account">{{ __('website.dont_have_an_account') }}<a
                                    href="{{ route('website.register.show') }}">{{ __('website.sign_up_free') }}</a></span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
