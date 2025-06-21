@extends('front.layouts.app')
@section('title', __('website.brands'))
@section('content')
<section class="product brand" data-aos="fade-up" style="padding-top: 80px;padding-bottom: 80px;">
    <div class="container">
        <div class="section-title">
            <h5>{{ __('website.brands') }}</h5>
        </div>
        <div class="brand-section">
            @foreach ($brands as $brand)
            <div class="product-wrapper">
                <div class="wrapper-img">
                    <a href="{{ route('website.brands.products', $brand->slug) }}">
                        <img src="{{ asset($brand->logo) }}"
                            alt="{{ $brand->getTranslation('name', app()->getLocale()) }}" />
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
