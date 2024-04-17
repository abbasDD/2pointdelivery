<table class="table table-striped">
    <thead class="thead-primary">
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Account Type</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($clients as $client)
            <tr>
                <td>{{ $client->id }}</td>
                <td>{{ $client->first_name }}</td>
                <td>{{ $client->account_type }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

{{ $clients->links() }}
