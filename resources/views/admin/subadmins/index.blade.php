@extends('admin.layouts.app')

@section('title', 'Sub Admins')

@section('content')

    <section class="section">
        <div class="container">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button class="btn btn-sm" type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <div class="section-header mb-2">
                <div class="d-flex justify-content-between my-3">
                    <h4 class="mb-0">Sub Admins</h4>
                    <a href="{{ route('admin.subadmin.create') }}" class="btn btn-primary btn-sm">Add</a>
                </div>
            </div>
            <div class="section-body">
                <table class="table table-striped">
                    <thead class="thead-primary">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">First Name</th>
                            <th scope="col">Last Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Type</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subadmins as $subadmin)
                            <tr>
                                <th scope="row">{{ $subadmin->id }}</th>
                                <td>{{ $subadmin->first_name }}</td>
                                <td>{{ $subadmin->last_name }}</td>
                                <td>{{ $subadmin->email }}</td>
                                <td>{{ $subadmin->admin_type }}</td>
                                <td>
                                    @if ($subadmin->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.subadmin.edit', $subadmin->id) }}"
                                        class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>

            </div>
        </div>
    </section>

@endsection
