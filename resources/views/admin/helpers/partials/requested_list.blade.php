<table class="table table-striped">
    <thead class="thead-primary">
        <tr>
            <th>ID</th>
            <th>Profile Image</th>
            <th>Email</th>
            <th>Full Name</th>
            <th>Account Type</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($helpers as $helper)
            <tr id="helper_{{ $helper->id }}">
                <td>{{ $helper->id }}</td>
                <td><img src="{{ $helper->thumbnail ? asset('images/users/thumbnail/' . $helper->thumbnail) : asset('images/users/default.png') }}"
                        alt="Profile Image" width="50">
                </td>
                <td>{{ $helper->user->email }}</td>
                <td>{{ $helper->first_name . ' ' . $helper->last_name }}</td>
                <td>{{ $helper->company_enabled ? 'Company' : 'Individual' }}</td>
                <td>
                    {{-- View Route --}}
                    <a class="btn btn-sm btn-primary" href="{{ route('admin.helper.show', $helper->id) }}"><i
                            class="fa fa-eye"></i></a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center">No data found</td>
            </tr>
        @endforelse
    </tbody>
</table>


{{ $helpers->links() }}
