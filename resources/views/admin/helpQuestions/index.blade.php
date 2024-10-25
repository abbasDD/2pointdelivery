@extends('admin.layouts.app')

@section('title', 'Help Questions')

@section('content')

    <section class="section p-0">
        <div class="container-fluid">
            <div class="section-header mb-2">
                <div class="d-flex justify-content-between">
                    <h4 class="mb-0">Help Questions</h4>
                    <a href="{{ route('admin.helpQuestion.create') }}" class="btn btn-sm btn-primary"><i
                            class="fas fa-plus"></i> Add
                        New</a>
                </div>
            </div>
            <div class="section-body">
                <div id="clientTable">
                    @include('admin.helpQuestions.partials.list')
                </div>
            </div>
        </div>
    </section>

    <script>
        $(document).ready(function() {
            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                fetch_data(page);
            });

            function fetch_data(page) {
                $.ajax({
                    url: "/clients?page=" + page,
                    success: function(data) {
                        $('#clientTable').html(data);
                    }
                });
            }
        });
    </script>

@endsection
