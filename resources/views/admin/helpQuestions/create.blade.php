@extends('admin.layouts.app')

@section('title', 'Help Questions')

@section('content')

    <section class="section p-0">
        <div class="container">
            <div class="section-header mb-2">
                <div class="d-flex justify-content-between">
                    <h4>Add Help Questions</h4>
                    <button type="button" class="btn btn-primary btn-sm" onclick="openAddTopicModal()">
                        Add Topic
                    </button>
                </div>
            </div>
            <div class="section-body">

                @if (count($helpTopics) > 0)
                    <form action="{{ route('admin.helpQuestion.store') }}" method="POST">
                        @csrf
                        @include('admin.helpQuestions.form')
                    </form>
                @else
                    {{-- Add topic button to open modal --}}
                    <div class="col-md-12 text-center">
                        <h3 class="mb-1">No Topic Found</h3>
                        <p>You need to add topic first</p>
                        <button type="button" class="btn btn-primary btn-block" onclick="openAddTopicModal()">
                            Add Topic
                        </button>
                    </div>
                @endif


                {{-- Modal to add topic --}}
                @include('admin.helpQuestions.partials.topic_modal')

            </div>
        </div>
    </section>

@endsection
