@extends('admin.layouts.app')
@section('title')
    الرئيسية
@endsection
@section('content')
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <!-- eCommerce statistic -->
                <div class="row">
                    <div class="col-xl-3 col-lg-6 col-12">
                        <div class="card pull-up">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="media d-flex">
                                        <div class="text-left media-body">
                                            <h3 class="info"> {{ $suppliers }} </h3>
                                            <h6> الموردين  </h6>
                                        </div>
                                        <div>
                                            <i class="float-right icon-basket-loaded info font-large-2"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-12">
                        <div class="card pull-up">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="media d-flex">
                                        <div class="text-left media-body">
                                            <h3 class="info"> {{ $clients }} </h3>
                                            <h6> العملاء  </h6>
                                        </div>
                                        <div>
                                            <i class="float-right icon-basket-loaded info font-large-2"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-12">
                        <div class="card pull-up">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="media d-flex">
                                        <div class="text-left media-body">
                                            <h3 class="info"> {{ $purchases }} </h3>
                                            <h6> فواتير الشراء </h6>
                                        </div>
                                        <div>
                                            <i class="float-right icon-basket-loaded info font-large-2"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-12">
                        <div class="card pull-up">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="media d-flex">
                                        <div class="text-left media-body">
                                            <h3 class="info"> {{ $sales }} </h3>
                                            <h6> فواتير البيع  </h6>
                                        </div>
                                        <div>
                                            <i class="float-right icon-basket-loaded info font-large-2"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ Recent Transactions -->
            </div>
        </div>
    </div>
@endsection
