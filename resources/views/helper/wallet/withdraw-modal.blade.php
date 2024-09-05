{{-- Withdraw Modal --}}
<div class="modal fade" id="withdrawModal" tabindex="-1" role="dialog" aria-labelledby="withdrawModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="withdrawModalLabel">Withdraw</h5>
            </div>
            <div class="modal-body">
                <p>Withdraw Amount</p>
                <form action="{{ route('helper.wallet.withdrawRequest') }}" method="POST">
                    @csrf
                    {{-- Select Bank from Bank Accounts --}}
                    <div class="form-group mb-3">
                        <select class="form-control" id="bank_account_id" name="bank_account_id" required>
                            <option value="" selected disabled>Select Account Type</option>
                            @foreach ($helperBankAccounts as $helperBankAccount)
                                <option value="{{ $helperBankAccount->id }}">{{ $helperBankAccount->payment_method }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <input id="withdrawAmount" name="withdraw_amount" type="number" class="form-control"
                            placeholder="Enter amount" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">Withdraw</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openWithdrawModal() {
        // withdrawAmount
        $('#withdrawAmount').val({{ $statistic['available'] }});

        $('#withdrawModal').modal('show');
    }
</script>
