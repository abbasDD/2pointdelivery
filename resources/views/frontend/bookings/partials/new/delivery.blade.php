{{-- Delivery Package Details --}}
<div id="deliveryPackageDetails" class="row d-none">
    {{-- Hide if volume_enabled is false --}}
    <div class="row" id="deliveryPackageDimensions">
        {{-- Package Length --}}
        <div class="col-md-4">
            <label for="packageLength">Package Length</label>
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Length" name="package_length" id="package_length"
                    aria-describedby="package_length"
                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                    onchange="updatePaymentAmount()">
                <span class="input-group-text text-uppercase"
                    id="package_length">{{ config('dimension') ?: 'INCH' }}</span>
            </div>
        </div>
        {{-- Package Width --}}
        <div class="col-md-4">
            <label for="packageWidth">Package Width</label>
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Width" name="package_width" id="package_width"
                    aria-describedby="package_width"
                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                    onchange="updatePaymentAmount()">
                <span class="input-group-text text-uppercase"
                    id="package_width">{{ config('dimension') ?: 'INCH' }}</span>
            </div>
        </div>
        {{-- Package Height  --}}
        <div class="col-md-4">
            <label for="packageHeight">Package Height</label>
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Height" name="package_height"
                    id="package_height" aria-describedby="package_height"
                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                    onchange="updatePaymentAmount()">
                <span class="input-group-text text-uppercase"
                    id="package_height">{{ config('dimension') ?: 'INCH' }}</span>
            </div>
        </div>
    </div>


    {{-- Package Weight  --}}
    <div class="col-md-6">
        <label for="packageWeight">Package Weight</label>
        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Weight" name="package_weight" id="package_weight"
                aria-describedby="package_weight"
                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                onchange="updatePaymentAmount()">
            <span class="input-group-text text-uppercase" id="package_weight">{{ config('weight') ?: 'Kg' }}</span>
        </div>
    </div>
    {{-- Check if package value decalared --}}
    @if (config('declare_package_value') == 'yes')
        {{-- Package Value --}}
        <div class="col-md-6">
            <label for="packageValue">Package Value</label>
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Value" name="package_value"
                    aria-describedby="package_value"
                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                    onchange="updatePaymentAmount()">
                <span class="input-group-text text-uppercase" id="package_value">$</span>
            </div>
        </div>
    @endif

    {{-- Check if insurance is enabled --}}
    @if (config('insurance') == 1)
        {{-- Insurance --}}
        <div class="col-md-6">
            <label for="insurance">Insurance</label>
            <div class="input-group mb-3">
                <select class="form-control" name="insurance" aria-label="Insurance" onchange="updatePaymentAmount()">
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                </select>
            </div>
        </div>
    @endif
</div>