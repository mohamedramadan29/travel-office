 <!-- Modal -->
 <div class="text-left modal fade" id="change_price_{{ $gov->id }}" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
     <div class="modal-dialog" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h4 class="modal-title" id="myModalLabel1">
                     تغير سعر الشحن لمحافظة :: {{ $gov->getTranslation('name', 'ar') }}
                 </h4>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body">
                 <h5> تغير سعر الشحن </h5>
                 <form action="{{ route('dashboard.world.GovernrateChangePrice', $gov->id) }}" method="post">
                     @csrf
                     <input type="number" step="0.01" name="price" class="form-control"
                         value="{{ $gov->ShippingPrice->price ?? 0 }}">

                     <hr />
             </div>
             <div class="modal-footer">
                 <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">
                     رجوع
                 </button>
                 <button type="submit" class="btn btn-outline-primary">
                     تغير السعر
                 </button>
             </div>
             </form>
         </div>
     </div>
 </div>
