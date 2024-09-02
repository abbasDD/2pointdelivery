{{-- Delivery Secureship Details --}}
<div id="deliverySecureshipDetails" class="row d-none">

    <h5>Package Details</h5>

    {{-- List of added secureship_packages --}}
    <div class="col-md-12">
        <table class="table table-striped">
            <thead class="thead-primary">
                <tr>
                    <th>Box</th>
                    <th>Type</th>
                    <th>Weight</th>
                    <th>Dimensions</th>
                    <th>Declared Value</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="deliverySecureshipPackagesTableBody">
                <tr>
                    <td colspan="6" class="text-center">No package added</td>
            </tbody>
        </table>
    </div>

    {{-- Add secureship_package form --}}
    <h4>Package Form</h4>
    {{-- Package Type --}}
    <div class="col-md-6">
        <label for="secureship_packageType">Package Type</label>
        {{-- Select Package Type --}}
        <select class="form-control" name="secureship_package_type" id="secureship_package_type"
            onchange="updateSecureshipPackageForm()">
            <option value="" disabled>Select Package Type</option>
            {{-- My Package --}}
            <option value="MyPackage">My Package</option>
            {{-- Envelope --}}
            <option value="Envelope">Envelope</option>
            {{-- Pak --}}
            <option value="Pak">Pak</option>
            {{-- Skid / Pallet --}}
            <option value="Pallet">Skid / Pallet</option>
        </select>
    </div>

    {{-- Package Dimensions --}}
    <div class="col-md-6" id="deliverySecureshipPackageDimensionsDiv">
        <label for="secureship_packageDimensions">Package Dimensions</label>
        <div class="row">

            {{-- Package Length --}}
            <div class="col-md-3">
                <div class="input-group mb-3">
                    <input type="number" class="form-control" placeholder="Length" name="secureship_package_length"
                        id="secureship_package_length" aria-describedby="secureship_package_length">
                </div>
            </div>


            {{-- Package Width --}}
            <div class="col-md-3">
                <div class="input-group mb-3">
                    <input type="number" class="form-control" placeholder="Width" name="secureship_package_width"
                        id="secureship_package_width" aria-describedby="secureship_package_width">
                </div>
            </div>

            {{-- Package Height  --}}
            <div class="col-md-3">
                <div class="input-group mb-3">
                    <input type="number" class="form-control" placeholder="Height" name="secureship_package_height"
                        id="secureship_package_height" aria-describedby="secureship_package_height">
                </div>
            </div>

            {{-- Dimension Unit --}}
            <div class="col-md-3">
                <div class="input-group mb-3">
                    {{-- Select Dimension Unit --}}
                    <select class="form-control" name="secureship_dimension_unit" id="secureship_dimension_unit">
                        <option value="Inches">Inches</option>
                        <option value="CM">CM</option>
                    </select>
                </div>
            </div>

        </div>

    </div>

    {{-- Package Weight --}}
    <div class="col-md-6" id="deliverySecureshipPackageWeightDiv">
        <label for="secureship_packageWeight">Package Weight</label>
        <div class="row">

            {{-- Package Weight --}}
            <div class="col-md-6">
                <div class="input-group mb-3">
                    <input type="number" class="form-control" placeholder="Weight" name="secureship_package_weight"
                        id="secureship_package_weight" aria-describedby="secureship_package_weight">
                </div>
            </div>

            {{-- Weight Unit --}}
            <div class="col-md-6">
                <div class="input-group mb-3">
                    {{-- Select Weight Unit --}}
                    <select class="form-control" name="secureship_weight_unit" id="secureship_weight_unit">
                        <option value="Lbs">LB</option>
                        <option value="Kgs">KG</option>
                    </select>
                </div>
            </div>

        </div>

    </div>

    {{-- Declared Value --}}
    <div class="col-md-6" id="deliverySecureshipPackageValueDiv">
        <label for="secureship_packageValue">Declared Value</label>
        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Value" id="secureship_package_value"
                name="secureship_package_value" aria-describedby="secureship_package_value" pattern="\d+(\.\d{0,2})?"
                inputmode="decimal"
                oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/^(\d*\.?\d{0,2}).*$/g, '$1');">
            <span class="input-group-text text-uppercase">CAD</span>
        </div>
    </div>

    {{-- Additional Handling Checkbox --}}
    <div class="col-md-6" id="deliverySecureshipPackageAdditionalHandlingDiv">
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" name="secureship_additional_handling"
                id="secureship_additional_handling">
            <label class="form-check-label" for="secureship_additional_handling">Additional Handling</label>
        </div>
    </div>

    {{-- Add Package Button --}}
    <div class="col-md-12 text-right">
        <button type="button" class="btn btn-primary" id="add_secureship_package" onclick="addSecureshipPackage()">Add
            Package</button>
    </div>


    {{-- Extras --}}
    <h4>Extras</h4>
    {{-- Dropdown for Signature Selections --}}
    <div class="col-md-6">
        <label for="secureship_signature">Signature Options</label>
        <div class="input-group mb-3">
            <select class="form-control" name="secureship_signature" id="secureship_signature">
                {{-- <option value="" disabled>Select Signature Options</option> --}}
                <option value="None">None</option>
                <option value="SignatureRequired">Signature Required</option>
                <option value="AdultSignatureRequired">Adult Signature Required</option>
            </select>
        </div>
    </div>

    {{-- Documents Only Checkbox --}}
    <div class="col-md-6">
        <label for="documents_only">Documents Only</label>
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" name="documents_only" id="documents_only">
            <label class="form-check-label" for="documents_only">Documents Only</label>
        </div>
    </div>

</div>

{{-- End of Packages --}}
