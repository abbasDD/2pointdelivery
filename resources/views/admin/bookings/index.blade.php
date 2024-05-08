@extends('admin.layouts.app')

@section('title', 'Bookings')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-3">
        <h3>Bookings</h3>
    </div>
    <div class="dataTable">
        @include('admin.bookings.partials.list')
    </div>

@endsection

<script>
    $(document).ready(function() {
        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            fetchUsers(page);
        });
    });

    function fetchUsers(page) {
        var base_url = "{{ url('/') }}";
        $.ajax({
            url: base_url + '/admin/bookings?page=' + page,
            success: function(data) {
                $('#dataTable').html(data);
            }
        });
    }
</script>
