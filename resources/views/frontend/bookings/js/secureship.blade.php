<script>
    // updateSecureshipPackageForm
    function updateSecureshipPackageForm() {
        // get secureship_package_type value
        var secureship_package_type = document.getElementById('secureship_package_type').value;

        // Case for My Package
        switch (secureship_package_type) {
            // Case for My Package
            case 'MyPackage':
                // Remove d-none class from deliverySecureshipPackageDimensionsDiv
                document.getElementById('deliverySecureshipPackageDimensionsDiv').classList.remove('d-none');
                // Remove d-none class from deliverySecureshipPackageWeightDiv
                document.getElementById('deliverySecureshipPackageWeightDiv').classList.remove('d-none');
                // Remove d-none class from deliverySecureshipPackageValueDiv
                document.getElementById('deliverySecureshipPackageValueDiv').classList.remove('d-none');
                // Remove d-none class from deliverySecureshipPackageAdditionalHandlingDiv
                document.getElementById('deliverySecureshipPackageAdditionalHandlingDiv').classList.remove('d-none');
                break;
                // Case for Envelope
            case 'Envelope':
                // Add d-none class from deliverySecureshipPackageDimensionsDiv
                document.getElementById('deliverySecureshipPackageDimensionsDiv').classList.add('d-none');
                // Add d-none class from deliverySecureshipPackageWeightDiv
                document.getElementById('deliverySecureshipPackageWeightDiv').classList.add('d-none');
                // Add d-none class from deliverySecureshipPackageValueDiv
                document.getElementById('deliverySecureshipPackageValueDiv').classList.add('d-none');
                // Add d-none class from deliverySecureshipPackageAdditionalHandlingDiv
                document.getElementById('deliverySecureshipPackageAdditionalHandlingDiv').classList.add('d-none');
                break;
            case 'Pak':
                // Add d-none class from deliverySecureshipPackageDimensionsDiv
                document.getElementById('deliverySecureshipPackageDimensionsDiv').classList.add('d-none');
                // Remove d-none class from deliverySecureshipPackageWeightDiv
                document.getElementById('deliverySecureshipPackageWeightDiv').classList.remove('d-none');
                // remove d-none class from deliverySecureshipPackageValueDiv
                document.getElementById('deliverySecureshipPackageValueDiv').classList.remove('d-none');
                // Remove d-none class from deliverySecureshipPackageAdditionalHandlingDiv
                document.getElementById('deliverySecureshipPackageAdditionalHandlingDiv').classList.remove('d-none');
                break;
                // Pallet Case
            case 'Pallet':
                // Remove d-none class from deliverySecureshipPackageDimensionsDiv
                document.getElementById('deliverySecureshipPackageDimensionsDiv').classList.remove('d-none');
                // Remove d-none class from deliverySecureshipPackageWeightDiv
                document.getElementById('deliverySecureshipPackageWeightDiv').classList.remove('d-none');
                // Remove d-none class from deliverySecureshipPackageValueDiv
                document.getElementById('deliverySecureshipPackageValueDiv').classList.remove('d-none');
                // Remove d-none class from deliverySecureshipPackageAdditionalHandlingDiv
                document.getElementById('deliverySecureshipPackageAdditionalHandlingDiv').classList.remove('d-none');
                break;
            default:
                console.log('No secureship_package_type');
                break;
        }
    }

    // add_secureship_package button click
    function addSecureshipPackage() {
        // get secureship_package_type value
        var secureship_package_type = document.getElementById('secureship_package_type').value;

        // Case for My Package
        switch (secureship_package_type) {
            case 'MyPackage':
                // secureship_package_length value
                var secureship_package_length = document.getElementById('secureship_package_length').value;
                // secureship_package_width input
                var secureship_package_width = $('#secureship_package_width').val();
                // secureship_package_height value
                var secureship_package_height = document.getElementById('secureship_package_height').value;
                // secureship_dimension_unit
                var secureship_dimension_unit = document.getElementById('secureship_dimension_unit').value;

                // get secureship_package_weight value
                var secureship_package_weight = document.getElementById('secureship_package_weight').value;
                // get secureship_weight_unit value
                var secureship_weight_unit = document.getElementById('secureship_weight_unit').value;
                if (secureship_package_weight == 0) {
                    // show error on field
                    document.getElementById('secureship_package_weight').classList.add('is-invalid');
                    return false;
                }

                // get secureship_additional_handling value
                var secureship_additional_handling = document.getElementById('secureship_additional_handling').value;

                // get secureship_package_value value
                var secureship_package_value = document.getElementById('secureship_package_value').value;
                break;

            case 'Envelope':
                // get secureship_package_weight value
                secureship_package_weight = 1.0;
                // get secureship_weight_unit value
                secureship_weight_unit = 'Lbs';
                break;

            case 'Pak':
                // get secureship_package_weight value
                var secureship_package_weight = document.getElementById('secureship_package_weight').value;
                // get secureship_weight_unit value
                var secureship_weight_unit = document.getElementById('secureship_weight_unit').value;
                if (secureship_package_weight == 0) {
                    // show error on field
                    document.getElementById('secureship_package_weight').classList.add('is-invalid');
                    return false;
                }

                // get secureship_additional_handling value
                var secureship_additional_handling = document.getElementById('secureship_additional_handling').value;

                // get secureship_package_value value
                var secureship_package_value = document.getElementById('secureship_package_value').value;
                break;

            case 'Pallet':
                // secureship_package_length value
                var secureship_package_length = document.getElementById('secureship_package_length').value;
                // secureship_package_width input
                var secureship_package_width = $('#secureship_package_width').val();
                // secureship_package_height value
                var secureship_package_height = document.getElementById('secureship_package_height').value;
                // secureship_dimension_unit
                var secureship_dimension_unit = document.getElementById('secureship_dimension_unit').value;

                // get secureship_package_weight value
                var secureship_package_weight = document.getElementById('secureship_package_weight').value;
                // get secureship_weight_unit value
                var secureship_weight_unit = document.getElementById('secureship_weight_unit').value;
                if (secureship_package_weight == 0) {
                    // show error on field
                    document.getElementById('secureship_package_weight').classList.add('is-invalid');
                    return false;
                }

                // get secureship_additional_handling value
                var secureship_additional_handling = document.getElementById('secureship_additional_handling').value;

                // get secureship_package_value value
                var secureship_package_value = document.getElementById('secureship_package_value').value;
                break;

            case 'default':
                console.log('No secureship_package_type');
                break;
        }

        if (secureshipPackages.length == 0) {
            document.getElementById('deliverySecureshipPackagesTableBody').innerHTML = '';

            // Update the secureship_package_type option
            document.getElementById('secureship_package_type').innerHTML = '';
            document.getElementById('secureship_package_type').innerHTML =
                '<option value="MyPackage">My Package</option>';
            document.getElementById('secureship_package_type').innerHTML +=
                '<option value="Envelope">Envelope</option>';
            document.getElementById('secureship_package_type').innerHTML +=
                '<option value="Pak">Pak</option>';
            document.getElementById('secureship_package_type').innerHTML +=
                '<option value="Pallet">Skid / Pallet</option>';
        }

        // Map of data
        var secureshipPackageItem = {
            packageType: secureship_package_type || null,
            userDefinedPackageType: null,
            weight: secureship_package_weight || 1.0,
            weightUnits: secureship_weight_unit || 'Lbs',
            length: secureship_package_length || 0.0,
            width: secureship_package_width || 0.0,
            height: secureship_package_height || 0.0,
            dimUnits: secureship_dimension_unit || 'Inches',
            'value': secureship_package_value || 0.0,
            insurance: 0.0,
            isAdditionalHandling: secureship_additional_handling || false,
            signatureOptions: 'None',
            description: null,
            isDangerousGoods: true,
            isNonStackable: true,
        };


        // Add all values to array secureshipPackages
        secureshipPackages.push(secureshipPackageItem);

        if (secureshipPackageItem.packageType == 'Pallet') {
            document.getElementById('secureship_package_type').innerHTML = '';

            document.getElementById('secureship_package_type').innerHTML +=
                '<option value="Pallet">Skid / Pallet</option>';
        } else {
            document.getElementById('secureship_package_type').innerHTML = '';
            document.getElementById('secureship_package_type').innerHTML =
                '<option value="MyPackage">My Package</option>';
            document.getElementById('secureship_package_type').innerHTML +=
                '<option value="Envelope">Envelope</option>';
            document.getElementById('secureship_package_type').innerHTML +=
                '<option value="Pak">Pak</option>';
        }


        console.log('New secureshipPackageItem:');
        console.log(secureshipPackageItem);

        // Add row to table deliverySecureshipPackagesTableBody
        addSecureshipPackageToTable(secureshipPackageItem);

        // Remove in-valid error from secureship_package_weight
        document.getElementById('secureship_package_weight').classList.remove('is-invalid');

    }

    function addSecureshipPackageToTable(secureshipPackageItem) {
        // get deliverySecureshipPackagesTableBody
        var deliverySecureshipPackagesTableBody = document.getElementById('deliverySecureshipPackagesTableBody');

        // Add row to table deliverySecureshipPackagesTableBody
        deliverySecureshipPackagesTableBody.innerHTML += `
            <tr>
                <td>${secureshipPackages.length}</td>
                <td>${secureshipPackageItem.packageType}</td>
                <td>${secureshipPackageItem.weight + ' ' + secureshipPackageItem.weightUnits}</td>
                <td>${secureshipPackageItem.length + ' x ' + secureshipPackageItem.width + ' x ' + secureshipPackageItem.height + ' ' + secureshipPackageItem.dimUnits}</td>
                <td>${secureshipPackageItem.value + ' CAD'}</td>
                <td><a class="btn btn-sm btn-danger" onclick="removeSecureshipPackage(${secureshipPackages.length - 1})">Remove</a></td>
            </tr>
            `;
    }

    // removeSecureshipPackage
    function removeSecureshipPackage(index) {
        secureshipPackages.splice(index, 1);
        document.getElementById('deliverySecureshipPackagesTableBody').innerHTML = '';

        for (var i = 0; i < secureshipPackages.length; i++) {
            addSecureshipPackageToTable(secureshipPackages[i]);
        }

        if (secureshipPackages.length == 0) {
            // add colpsan tr
            document.getElementById('deliverySecureshipPackagesTableBody').innerHTML = `
                <tr>
                    <td colspan="6" class="text-center">No package added</td>
                </tr>
                `;

            // Update the secureship_package_type option
            document.getElementById('secureship_package_type').innerHTML = '';
            document.getElementById('secureship_package_type').innerHTML =
                '<option value="MyPackage">My Package</option>';
            document.getElementById('secureship_package_type').innerHTML +=
                '<option value="Envelope">Envelope</option>';
            document.getElementById('secureship_package_type').innerHTML +=
                '<option value="Pak">Pak</option>';
            document.getElementById('secureship_package_type').innerHTML +=
                '<option value="Pallet">Skid / Pallet</option>';
        }
    }
</script>
