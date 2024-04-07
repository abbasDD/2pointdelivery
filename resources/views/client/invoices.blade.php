@extends('client.layouts.app')

@section('title', 'Invoices')

@section('content')

    <section class="section">
        <div class="container">
            <div class="section-header mb-2">
                <div class="d-flex justify-content-between">
                    <h4>Invoices History</h4>
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
                            <th scope="col">Company</th>
                            <th scope="col">Dropoff</th>
                            <th scope="col">Driver</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">1</th>
                            <td>ABC Transport</td>
                            <td>Big Ben, London, England</td>
                            <td>200$</td>
                            <td>@mdo</td>
                            <td><span class="badge bg-success">Completed</span></td>
                            <td><a href="#" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">2</th>
                            <td>XYZ Logistics</td>
                            <td>Westminster Abbey, London, England</td>
                            <td>100$</td>
                            <td>@fat</td>
                            <td><span class="badge bg-warning">Pending</span></td>
                            <td><a href="#" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">3</th>
                            <td>BestRide Inc.</td>
                            <td>Buckingham Palace, London, England</td>
                            <td>50$</td>
                            <td>@twitter</td>
                            <td><span class="badge bg-danger">Cancelled</span></td>
                            <td><a href="#" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">4</th>
                            <td>Swift Transport</td>
                            <td>The Shard, London, England</td>
                            <td>150$</td>
                            <td>@random</td>
                            <td><span class="badge bg-info">Processing</span></td>
                            <td><a href="#" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">5</th>
                            <td>Speedy Couriers</td>
                            <td>London Eye, London, England</td>
                            <td>75$</td>
                            <td>@johndoe</td>
                            <td><span class="badge bg-success">Completed</span></td>
                            <td><a href="#" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">6</th>
                            <td>London Transfers</td>
                            <td>Covent Garden, London, England</td>
                            <td>120$</td>
                            <td>@alice</td>
                            <td><span class="badge bg-warning">Pending</span></td>
                            <td><a href="#" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">7</th>
                            <td>CityLink Transport</td>
                            <td>Camden Market, London, England</td>
                            <td>90$</td>
                            <td>@bob</td>
                            <td><span class="badge bg-danger">Cancelled</span></td>
                            <td><a href="#" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">8</th>
                            <td>London Shuttles</td>
                            <td>Victoria and Albert Museum, London, England</td>
                            <td>180$</td>
                            <td>@eva</td>
                            <td><span class="badge bg-info">Processing</span></td>
                            <td><a href="#" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">9</th>
                            <td>Urban Express</td>
                            <td>Millennium Bridge, London, England</td>
                            <td>95$</td>
                            <td>@michael</td>
                            <td><span class="badge bg-success">Completed</span></td>
                            <td><a href="#" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">10</th>
                            <td>London Movers</td>
                            <td>Piccadilly Circus, London, England</td>
                            <td>110$</td>
                            <td>@samantha</td>
                            <td><span class="badge bg-warning">Pending</span></td>
                            <td><a href="#" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a></td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </section>

@endsection
