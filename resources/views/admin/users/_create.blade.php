<!-- Modal -->
<div class="modal fade" id="createuser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel"> اضافة مستخدم </h1>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">ـ
                <!-- Validation Errors -->
                <div class="alert alert-danger" id='error_div' style="display: none;">
                    <ul id="error_list">
                    </ul>
                </div>
                <form action="" id="createuser_form" method="post">
                    @csrf
                    <div class="form-group">
                        <label for=""> الاسم </label>
                        <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                        <strong class="text-danger" id="name_error"></strong>
                    </div>
                    <div class="form-group">
                        <label for=""> البريد الالكتروني </label>
                        <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                        <strong class="text-danger" id="email_error"></strong>
                    </div>
                    <div class="form-group">
                        <label for=""> رقم الهاتف </label>
                        <input type="number" class="form-control" name="mobile" value="{{ old('mobile') }}">
                        <strong class="text-danger" id="mobile_error"></strong>
                    </div>
                    <div class="form-group">
                        <label for=""> كلمة المرور  </label>
                        <input type="password" class="form-control" name="password" value="{{ old('password') }}">
                        <strong class="text-danger" id="password_error"></strong>
                    </div>
                    @livewire('general.count-gover-city-comp')
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
