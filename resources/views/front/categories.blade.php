@extends('front.layouts.app')
@section('title', __('website.categories'))
@section('content')
   {{--  Start Category  --}}
    <section class="product-category" style="padding-top: 80px;padding-bottom: 80px;">
        <div class="container">
            <div class="section-title">
                <h5>{{ __('website.categories') }}</h5>
            </div>
            <div class="category-section">
                @foreach ($categories as $category)
                <div class="product-wrapper" data-aos="fade-right" data-aos-duration="100">
                    <div class="wrapper-img">
                        <img src="{{ asset('assets/front/') }}/assets/images/homepage-one/category-img/dresses.webp"
                            alt="dress" />
                    </div>
                    <div class="wrapper-info">
                        <a href="{{ route('website.categories.products', $category->slug) }}" class="wrapper-details">{{ $category->getTranslation('name', app()->getLocale()) }}</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
