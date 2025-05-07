<!-- Modal -->
<div class="modal fade" id="editattribute" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel"> تعديل سمة للمنتج </h1>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <!-- Validation Errors -->
                <div class="alert alert-danger" id='error_div_edit_attribute' style="display: none;">
                    <ul id="error_list_edit_attribute">
                    </ul>
                </div>
                <form action="" id="editattributeform" method="post">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="attribute_id">
                    <div class="form-group">
                        <label for=""> اسم السمة </label>
                        <input type="text" class="form-control" name="name[ar]" id="AttributeNameAr">
                        <strong class="text-danger" id="name_error"></strong>
                    </div>
                    <div class="form-group">
                        <label for=""> اسم السمة باللغة الانجليزية </label>
                        <input type="text" class="form-control" name="name[en]" id="AttributeNameEn">
                        <strong class="text-danger" id="name_error"></strong>
                    </div>
                    <hr>
                    <div class="attributevaluescontainer">

                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <button class="btn btn-primary btn-sm add_more_attribute" type="button"
                                id="add_more_attribute"> <i class="la la-plus"></i> </button>
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
