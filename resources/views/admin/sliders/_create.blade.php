<!-- Modal -->
<div class="modal fade" id="createslider" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel"> اضافة بانر </h1>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Validation Errors -->
                <div class="alert alert-danger" id='error_div' style="display: none;">
                    <ul id="error_list">
                    </ul>
                </div>
                <form action="{{ route('dashboard.slider.store') }}" id="createslider_form" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="image"> الصورة </label>
                        <input type="file" class="form-control" id="single-image" name="file_name" value="{{ old('file_name') }}">
                        <strong class="text-danger" id="image_error"></strong>
                    </div>
                    <div class="form-group">
                        <label for="note_ar"> الوصف بالعربي </label>
                        <input type="text" class="form-control" id="note_ar" name="note[ar]"
                            value="{{ old('note[ar]') }}">
                        <strong class="text-danger" id="note_ar_error"></strong>
                    </div>
                    <div class="form-group">
                        <label for="note_en"> الوصف بالانجليزية </label>
                        <input type="text" class="form-control" id="note_en" name="note[en]"
                            value="{{ old('note[en]') }}">
                        <strong class="text-danger" id="note_en_error"></strong>
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
