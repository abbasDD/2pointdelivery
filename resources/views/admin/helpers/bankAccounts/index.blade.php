@extends('admin.layouts.app')

@section('title', 'Helper Bank Accounts')

@section('content')

    <section class="section p-0">
        <div class="container-fluid">
            <div class="section-header mb-2">
                <div class="d-flex justify-content-between">
                    <h4 class="mb-0">Helper Bank Accounts</h4>
                </div>
            </div>
            <div class="section-body">
                <div id="helperTable">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="thead-primary">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Helper</th>
                                    <th scope="col">Payment Method</th>
                                    <th scope="col">Account Name</th>
                                    <th scope="col">Account Number</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($helperBankAccounts as $helperBankAccount)
                                    <tr>
                                        <th scope="row">{{ $loop->index + 1 }}</th>
                                        <td>
                                            <a href="{{ route('admin.helper.show', $helperBankAccount->helper->id) }}">
                                                {{ $helperBankAccount->helper->first_name . ' ' . $helperBankAccount->helper->last_name }}
                                            </a>
                                        </td>
                                        <td>{{ $helperBankAccount->payment_method }}</td>
                                        <td>{{ $helperBankAccount->account_name }}</td>
                                        <td>{{ $helperBankAccount->account_number }}</td>
                                        <td>
                                            @if ($helperBankAccount->is_approved == 0)
                                                <a href="{{ route('admin.helper.BankAccount.approve', $helperBankAccount->id) }}"
                                                    class="btn btn-sm btn-primary"><i class="fas fa-check"></i></a>
                                                <a href="{{ route('admin.helper.BankAccount.reject', $helperBankAccount->id) }}"
                                                    class="btn btn-sm btn-danger"><i class="fas fa-times"></i></a>
                                            @else
                                                <a href="{{ route('admin.helper.show', $helperBankAccount->helper->id) }}"
                                                    class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a>
                                            @endif
                                        </td>
                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="6" class="text-center">No Data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection
