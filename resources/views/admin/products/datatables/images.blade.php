<div id="carouselExampleControls_{{ $product['id'] }}" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
        @foreach ($product->images as $key => $image)
            <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                <img class="d-block w-100" src="{{ asset('uploads/products/' . $image->file_name) }}" alt="First slide">
            </div>
        @endforeach
    </div>
    <a class="carousel-control-next" href="#carouselExampleControls_{{ $product['id'] }}" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
    <a class="carousel-control-prev" href="#carouselExampleControls_{{ $product['id'] }}" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>

</div>
