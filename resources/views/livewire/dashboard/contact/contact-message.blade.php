<div class="p-0 email-app-list-wraper col-md-7 card">
    <div class="email-app-list">
        <div class="card-body chat-fixed-search">
            <fieldset class="pb-1 m-0 form-group position-relative has-icon-left">
                <input type="text" class="form-control" id="iconLeft4" wire:model.live="search" placeholder="Search email">
                <div class="form-control-position">
                    <i class="ft-search"></i>
                </div>
            </fieldset>
        </div>
        <div id="users-list" class="list-group">
            <div class="users-list-padding media-list">
                @forelse ($contacts as $contact)
                    <a href="#" @if($contact->id == $openMessageid) style="background-color: #f5f5f5;" @endif wire:click="showMessage({{ $contact->id }})" class="border-0 media">
                        <div class="pr-1 media-left">
                            <span class="avatar avatar-md">
                                <span
                                    class="media-object rounded-circle text-circle bg-info">{{ $contact->name[0] }}</span>
                            </span>
                        </div>
                        <div class="media-body w-100">
                            <h6 class="list-group-item-heading text-bold-500">{{ $contact->name }}
                                <span class="float-right">
                                    <span
                                        class="font-small-2 primary">{{ $contact->created_at->diffForHumans() }}</span>
                                </span>
                            </h6>
                            <p class="mb-0 list-group-item-text text-truncate text-bold-600">{{ $contact->subject }}</p>
                            <p class="mb-0 list-group-item-text">
                                <span class="float-right primary">
                                    <span class="mr-1 badge badge-{{ $contact->is_read ? 'success' : 'danger' }}">{{ $contact->is_read ? 'Read' : 'Unread' }}
                                    </span>
                                </span>
                            </p>
                        </div>
                    </a>
                @empty
                    <p> لا  يوجد رسائل في الوقت الحالي  </p>
                @endforelse
                {{ $contacts->links() }}
            </div>
        </div>
    </div>
</div>
