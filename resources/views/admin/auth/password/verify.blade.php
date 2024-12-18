@extends('admin.layouts.auth')
@section('title','Confirm Otp')
@section('content')
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <section class="flexbox-container">
                <div class="col-12 d-flex align-items-center justify-content-center">
                    <div class="p-0 col-md-4 col-10 box-shadow-2">
                        <div class="px-2 py-2 m-0 card border-grey border-lighten-3">
                            <div class="pb-0 border-0 card-header">
                                <div class="text-center card-title">
                                    <img src="{{ asset('assets/admin/') }}/images/logo/logo-dark.png" alt="branding logo">
                                </div>
                                <h6 class="pt-2 text-center card-subtitle line-on-side text-muted font-small-3">
                                    <span>We will send you a link to reset password.</span>
                                </h6>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <form class="form-horizontal" action="{{ route('dashboard.password.otp.post') }}" novalidate>
                                        @csrf
                                        <fieldset class="form-group position-relative has-icon-left">
                                            <input type="hidden" name="email" value="{{ $email }}">
                                            <input type="text" class="form-control form-control-lg input-lg"
                                                id="user-email" name="code" placeholder="Enter Otp Verify" required>
                                                @error('code')
                                                <p class="text-red strong">{{ $message }}</p>
                                                @enderror
                                            <div class="form-control-position">
                                                <i class="ft-mail"></i>
                                            </div>
                                        </fieldset>
                                        <button type="submit" class="btn btn-outline-info btn-lg btn-block"><i
                                                class="ft-unlock"></i> Send  </button>
                                    </form>
                                </div>
                            </div>
                            <div class="border-0 card-footer">
                                <p class="text-center float-sm-left"><a href="{{ route('dashboard.login.show') }}"
                                        class="card-link">Login</a></p>

                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
