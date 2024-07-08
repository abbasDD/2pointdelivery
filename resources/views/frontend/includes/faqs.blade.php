<section class="py-5 bg-light-gray">
    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="heading">
                    <h6>Need help?</h6>
                    <h2>Frequently Asked Questions</h2>
                    <p>Here are some of our FAQs</p>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-lg-12">
                <div class="accordion" id="accordionFAQs">
                    {{-- If $faqs are not empty and loop thorugh --}}
                    @forelse($faqs as $faq)
                        <div class="card">
                            <h2 class="card-heading" id="faq_{{ $faq->id }}">
                                <a class="card-link py-0 collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse{{ $faq->id }}" aria-expanded="true"
                                    aria-controls="collapse{{ $faq->id }}">
                                    {{ $faq->question }}
                                </a>
                            </h2>
                            <div id="collapse{{ $faq->id }}" class="collapse"
                                aria-labelledby="faq_{{ $faq->id }}" data-bs-parent="#accordionFAQs">
                                <div class="card-body">
                                    {!! $faq->answer !!}
                                </div>
                            </div>
                        </div>
                    @empty
                        {{-- <p>No FAQs Found</p> --}}
                        <div class="accordion-item mb-3">
                            <h2 class="accordion-header" id="heading3">
                                <button class="accordion-button py-0 collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse3" aria-expanded="true" aria-controls="collapse3">
                                    How many items are include in the price of delivery?
                                </button>
                            </h2>
                            <div id="collapse3" class="accordion-collapse collapse" aria-labelledby="heading3"
                                data-bs-parent="#accordionFAQs">
                                <div class="accordion-body">
                                    2 items are included in the price of delivery with our 24/7 delivery service, with
                                    additional items costing £5 per item.
                                </div>
                            </div>
                        </div>
                    @endforelse


                </div>
            </div>
        </div>
    </div>
</section>
