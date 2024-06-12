@extends('client.layouts.app')

@section('title', 'Orders')

@section('content')

    <section class="section p-0">
        <div class="container">
            <div class="section-header mb-2">
                <div class="d-flex justify-content-between">
                    <h4>Orders History</h4>
                </div>
            </div>
            <div class="section-body">
                <table class="table table-striped">
                    <thead class="thead-primary">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Pickup</th>
                            <th scope="col">Dropoff</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Driver</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">1</th>
                            <td>London Bridge, London, England</td>
                            <td>Big Ben, London, England</td>
                            <td>200$</td>
                            <td>@mdo</td>
                            <td><span class="badge bg-success">Completed</span></td>
                            <td><a href="{{ route('booking_detail') }}" class="btn btn-sm btn-primary"><i
                                        class="fas fa-eye"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">2</th>
                            <td>Hyde Park, London, England</td>
                            <td>Westminster Abbey, London, England</td>
                            <td>100$</td>
                            <td>@fat</td>
                            <td><span class="badge bg-warning">Pending</span></td>
                            <td><a href="{{ route('booking_detail') }}" class="btn btn-sm btn-primary"><i
                                        class="fas fa-eye"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">3</th>
                            <td>Tower of London, London, England</td>
                            <td>Buckingham Palace, London, England</td>
                            <td>50$</td>
                            <td>@twitter</td>
                            <td><span class="badge bg-danger">Cancelled</span></td>
                            <td><a href="{{ route('booking_detail') }}" class="btn btn-sm btn-primary"><i
                                        class="fas fa-eye"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">4</th>
                            <td>St. Paul's Cathedral, London, England</td>
                            <td>The Shard, London, England</td>
                            <td>150$</td>
                            <td>@random</td>
                            <td><span class="badge bg-info">Processing</span></td>
                            <td><a href="{{ route('booking_detail') }}" class="btn btn-sm btn-primary"><i
                                        class="fas fa-eye"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">5</th>
                            <td>The British Museum, London, England</td>
                            <td>London Eye, London, England</td>
                            <td>75$</td>
                            <td>@johndoe</td>
                            <td><span class="badge bg-success">Completed</span></td>
                            <td><a href="{{ route('booking_detail') }}" class="btn btn-sm btn-primary"><i
                                        class="fas fa-eye"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">6</th>
                            <td>Trafalgar Square, London, England</td>
                            <td>Covent Garden, London, England</td>
                            <td>120$</td>
                            <td>@alice</td>
                            <td><span class="badge bg-warning">Pending</span></td>
                            <td><a href="{{ route('booking_detail') }}" class="btn btn-sm btn-primary"><i
                                        class="fas fa-eye"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">7</th>
                            <td>Kensington Palace, London, England</td>
                            <td>Camden Market, London, England</td>
                            <td>90$</td>
                            <td>@bob</td>
                            <td><span class="badge bg-danger">Cancelled</span></td>
                            <td><a href="{{ route('booking_detail') }}" class="btn btn-sm btn-primary"><i
                                        class="fas fa-eye"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">8</th>
                            <td>Regent's Park, London, England</td>
                            <td>Victoria and Albert Museum, London, England</td>
                            <td>180$</td>
                            <td>@eva</td>
                            <td><span class="badge bg-info">Processing</span></td>
                            <td><a href="{{ route('booking_detail') }}" class="btn btn-sm btn-primary"><i
                                        class="fas fa-eye"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">9</th>
                            <td>St. James's Park, London, England</td>
                            <td>Millennium Bridge, London, England</td>
                            <td>95$</td>
                            <td>@michael</td>
                            <td><span class="badge bg-success">Completed</span></td>
                            <td><a href="{{ route('booking_detail') }}" class="btn btn-sm btn-primary"><i
                                        class="fas fa-eye"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">10</th>
                            <td>Leicester Square, London, England</td>
                            <td>Piccadilly Circus, London, England</td>
                            <td>110$</td>
                            <td>@samantha</td>
                            <td><span class="badge bg-warning">Pending</span></td>
                            <td><a href="{{ route('booking_detail') }}" class="btn btn-sm btn-primary"><i
                                        class="fas fa-eye"></i></a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

@endsection
