@extends('frontend.layouts.app')

@section('title', 'Topic Question')

@section('content')

    {{-- Header Section  --}}
    <section class="py-5 bg-light-gray">
        <div class="container my-5">
            <div class="row align-items-center">
                <div class="col-md-8 px-5">
                    <h3 class="mb-1">How can we help you?</h3>
                    <p class="mb-3">At 2 Point, we operate around the clock to serve you whenever you need us. Day or
                        night, our delivery services are at your disposal, ensuring convenience at every hour.</p>
                    <form>
                        <div class="form-group">
                            <div class="input-group">
                                <input type="text" class="form-control" id="search" placeholder="Search your topic">
                                <div class="input-group-append">
                                    <span class="input-group-text h-100 bg-primary text-white"><i
                                            class="fa fa-search"></i></span>
                                </div>
                            </div>
                            <div id="search-results" class="list-group mt-1 w-100"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>


    {{-- All Questions --}}

    <section class="container my-5">
        <div class="row">
            <div class="col-lg-12">
                <div class="heading text-center">
                    <h2>{{ $topic->name ?? '2 Point Delivery' }}</h2>
                    <p>
                        {{ $topic->content ?? 'Here are some of our FAQs related to this topic' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="accordion" id="accordionFAQs">
            {{-- If $helpQuestions are not empty and loop thorugh --}}
            @forelse($helpQuestions as $helpQuestion)
                <div class="card">
                    <h2 class="card-heading" id="helpQuestion_{{ $helpQuestion->id }}">
                        <a class="card-link py-0 collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse{{ $helpQuestion->id }}" aria-expanded="true"
                            aria-controls="collapse{{ $helpQuestion->id }}">
                            {{ $helpQuestion->question }}
                        </a>
                    </h2>
                    <div id="collapse{{ $helpQuestion->id }}" class="collapse"
                        aria-labelledby="helpQuestion_{{ $helpQuestion->id }}" data-bs-parent="#accordionFAQs">
                        <div class="card-body">
                            {{ $helpQuestion->answer }}
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
    </section>


    {{-- Still looking for help ? --}}
    @include('frontend.includes.ctahelp')

    {{-- JavaScript --}}


    <script>
        $('#search').keyup(function() {
            var query = $(this).val();

            var base_url = "{{ url('/') }}";

            $('#search-results').empty();

            if (query.length > 0) {
                $.ajax({
                    url: base_url + '/topic-search?query=' + query, // Change this to your search endpoint
                    method: 'GET',
                    success: function(data) {
                        $('#search-results').empty();
                        console.log(data);
                        if (data.length > 0) {
                            data.forEach(function(item) {
                                $('#search-results').append(
                                    '<a href="' + base_url + '/topic/' + item.id +
                                    '" class="list-group-item list-group-item-action">' +
                                    item.name + '</a>'
                                );
                            });
                        } else {
                            $('#search-results').append(
                                '<div class="list-group-item">No results found</div>'
                            );
                        }
                    },
                    error: function() {
                        $('#search-results').empty();
                        $('#search-results').append(
                            '<div class="list-group-item">Error fetching results</div>'
                        );
                    }
                });
            } else {
                $('#search-results').empty();
            }
        });
    </script>


@endsection
