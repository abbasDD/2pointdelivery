{{-- bank-accounts --}}
<div class="card mb-3">

    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h3 class="mb-0">Bank Accounts</h3>
            <button class="btn btn-primary btn-sm" onclick="openBankAccountModal()">Add Bank Account</button>
        </div>
        <div class="row">
            @forelse ($helperBankAccounts as $helperBankAccount)
                <div class="col-md-6">
                    {{-- Account Type --}}
                    <p class="mb-0"><span class="text-primary">Account Type:</span>
                        {{ $helperBankAccount->account_type }}
                    </p>
                    {{-- Account Number --}}
                    <p class="mb-0"><span class="text-primary">Account Number:</span>
                        {{ $helperBankAccount->account_number }}
                    </p>
                    {{-- Account Name --}}
                    <p class="mb-0"><span class="text-primary">Account Name:</span>
                        {{ $helperBankAccount->account_name }}
                    </p>
                    {{-- Status --}}
                    <p class="mb-0"><span class="text-primary">Status:</span>
                        {{ $helperBankAccount->is_approved == 1 ? 'Approved' : 'Pending' }}
                    </p>
                </div>
            @empty
                <div class="d-flex align-items-center justify-content-center">
                    <p>No Bank Accounts. Add one</p>
                </div>
            @endforelse
        </div>
    </div>

</div>

{{-- bankAccountModal --}}
<div class="modal fade" id="bankAccountModal" tabindex="-1" role="dialog" aria-labelledby="bankAccountModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bankAccountModalLabel">Add Bank Account</h5>
            </div>
            <div class="modal-body">

                <form action="{{ route('helper.wallet.addBankAccount') }}" method="POST">
                    @csrf

                    <div class="form-group mb-3">
                        <select class="form-control" id="accountType" name="account_type" required>
                            <option value="" selected disabled>Select Account Type</option>
                            <option value="paypal">Paypal</option>
                            <option value="stripe">Stripe</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <input id="accountName" name="account_name" type="text" class="form-control"
                            placeholder="Account Name" required>
                    </div>

                    <div class="form-group mb-3">
                        <input id="accountNumber" name="account_number" type="text" class="form-control"
                            placeholder="Account Number/Email" required>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add Bank Account</button>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>

<script>
    // openBankAccountModal
    function openBankAccountModal() {
        $('#bankAccountModal').modal('show');
    }
</script>
