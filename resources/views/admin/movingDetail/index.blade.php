<section class="section p-0">
    <div class="container-fluid">
        <div class="section-header mb-2">
            <div class="d-flex justify-content-between">
                <h4 class="mb-0">Moving Details</h4>
                <a href="{{ route('admin.movingDetail.create') }}" class="btn btn-sm btn-primary"><i
                        class="fas fa-plus"></i>
                    Add New</a>
            </div>
        </div>
        <div class="section-body">
            <div id="roomsTable">
                @include('admin.movingDetail.partials.list')
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
                    $('#roomsTable').html(data);
                }
            });
        }
    });
</script>
