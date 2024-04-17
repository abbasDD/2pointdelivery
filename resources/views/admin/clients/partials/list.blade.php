<table class="table table-striped">
    <thead class="thead-primary">
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Account Type</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($clients as $client)
            <tr>
                <td>{{ $client->id }}</td>
                <td>{{ $client->first_name }}</td>
                <td>{{ $client->account_type }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center">No data found</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{ $clients->links() }}
