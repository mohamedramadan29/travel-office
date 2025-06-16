@extends('admin.layouts.app')

@section('title', ' الاتصالات ')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/') }}/fonts/simple-line-icons/style.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/') }}/css-rtl/pages/email-application.css">
@endsection

@section('content')
    <div class="app-content content">
        <div class="sidebar-left">
            <div class="sidebar" style="width:600px !important">
                <div class="sidebar-content email-app-sidebar d-flex">
                    @livewire('dashboard.contact.contact-sidebar')
                    @livewire('dashboard.contact.contact-message')

                </div>
            </div>
        </div>
        <div class="content-right" style="width: calc(100% - 600px);">
            <div class="content-wrapper">
                <div class="content-header row">
                </div>
                <div class="content-body">
                    <div class="card email-app-details d-none d-lg-block">
                        @livewire('dashboard.contact.contact-show')
                        @livewire('dashboard.contact.replay-contact')

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('assets/admin/') }}/js/scripts/pages/email-application.js" type="text/javascript"></script>
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('MsgDeleted', (event) => {
                Swal.fire({
                    icon: 'success',
                    title: 'تم!',
                    text: 'تم حذف الرسالة بنجاح  ',
                });
            });
            Livewire.on('Launch-replay-modal', (event) => {
                $('#replay-modal').modal('show');
            });
            Livewire.on('Close-replay-modal', (event) => {
                $('#replay-modal').modal('hide');
            });
            Livewire.on('MsgReplied', (event) => {
                Swal.fire({
                    icon: 'success',
                    title: 'تم!',
                    text: 'تم الرد على الرسالة بنجاح  ',
                });
            });
        });
    </script>

@endsection
