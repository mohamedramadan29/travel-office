@extends('admin.layouts.app')
@section('title')
اضافة عميل جديد
@endsection
@section('content')
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="mb-2 content-header-left col-md-6 col-12 breadcrumb-new">
                <h3 class="mb-0 content-header-title d-inline-block">اضافة عميل جديد</h3>
                <div class="row breadcrumbs-top d-inline-block">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.welcome') }}">الرئيسية </a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.clients.index') }}">العملاء</a>
                            </li>
                            <li class="breadcrumb-item active"><a href="#">اضافة عميل جديد</a>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <section id="basic-form-layouts">
                <div class="row match-height">
                    <div class="col-md-12">

                        <div class="card">

                            <div class="card-header">
                                <h4 class="card-title" id="basic-layout-colored-form-control">اضافة عميل جديد</h4>
                                <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>

                            </div>
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    @include('admin.layouts.validation_errors')
                                    <form class="form" action="{{ route('dashboard.clients.store') }}" method="POST">
                                        @csrf
                                        <div class="form-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="userinput1"> اسم العميل</label>
                                                        <input type="text" id="userinput1" class="form-control"
                                                            name="name">
                                                    </div>

                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="userinput1"> البريد الالكتروني</label>
                                                        <input type="email" id="userinput1" class="form-control"
                                                            name="email">
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="userinput1"> رقم الهاتف</label>
                                                        <input type="text" id="userinput1" class="form-control"
                                                            placeholder="" name="mobile">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="userinput1"> رقم التيلغرام</label>
                                                        <input type="number" id="userinput1" class="form-control"
                                                            placeholder="" name="telegram">
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="userinput1"> رقم الواتساب</label>
                                                        <input type="number" id="userinput1" class="form-control"
                                                            placeholder="" name="whatsapp">
                                                    </div>

                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>الحالة</label>
                                                        <select class="form-control" name="status">
                                                            <optgroup label="الحالة">
                                                                <option value="1">نشط</option>
                                                                <option value="0">غير نشط</option>
                                                            </optgroup>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="userinput1"> العنوان</label>
                                                        <input type="text" id="userinput1" class="form-control"
                                                            placeholder="" name="address">
                                                    </div>

                                                </div>
                                            </div>

                                            <!-- Transaction Section -->
                                            <hr style="margin: 30px 0;">
                                            <h5 style="color: #28a745; margin-bottom: 20px;">
                                                <i class="la la-money"></i>
                                                إضافة معاملة مالية للعميل (اختياري)
                                            </h5>

                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="transaction_amount">المبلغ (د.ل)</label>
                                                        <input type="number" step="0.01" min="0" id="transaction_amount"
                                                            class="form-control" name="transaction_amount"
                                                            placeholder="أدخل المبلغ">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="safe_id">الخزينة</label>
                                                        <select class="form-control" name="safe_id" id="safe_id">
                                                            <option value="">اختر الخزينة</option>
                                                            @foreach(\App\Models\admin\Safe::active()->get() as $safe)
                                                            <option value="{{ $safe->id }}">{{ $safe->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="transaction_type">نوع المعاملة</label>
                                                        <select class="form-control" name="transaction_type"
                                                            id="transaction_type">
                                                            <option value="">اختر النوع</option>
                                                            <option value="credit">دفعة للعميل (للعميل)</option>
                                                            <option value="debit">دفعة من العميل (من العميل)</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="transaction_notes">ملاحظات المعاملة</label>
                                                        <textarea class="form-control" rows="3" name="transaction_notes"
                                                            id="transaction_notes"
                                                            placeholder="أدخل ملاحظات حول المعاملة (اختياري)"></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="alert alert-info" role="alert">
                                                <strong>ملاحظة:</strong> إذا تم إدخال مبلغ، يجب اختيار الخزينة ونوع
                                                المعاملة
                                            </div>
                                        </div>
                                        <div class="form-actions right">
                                            <a href="{{ route('dashboard.clients.index') }}" type="button"
                                                class="mr-1 btn btn-warning">
                                                <i class="ft-x"></i> الغاء
                                            </a>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="la la-check-square-o"></i> حفظ
                                            </button>
                                        </div>
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

@section('js')
<script>
    $(document).ready(function() {
    // Initially disable transaction fields
    toggleTransactionFields();

    // Monitor transaction amount field
    $('#transaction_amount').on('input', function() {
        toggleTransactionFields();
    });

    function toggleTransactionFields() {
        var amount = parseFloat($('#transaction_amount').val());
        var hasAmount = !isNaN(amount) && amount > 0;

        $('#safe_id').prop('required', hasAmount);
        $('#transaction_type').prop('required', hasAmount);

        if (hasAmount) {
            $('#safe_id, #transaction_type').closest('.form-group').find('label').addClass('required-field');
        } else {
            $('#safe_id, #transaction_type').closest('.form-group').find('label').removeClass('required-field');
            $('#safe_id, #transaction_type').val('');
        }
    }
});
</script>

<style>
    .required-field::after {
        content: " *";
        color: #e74c3c;
        font-weight: bold;
    }
</style>
@endsection
@endsection
