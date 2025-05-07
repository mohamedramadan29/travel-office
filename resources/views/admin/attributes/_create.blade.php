<!-- Modal -->
<div class="modal fade" id="createattribute" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel"> اضافة سمة للمنتج </h1>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Validation Errors -->
                <div class="alert alert-danger" id='error_div' style="display: none;">
                    <ul id="error_list">
                    </ul>
                </div>
                <form action="{{ route('dashboard.attributes.store') }}" id="createattribute" method="post">
                    @csrf
                    <div class="form-group">
                        <label for=""> اسم السمة </label>
                        <input type="text" class="form-control" name="name[ar]" value="{{ old('name[ar]') }}">
                        <strong class="text-danger" id="name_error"></strong>
                    </div>
                    <div class="form-group">
                        <label for=""> اسم السمة باللغة الانجليزية </label>
                        <input type="text" class="form-control" name="name[en]" value="{{ old('name[en]') }}">
                        <strong class="text-danger" id="name_error"></strong>
                    </div>
                    <hr>
                    <div class="row attribute_values_row d-flex align-items-center" >
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label for=""> اسم المتغير باللغة العربية </label>
                                <input type="text" class="form-control" name="value[1][ar]"
                                    value="{{ old('value[1][ar]') }}">
                                <strong class="text-danger" id="error_value"></strong>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label for=""> اسم المتغير باللغة الانجليزية </label>
                                <input type="text" class="form-control" name="value[1][en]"
                                    value="{{ old('value[1][en]') }}">
                                <strong class="text-danger" id="error_value"></strong>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <button disabled type="button" class="btn btn-danger btn-sm remove"> <i class="la la-close"></i> </button>
                        </div>
                        <br>
                        <br>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <button class="btn btn-primary btn-sm" type="button" id="add_more"> <i class="la la-plus"></i> </button>
                        </div>
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
