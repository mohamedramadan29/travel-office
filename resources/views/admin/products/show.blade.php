@extends('admin.layouts.app')
@section('title', ' تفاصيل المنتج ')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.min.css">
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.2/css/buttons.dataTables.min.css"> --}}

@endsection
@section('content')
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="mb-2 content-header-left col-md-6 col-12 breadcrumb-new">
                    <h3 class="mb-0 content-header-title d-inline-block"> تفاصيل المنتج </h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.welcome') }}">الرئيسية </a>
                                </li>
                                <li class="breadcrumb-item active"> تفاصيل المنتج
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
                    <div class="col-lg-6 col-12">
                        <div class="card">
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <div id="carouselExampleControls_{{ $product['id'] }}" class="carousel slide"
                                        data-ride="carousel">
                                        <div class="carousel-inner">
                                            @foreach ($product->images as $key => $image)
                                                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                                    <img class="d-block w-100"
                                                        src="{{ asset('uploads/products/' . $image->file_name) }}"
                                                        alt="First slide">
                                                </div>
                                            @endforeach
                                        </div>
                                        <a class="carousel-control-next"
                                            href="#carouselExampleControls_{{ $product['id'] }}" role="button"
                                            data-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                        <a class="carousel-control-prev"
                                            href="#carouselExampleControls_{{ $product['id'] }}" role="button"
                                            data-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Previous</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-12">
                        <div class="card">
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <h3> {{ $product->name }} </h3>
                                    <hr>
                                    <p> <span class="badge badge-info"> الوصف المختصر :: </span> {{ $product->small_desc }}
                                    </p>
                                    <hr>
                                    <p> <span class="badge badge-info"> الوصف :: </span> {{ $product->description }} </p>
                                    <hr>
                                    @if ($product->has_variants)
                                        <p> <span class="badge badge-info"> السعر :: </span>
                                            {{ number_format($product->price, 2) }} </p>
                                        <hr>
                                        @if ($product->manage_stock)
                                            <p> <span class="badge badge-info"> الكمية :: </span> {{ $product->qty }} </p>
                                            <hr>
                                        @endif
                                        @if ($product->has_discount)
                                            <p> <span class="badge badge-info"> قيمة الخصم :: </span>
                                                {{ number_format($product->discount, 2) }} </p>
                                        @endif
                                    @else
                                        <p class="badge badge-info"> منتج متغير </p>
                                        <hr>
                                    @endif

                                    <p> <span class="badge badge-info"> متاح الي :: </span> {{ $product->available_for }}
                                    </p>
                                    <hr>
                                    <p> <span class="badge badge-info"> SKU :: </span> {{ $product->sku }} </p>
                                    <hr>
                                    <p> <span class="badge badge-info"> عدد المشاهدات :: </span> {{ $product->views }} </p>
                                    <hr>

                                    <p> <span class="badge badge-info"> متاح في المخزون :: </span>
                                        {{ $product->available_in_stock ? 'نعم' : 'لا' }} </p>
                                    <hr>

                                    <p> <span class="badge badge-info"> قسم المنتج :: </span>
                                        {{ $product->category->name }} </p>
                                    <hr>

                                    <p> <span class="badge badge-info"> العلامة التجارية :: </span>
                                        {{ $product->Brand->name }} </p>
                                    <hr>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th> # </th>
                                    <th> السعر </th>
                                    <th> الكمية </th>
                                    <th> المتغيرات </th>
                                    <th> العمليات </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($product->Vartians as $variant)
                                    <tr>
                                        <td> {{ $loop->iteration }} </td>
                                        <td> {{ $variant->price }} </td>
                                        <td> {{ $variant->stock }} </td>
                                        <td>
                                            @foreach ($variant->VartiantAttributes as $Vartiantattribute)
                                                <span class="badge badge-danger">
                                                    {{ $Vartiantattribute->AttributeValue->attribute->name }} ::
                                                    {{ $Vartiantattribute->AttributeValue->value }}
                                                </span>
                                            @endforeach
                                        </td>
                                        <td>
                                            <a class="btn btn-danger btn-sm" href="{{ route('dashboard.product.vartiants.delete', $variant->id) }}"><i
                                                    class="la la-trash"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Bordered striped end -->
            </div>
        </div>
    </div>


@endsection

@section('js')
@endsection
