@extends('admin.layouts.app')

@section('title', 'Helpers')

@section('content')

    <section class="section">
        <div class="container-fluid">
            <div class="section-header mb-2">
                <div class="d-flex justify-content-between">
                    <h4 class="mb-0">Requested Helpers</h4>
                </div>
            </div>
            <div class="section-body">
                <div id="helperTable">
                    @include('admin.helpers.partials.requested_list')
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
                    url: "/helpers?page=" + page,
                    success: function(data) {
                        $('#helperTable').html(data);
                    }
                });
            }
        });
    </script>

@endsection
