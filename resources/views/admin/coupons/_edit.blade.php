<!-- Modal -->
<div class="modal fade" id="edit_coupon" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel"> تعديل كوبون </h1>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Validation Errors -->
                <div class="alert alert-danger" id='error_div_edit_coupon' style="display: none;">
                    <ul id="error_list_edit_coupon">
                    </ul>
                </div>
                <form action="" id="edit_coupon_form" method="post">
                    @method('PUT')
                    @csrf
                    <input type="hidden" name="id" id="coupon_id">
                    <div class="form-group">
                        <label for=""> الكود </label>
                        <input type="text" class="form-control" name="code" value="{{ old('code') }}" id="coupon_code">
                        <strong class="text-danger" id="code_error"></strong>
                    </div>
                    <div class="form-group">
                        <label for=""> النسبة المئوية للخصم </label>
                        <input type="number" class="form-control" name="discount_percentage"
                            value="{{ old('discount_percentage') }}" id="coupon_discount_percentage">
                        <strong class="text-danger" id="discount_percentage_error"></strong>
                    </div>
                    <div class="form-group">
                        <label for=""> التاريخ البداية للخصم </label>
                        <input type="date" class="form-control" name="start_date" required
                            value="{{ old('start_date') }}" id="coupon_start_date">
                        <strong class="text-danger" id="start_date_error"></strong>
                    </div>
                    <div class="form-group">
                        <label for=""> التاريخ النهاية للخصم </label>
                        <input type="date" class="form-control" name="end_date" required
                            value="{{ old('end_date') }}" id="coupon_end_date">
                        <strong class="text-danger" id="end_date_error"></strong>
                    </div>
                    <div class="form-group">
                        <label for=""> الحد الأقصى للاستخدام </label>
                        <input type="number" class="form-control" name="limit" required value="{{ old('limit') }}"
                            id="coupon_limit">
                        <strong class="text-danger" id="limit_error"></strong>
                    </div>

                    <div class="form-group">
                        <label for=""> حدد الحالة </label>
                        <select name="is_active" id="coupon_is_active" class="form-control" required>
                            <option value=""> -- حدد الحالة -- </option>
                            <option id="coupon_is_active_1" value="1" @selected(old('is_active') == 1)> مفعل </option>
                            <option id="coupon_is_active_0" value="0" @selected(old('is_active') == 0)> غير مفعل </option>
                        </select>
                        <strong class="text-danger" id="is_active_error"></strong>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"> رجوع </button>
                        <button type="submit" class="btn btn-primary"> حفظ البيانات </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
