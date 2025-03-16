<!-- Modal -->
<div class="modal fade" id="createcoupon" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel"> اضافة كوبون </h1>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Validation Errors -->
                <div class="alert alert-danger" id='error_div' style="display: none;">
                    <ul id="error_list">
                    </ul>
                </div>
                <form action="" id="createcoupon_form" method="post">
                    @csrf
                    <div class="form-group">
                        <label for=""> الكود </label>
                        <input type="text" class="form-control" name="code" value="{{ old('code') }}">
                        <strong class="text-danger" id="code_error"></strong>
                    </div>
                    <div class="form-group">
                        <label for=""> النسبة المئوية للخصم </label>
                        <input type="number" class="form-control" name="discount_percentage"
                            value="{{ old('discount_percentage') }}">
                        <strong class="text-danger" id="discount_percentage_error"></strong>
                    </div>
                    <div class="form-group">
                        <label for=""> التاريخ البداية للخصم </label>
                        <input type="date" class="form-control" name="start_date" required
                            value="{{ old('start_date') }}">
                        <strong class="text-danger" id="start_date_error"></strong>
                    </div>
                    <div class="form-group">
                        <label for=""> التاريخ النهاية للخصم </label>
                        <input type="date" class="form-control" name="end_date" required
                            value="{{ old('end_date') }}">
                        <strong class="text-danger" id="end_date_error"></strong>
                    </div>
                    <div class="form-group">
                        <label for=""> الحد الأقصى للاستخدام </label>
                        <input type="number" class="form-control" name="limit" required value="{{ old('limit') }}">
                        <strong class="text-danger" id="limit_error"></strong>
                    </div>

                    <div class="form-group">
                        <label for=""> حدد الحالة </label>
                        <select name="is_active" id="" class="form-control" required>
                            <option value=""> -- حدد الحالة -- </option>
                            <option value="1" @selected(old('is_active') == 1)> مفعل </option>
                            <option value="0" @selected(old('is_active') == 0)> غير مفعل </option>
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
