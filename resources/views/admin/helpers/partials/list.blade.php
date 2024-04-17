<table class="table table-striped">
    <thead class="thead-primary">
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Account Type</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($helpers as $helper)
            <tr>
                <td>{{ $helper->id }}</td>
                <td>{{ $helper->first_name }}</td>
                <td>{{ $helper->account_type }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center">No data found</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{ $helpers->links() }}
