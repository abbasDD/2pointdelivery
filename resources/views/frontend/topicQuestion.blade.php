@extends('frontend.layouts.app')

@section('title', 'Topic Question')

@section('content')


    {{-- All Questions --}}

    <section class="container my-5">
        {{-- <div class="row">
            <div class="col-lg-12">
                <div class="heading text-center">
                    <h2>{{ $helpTopic->name ?? '2 Point Delivery' }}</h2>
                    <p>
                        {{ $helpTopic->content ?? 'Here are some of our FAQs related to this topic' }}
                    </p>
                </div>
            </div>
        </div> --}}

        <div class="row mt-4">
            <div class="col-lg-4">

                {{-- If $helpQuestionList are not empty and loop thorugh --}}
                @forelse($helpQuestionList as $helpQuestionItem)
                    <div class="{{ $helpQuestionItem->id == $helpQuestion->id ? 'bg-primary' : 'bg-light-gray' }} p-4 mb-2">
                        <a class="{{ $helpQuestionItem->id == $helpQuestion->id ? 'text-white' : '' }}"
                            href="{{ route('topicQuestion', $helpQuestionItem->id) }}">
                            <h6 class="mb-0"><i class="fa fa-file-alt"></i> {{ $helpQuestionItem->question }}</h6>
                        </a>
                    </div>
                @empty
                    <p>No FAQs Found</p>
                @endforelse
            </div>

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h4>{{ $helpQuestion->question ?? '2 Point Delivery' }}</h4>
                        <p>{!! $helpQuestion->answer !!}</p>

                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
