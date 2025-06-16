<div class="email-app-menu col-md-5 card d-none d-lg-block">
    <div class="text-center form-group form-group-compose">
        <button type="button" class="my-1 btn btn-danger btn-block"><i class="ft-mail"></i>
            Compose</button>
    </div>
    <h6 class="mb-1 text-muted text-bold-500">Messages</h6>
    <div class="list-group list-group-messages">
        <a href="#" class="border-0 list-group-item active">
            <i class="mr-1 ft-inbox"></i> Inbox
            <span class="float-right badge badge-secondary badge-pill">{{ $counts_not_read }}</span>
        </a>
        <a href="#" class="border-0 list-group-item list-group-item-action"><i
                class="mr-1 la la-paper-plane-o"></i> Sent</a>
        <a href="#" class="border-0 list-group-item list-group-item-action"><i class="mr-1 ft-file"></i> Draft</a>
        <a href="#" class="border-0 list-group-item list-group-item-action"><i class="mr-1 ft-star"></i>
            Starred<span class="float-right badge badge-danger badge-pill">3</span> </a>
        <a href="#" class="border-0 list-group-item list-group-item-action"><i class="mr-1 ft-trash-2"></i>
            Trash</a>
    </div>
    <h6 class="mt-1 mb-1 text-muted text-bold-500">Labels</h6>
    <div class="list-group list-group-messages">
        <a href="#" class="border-0 list-group-item list-group-item-action">
            <i class="mr-1 ft-circle warning"></i> Work
            <span class="float-right badge badge-warning badge-pill">5</span>
        </a>
        <!--<a href="#" class="border-0 list-group-item list-group-item-action"><i class="mr-1 ft-circle danger"></i> Family</a>-->
        <!--<a href="#" class="border-0 list-group-item list-group-item-action"><i class="mr-1 ft-circle primary"></i> Friends</a>-->
        <a href="#" class="border-0 list-group-item list-group-item-action"><i class="mr-1 ft-circle success"></i>
            Private <span class="float-right badge badge-success badge-pill">3</span> </a>
    </div>
</div>
