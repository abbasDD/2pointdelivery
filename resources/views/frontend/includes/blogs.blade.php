<section id="services">
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8 text-center">
                <div class="heading">
                    <h6>Read Our Blogs</h6>
                    <h2>Our Blogs</h2>
                    <p> Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                </div>
            </div>
        </div>
        <div>
            <div class="row mx-auto my-auto justify-content-center">
                @forelse ($blogs as $blog)
                    {{-- Service {{ $loop->iteration }} --}}
                    <div class="col-md-4">
                        <div class="card mx-2">
                            <div class="card-body">
                                <img src="{{ $blog->image ? asset('images/service_types/' . $blog->image) : asset('images/service_types/default.png') }}"
                                    alt="Image" height="200" class="w-100 mb-3">
                                <h5>{{ $blog->title }}</h5>
                                <h6 class="text-muted text-align-end">{{ $blog->author }}</h6>
                                <p>
                                    {{-- Show only first 150 character --}}
                                    {!! Str::limit($blog->body ?? '-', 150) !!}
                                </p>
                                {{-- Redirect to Booking --}}
                                <div class="arrow-button">
                                    <a href="#">
                                        <i class="fas fa-long-arrow-alt-right mr-2"></i> Read More
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-md-12 text-center">
                        <p>No Blog Found</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</section>

<script>
    let items = document.querySelectorAll('.carousel .carousel-item')

    items.forEach((el) => {
        const minPerSlide = 3;
        let next = el.nextElementSibling
        for (var i = 1; i < minPerSlide; i++) {
            if (!next) {
                // wrap carousel by using first child
                next = items[0]
            }
            let cloneChild = next.cloneNode(true)
            el.appendChild(cloneChild.children[0])
            next = next.nextElementSibling
        }
    })
</script>
