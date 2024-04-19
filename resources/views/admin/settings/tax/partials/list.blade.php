<table class="table table-striped">
    <thead class="thead-primary">
        <tr>
            <th>ID</th>
            <th>Country</th>
            <th>State</th>
            <th>Tax Type</th>
            <th>Tax Rate</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($taxCountries as $taxCountry)
            <tr>
                <td>{{ $taxCountry->id }}</td>
                <td>{{ $taxCountry->country }}</td>
                <td>{{ $taxCountry->state }}</td>
                <td>{{ $taxCountry->tax_type }}</td>
                <td>{{ $taxCountry->tax_rate }}</td>
                <td>{{ $taxCountry->is_active }}</td>
                <td><a class="btn btn-sm btn-primary" href="{{ route('admin.taxSetting.edit', $taxCountry->id) }}"><i
                            class="fa fa-edit"></i>"</a></td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center">No data found</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{ $taxCountries->links() }}
