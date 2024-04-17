@extends('admin.layouts.app')

@section('title', 'Sub Admins')

@section('content')

    <section class="section">
        <div class="container">
            <div class="section-header mb-2">
                <div class="d-flex justify-content-between">
                    <h4>Sub Admins</h4>
                    <div class="dropdown mr-2">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            All
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#">Completed</a>
                            <a class="dropdown-item" href="#">Cancelled</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="section-body">
                <table class="table table-striped">
                    <thead class="thead-primary">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Full Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Type</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">1</th>
                            <td>Sub Admin</td>
                            <td>subadmin@gmail.com</td>
                            <td>sub</td>
                            <td><span class="badge bg-success">Active</span></td>
                            <td><a href="#" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a></td>
                        </tr>

                    </tbody>
                </table>

            </div>
        </div>
    </section>

@endsection
