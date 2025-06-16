<div class="card-content">
    @if ($contact)
        <div class="email-app-options card-body">
            <div class="row">
                <div class="col-md-6 col-12">
                    <div class="btn-group" role="group" aria-label="Basic example">

                        <button wire:click="replayMsg({{ $contact->id }})" type="button" class="btn btn-sm btn-outline-secondary" data-toggle="tooltip"
                            data-placement="top" data-original-title="Replay"><i
                                class="la la-reply-all"></i></button>

                        <button wire:click="deleteMsg({{ $contact->id }})" type="button"
                            class="btn btn-sm btn-outline-secondary" data-toggle="tooltip" data-placement="top"
                            data-original-title="Delete"><i class="ft-trash-2"></i></button>
                    </div>
                </div>
                <div class="text-right col-md-6 col-12">
                    ـ
                    <div class="ml-1 btn-group">
                        <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">More</button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#">Mark as unread</a>
                            <a class="dropdown-item" href="#">Mark as unimportant</a>
                            <a class="dropdown-item" href="#">Add star</a>
                            <a class="dropdown-item" href="#">Add to task</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Filter mail</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="email-app-title card-body">
            <h3 class="list-group-item-heading">{{ $contact['subject'] }}</h3>
        </div>

        <div class="media-list">
            <div id="headingCollapse1" class="p-0 card-header">
                <a data-toggle="collapse" href="#collapse1" aria-expanded="true" aria-controls="collapse1"
                    class="border-0 collapsed email-app-sender media bg-blue-grey bg-lighten-5">
                    <div class="pr-1 media-left">
                        <span class="avatar avatar-md">
                            <span
                                class="media-object rounded-circle text-circle bg-info">{{ $contact['name'][0] }}</span>
                        </span>
                    </div>
                    <div class="media-body w-100">
                        <h6 class="list-group-item-heading">{{ $contact['name'] }}</h6>
                        <p class="list-group-item-text">{{ $contact['subject'] }}
                            <span class="float-right text muted">{{ $contact['created_at']->diffForHumans() }}</span>
                        </p>
                    </div>
                </a>
            </div>
            <div id="collapse1" role="tabpanel" aria-labelledby="headingCollapse1" class="card-collapse collapse"
                aria-expanded="true">
                <div class="card-content">
                    <div class="card-body">
                        <p>{{ $contact['message'] }}</p>
                    </div>
                </div>
            </div>
            <div class="email-app-text-action card-body">
            </div>
        </div>
    @endif
</div>
