<!-- Modal -->
<div class="modal fade" id="replay-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel"> الرد على الرسالة </h1>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" wire:click.prevent="submitreplayMsg" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for=""> البريد الالكتروني  </label>
                        <input type="text" class="form-control" name="email" wire:model='email' disabled readonly>
                    </div>
                    <div class="form-group">
                        <label for=""> عنوان الرسالة  </label>
                        <input type="text" class="form-control" name="subject" wire:model='subject' disabled readonly>
                    </div>
                    <div class="form-group">
                        <label for=""> الرسالة </label>
                        <textarea name="message" id="" class="form-control" wire:model='replaymessage' required></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"> رجوع </button>
                        <button type="submit" class="btn btn-primary"> الرد على الرسالة </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
