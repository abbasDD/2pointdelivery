@extends('client.layouts.app')

@section('title', 'Referrals')

@section('content')

    <section class="section">
        <div class="container">
            <div class="section-header mb-2">
                <div class="d-flex justify-content-between">
                    <h4>Referrals History</h4>
                </div>
            </div>
            <div class="section-body">
                <table class="table table-striped">
                    <thead class="thead-primary">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">User</th>
                            <th scope="col">User Type</th>
                            <th scope="col">Points</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="6" class="text-center">No data found</td>
                        </tr>
                        {{-- <tr>
                            <th scope="row">1</th>
                            <td>@john123</td>
                            <td>Company Client</td>
                            <td>200</td>
                            <td><span class="badge bg-success">Completed</span></td>
                            <td><a href="#" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">2</th>
                            <td>@emma456</td>
                            <td>Individual Helper</td>
                            <td>100</td>
                            <td><span class="badge bg-warning">Pending</span></td>
                            <td><a href="#" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">3</th>
                            <td>@mark789</td>
                            <td>Individual Client</td>
                            <td>50</td>
                            <td><span class="badge bg-danger">Cancelled</span></td>
                            <td><a href="#" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">4</th>
                            <td>@sarah_12</td>
                            <td>Company Helper</td>
                            <td>150</td>
                            <td><span class="badge bg-info">Processing</span></td>
                            <td><a href="#" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">5</th>
                            <td>@alex456</td>
                            <td>Individual Helper</td>
                            <td>75</td>
                            <td><span class="badge bg-success">Completed</span></td>
                            <td><a href="#" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">6</th>
                            <td>@lisa_89</td>
                            <td>Company Client</td>
                            <td>120</td>
                            <td><span class="badge bg-warning">Pending</span></td>
                            <td><a href="#" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">7</th>
                            <td>@robertm</td>
                            <td>Individual Helper</td>
                            <td>90</td>
                            <td><span class="badge bg-danger">Cancelled</span></td>
                            <td><a href="#" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">8</th>
                            <td>@eva_99</td>
                            <td>Company Helper</td>
                            <td>180</td>
                            <td><span class="badge bg-info">Processing</span></td>
                            <td><a href="#" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">9</th>
                            <td>@mike_22</td>
                            <td>Individual Client</td>
                            <td>95</td>
                            <td><span class="badge bg-success">Completed</span></td>
                            <td><a href="#" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">10</th>
                            <td>@sammy</td>
                            <td>Company Client</td>
                            <td>110</td>
                            <td><span class="badge bg-warning">Pending</span></td>
                            <td><a href="#" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a></td>
                        </tr> --}}
                    </tbody>
                </table>

            </div>
        </div>
    </section>

@endsection
