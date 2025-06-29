<div id="bankAccountsContainer">
    <div class="d-flex justify-content-end mb-3">
        <button type="button" id="addBankAccount" class="btn btn-success">
            <i class="fas fa-plus me-2"></i>افزودن حساب بانکی
        </button>
    </div>
    @php
        $oldBankAccounts = old('bank_accounts');
        $accounts = $oldBankAccounts ?: ($person->bankAccounts ?? []);
    @endphp

    @if($accounts && count($accounts))
        @foreach($accounts as $index => $account)
            <div class="bank-account-row">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">نام بانک</label>
                            <input type="text" name="bank_accounts[{{ $index }}][bank_name]" class="form-control"
                                   value="{{ $account['bank_name'] ?? ($account->bank_name ?? '') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">شماره حساب</label>
                            <input type="text" name="bank_accounts[{{ $index }}][account_number]" class="form-control" dir="ltr"
                                   value="{{ $account['account_number'] ?? ($account->account_number ?? '') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">شماره کارت</label>
                            <input type="text" name="bank_accounts[{{ $index }}][card_number]" class="form-control card-number" maxlength="19" dir="ltr"
                                   value="{{ $account['card_number'] ?? ($account->card_number ?? '') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">شماره شبا</label>
                            <input type="text" name="bank_accounts[{{ $index }}][iban]" class="form-control iban" maxlength="26" dir="ltr"
                                   value="{{ $account['iban'] ?? ($account->iban ?? '') }}">
                        </div>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-danger removeBankAccount">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>
<!-- Template for new bank account row -->
<template id="bankAccountTemplate">
    <div class="bank-account-row animate__animated animate__fadeIn">
        <div class="row g-3">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-label">نام بانک</label>
                    <input type="text" name="bank_accounts[INDEX][bank_name]" class="form-control">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-label">شماره حساب</label>
                    <input type="text" name="bank_accounts[INDEX][account_number]" class="form-control" dir="ltr">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-label">شماره کارت</label>
                    <input type="text" name="bank_accounts[INDEX][card_number]" class="form-control card-number" maxlength="19" dir="ltr">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="form-label">شماره شبا</label>
                    <input type="text" name="bank_accounts[INDEX][iban]" class="form-control iban" maxlength="26" dir="ltr">
                </div>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="button" class="btn btn-danger removeBankAccount">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    </div>
</template>
