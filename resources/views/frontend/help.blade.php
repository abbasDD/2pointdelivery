@extends('frontend.layouts.app')

@section('title', 'Help')

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


    {{-- All Topics --}}

    <section class="container my-5">
        <div class="row">
            <div class="col-lg-12">
                <div class="heading text-center">
                    <h2>All topics</h2>
                    <p>
                        Don't worry you can still apply as a helper!
                    </p>
                </div>
            </div>
        </div>
        <div class="row topic-list">
            @forelse ($helpTopics as $helpTopic)
                <div class="col-md-6 p-2 item">
                    <a href="{{ route('topicQuestion', $helpTopic->id) }}">
                        <div class="bg-light-gray p-4">
                            <h5 class="mb-0">{{ $helpTopic->name }}</h5>
                            {{ $helpTopic->content ?? 'Read more' }}
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-md-6 p-2">
                    <div class="bg-light-gray p-4">
                        <h5 class="mb-0">No Topic Found</h5>
                        <p>We are still working on it</p>
                    </div>
                </div>
            @endforelse
        </div>
    </section>


    {{-- Still looking for help ? --}}
    @include('frontend.includes.ctahelp')

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
