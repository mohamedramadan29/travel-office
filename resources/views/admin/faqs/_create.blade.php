<!-- Modal -->
<div class="modal fade" id="createfaq" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel"> اضافة اسئلة </h1>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Validation Errors -->
                <div class="alert alert-danger" id='error_div' style="display: none;">
                    <ul id="error_list">
                    </ul>
                </div>
                <form action="" id="createfaq_form" method="post">
                    @csrf
                    <div class="form-group">
                        <label for=""> السؤال بالعربية </label>
                        <input type="text" class="form-control" name="question[ar]"
                            value="{{ old('question[ar]') }}">
                        <strong class="text-danger" id="question_error"></strong>
                    </div>
                    <div class="form-group">
                        <label for=""> السؤال بالانجليزية </label>
                        <input type="text" class="form-control" name="question[en]"
                            value="{{ old('question[en]') }}">
                        <strong class="text-danger" id="question_error"></strong>
                    </div>
                    <div class="form-group">
                        <label for=""> الاجابة بالعربية </label>
                        <textarea name="answer[ar]" id="" cols="30" rows="10" class="form-control">{{ old('answer[ar]') }}</textarea>
                        <strong class="text-danger" id="answer_error"></strong>
                    </div>
                    <div class="form-group">
                        <label for=""> الاجابة بالانجليزية </label>
                        <textarea name="answer[en]" id="" cols="30" rows="10" class="form-control">{{ old('answer[en]') }}</textarea>
                        <strong class="text-danger" id="answer_error"></strong>
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
