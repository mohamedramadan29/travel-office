@extends('front.layouts.app')
@section('title')
    {{ __('Register') }}
@endsection
@section('content')
    <section class="login account footer-padding"
        style="background-image: url({{ asset('assets/front/assets/images/homepage-one/login-bg.webp') }})">
        <div class="container">
            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            <form action="{{ route('website.register.post') }}" method="post">
                @csrf
                <div class="login-section account-section">
                    <div class="review-form">
                        <h5 class="comment-title">{{ __('website.create_an_account') }}</h5>
                        <div class="account-inner-form">
                            <div class="review-form-name">
                                <label for="name" class="form-label">{{ __('website.name') }}*</label>
                                <input type="text" id="name" name="name" class="form-control"
                                    placeholder="{{ __('website.name') }}" />
                            </div>
                        </div>
                        <div class="account-inner-form">
                            <div class="account-inner-form">
                                <div class="review-form-name">
                                    <label for="email" class="form-label">{{ __('website.email_address') }}*</label>
                                    <input type="email" id="email" name="email" class="form-control"
                                        placeholder="{{ __('website.email_address') }}" />
                                </div>
                            </div>
                            <div class="account-inner-form">
                                <div class="review-form-name">
                                    <label for="phone" class="form-label">{{ __('website.phone') }}*</label>
                                    <input type="text" id="phone" name="phone" class="form-control"
                                        placeholder="{{ __('website.phone') }}" />
                                </div>
                            </div>
                        </div>
                        <div class="account-inner-form">

                        <div class="account-inner-form">
                            <div class="review-form-name">
                                <label for="country" class="form-label">{{ __('website.country') }}*</label>
                                <select name="country_id" id="country" class="form-control">
                                    <option value="">{{ __('website.select_country') }}</option>
                                    <option value="1"> مصر </option>
                                </select>
                            </div>
                        </div>
                        <div class="account-inner-form">
                            <div class="review-form-name">
                                <label for="governrate" class="form-label">{{ __('website.governrate') }}*</label>
                                <select name="governrate_id" id="governrate" class="form-control">
                                    <option value="">{{ __('website.select_governrate') }}</option>
                                    <option value="1"> القاهرة </option>
                                </select>
                            </div>
                        </div>
                        </div>
                        <div class="account-inner-form city-inner-form">
                            <div class="review-form-name">
                                <label for="city" class="form-label">{{ __('website.city') }}*</label>
                                <select name="city_id" id="city" class="form-control">
                                    <option value="">{{ __('website.select_city') }}</option>
                                    <option value="1"> القاهرة </option>
                                </select>
                            </div>
                        </div>
                        <div class="account-inner-form">
                        <div class="account-inner-form">
                            <div class="review-form-name">
                                <label for="password" class="form-label">{{ __('website.password') }}*</label>
                                <input type="password" id="password" name="password" class="form-control"
                                    placeholder="{{ __('website.password') }}" />
                            </div>
                        </div>
                        <div class="account-inner-form">
                            <div class="review-form-name">
                                <label for="password_confirmation"
                                    class="form-label">{{ __('website.password_confirmation') }}*</label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="form-control" placeholder="{{ __('website.password_confirmation') }}" />
                            </div>
                        </div>
                        </div>
                        <div class="review-form-name checkbox">
                            <div class="checkbox-item">
                                <input type="checkbox" name="terms" id="terms" />
                                <p class="remember">
                                    {{ __('website.i_agree_all_terms_and_condition_in') }}
                                    <span class="inner-text">{{ __('website.shop_us') }}.</span>
                                </p>
                            </div>
                        </div>
                        <div class="text-center login-btn">
                            <button type="submit" class="shop-btn">{{ __('website.create_an_account') }}</button>
                            <span class="shop-account">{{ __('website.already_have_an_account') }}<a
                                    href="{{ route('website.login.show') }}">{{ __('website.log_in') }}</a></span>
                        </div>
                    </div>
            </form>
        </div>
    </section>
@endsection
