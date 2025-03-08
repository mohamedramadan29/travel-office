<!-- Modal -->
<div class="modal fade" id="createbrand" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel"> اضافة علامة تجارية </h1>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('dashboard.brands.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for=""> اسم العلامة التجارية </label>
                        <input type="text" class="form-control" name="name[ar]" required
                            value="{{ old('name[ar]') }}">
                    </div>
                    <div class="form-group">
                        <label for=""> اسم العلامة باللغة الانجليزية </label>
                        <input type="text" class="form-control" name="name[en]" required
                            value="{{ old('name[en]') }}">
                    </div>
                    <div class="form-group">
                        <label for=""> اللوجو </label>
                        <input type="file" class="form-control" name="logo" id="single-image">
                    </div>
                    <div class="form-group">
                        <label for=""> حدد الحالة </label>
                        <select name="status" id="" class="form-control" required>
                            <option value=""> -- حدد الحالة -- </option>
                            <option value="1" @selected(old('status') == 1)> مفعل </option>
                            <option value="0" @selected(old('status') == 0)> غير مفعل </option>
                        </select>
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
