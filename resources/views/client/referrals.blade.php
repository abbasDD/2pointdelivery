@extends('client.layouts.app')

@section('title', 'Referrals')

@section('content')

    <section class="section p-0">
        <div class="container">
            <div class="section-header mb-2">
                <div class="d-flex justify-content-between">
                    <h4 class="mb-0">Referrals History</h4>
                    {{-- Show Referral Code with copy button --}}
                    @if (isset(auth()->user()->referral_code))
                        <div class="d-flex align-items-center">
                            <p class="mb-0 fs-18" onclick="copyToClipboard('{{ auth()->user()->referral_code }}')">
                                <span class="badge bg-primary cursor-pointer">
                                    <i class="fas fa-copy"></i>
                                    Click to copy
                                </span>
                            </p>
                        </div>
                    @endif
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
                            {{-- <th scope="col">Action</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($referrals as $referral)
                            <tr>
                                <th scope="row">1</th>
                                <td>{{ $referral->email }}</td>
                                <td>{{ $referral->user_type }}</td>
                                <td>200</td>
                                <td><span class="badge bg-success">Awarded</span></td>
                                {{-- <td><a href="#" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a></td> --}}
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No data found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </section>

    <script>
        // Copy to clip board
        function copyToClipboard(referralCode) {

            // Create Refferal Link
            var url = "{{ url('/') }}" + "/client/register?ref=" + referralCode;

            navigator.clipboard.writeText(url);
            // Trigger Notification
            triggerToast('Success', 'Referral Link copied to clipboard');

        }
    </script>

@endsection
