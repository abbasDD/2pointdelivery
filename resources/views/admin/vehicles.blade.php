@extends('admin.layouts.app')

@section('title', 'Vehicles')

@section('content')

    <section class="section">
        <div class="container">
            <div class="section-header mb-2">
                <div class="d-flex justify-content-between">
                    <h4>Vehicles</h4>
                    <button class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Add Vehicle</button>
                </div>
            </div>
            <div class="section-body">
                <table class="table table-striped">
                    <thead class="thead-primary">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Vehicle Name</th>
                            <th scope="col">Available For</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">1</th>
                            <td>Bike</td>
                            <td>Delivery</td>
                            <td><a href="#" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">2</th>
                            <td>Truck</td>
                            <td>Delivery, Moving</td>
                            <td><a href="#" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a></td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </section>

@endsection
