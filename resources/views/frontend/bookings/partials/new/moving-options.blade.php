<style>
    .fancy-select-wrap {
        background: white;
        line-height: 1.15;
        width: 100%;
        overflow: hidden;
        position: relative;
        border: 1px solid #e6e6e6;
    }

    .fancy-select-wrap .selected {
        padding: 10px;
        padding-right: 35px;
        line-height: 2.5rem;
        cursor: pointer;
    }

    .fancy-select-wrap .selected>span {
        display: inline-block;
        margin-right: 0.5rem;
        background: #efefef;
        padding: 0.1rem 2rem 0.1rem 0.5rem;
        line-height: 2rem;
        border-radius: 5px;
        position: relative;
        transition: all 0.2s ease;
    }

    .fancy-select-wrap .selected>span:after {
        content: 'x';
        font-weight: bold;
        position: absolute;
        line-height: 2rem;
        font-size: 18px;
        height: 100%;
        width: 2rem;
        right: 0;
        top: 0;
        opacity: 0.5;
        text-align: center;
        transition: all 0.2s ease;
    }

    .fancy-select-wrap .selected>span:hover {
        background-color: #32aa8e;
    }

    .fancy-select-wrap .selected>span:hover:after {
        opacity: 1;
    }

    .fancy-select-wrap .options {
        padding: 10px 15px;
        display: none;
        max-height: 250px;
        overflow: scroll;
        overflow-x: hidden;
    }

    .fancy-select-wrap .options>div {
        padding: 0.3rem 0;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
    }

    .fancy-select-wrap .options>div:not(.subopt) {
        font-weight: bold;
    }

    .fancy-select-wrap .options>div.subopt {
        padding-left: 2rem;
    }

    .fancy-select-wrap .options>div:after {
        content: '+';
        font-weight: bold;
        position: absolute;
        line-height: 23px;
        font-size: 20px;
        height: 100%;
        right: 2rem;
        top: 0;
        opacity: 0;
        transition: all 0.2s ease;
    }

    .fancy-select-wrap .options>div:hover {
        transform: translateX(10px);
        background-color: #efefef;
    }

    .fancy-select-wrap .options>div:hover:after {
        opacity: 1;
    }

    .fancy-select-wrap .options>div[data-selected='1'] {
        background-color: #c8f5a1;
    }

    .fancy-select-wrap .options>div[data-selected='1']:after {
        opacity: 1;
        content: '✓';
    }

    .fancy-select-wrap .options>div[data-selected='1']:hover {
        background-color: #32aa8e;
    }

    .fancy-select-wrap .options>div[data-selected='1']:hover:after {
        content: 'x';
    }

    .fancy-select-wrap+select {
        visibility: hidden;
        position: absolute;
    }

    .fancy-select-wrap:after {
        display: block;
        content: '';
        position: absolute;
        top: 19px;
        right: 15px;
        width: 14px;
        height: 8px;
        background-image: linear-gradient(to top right, transparent 50%, #bbb 51%), linear-gradient(to top left, transparent 50%, #bbb 51%);
        background-size: 7px 8px;
        background-repeat: no-repeat;
        background-position: 0 0, 6px 0;
        opacity: 0.6;
        transition: opacity 0.4s ease;
    }

    .fancy-select-wrap:hover:after {
        opacity: 1;
    }

    .fancy-select-wrap.expanded {
        min-height: 300px;
    }

    .fancy-select-wrap.expanded .selected {
        border-bottom: 1px solid #e6e6e6;
    }

    .fancy-select-wrap.expanded .options {
        display: block;
    }

    .fancy-select-wrap.expanded:after {
        background-image: linear-gradient(to bottom right, transparent 50%, #bbb 51%), linear-gradient(to bottom left, transparent 50%, #bbb 51%);
    }
</style>

<div class="col-md-12">
    <div class="mb-3">
        <label for="movingDetails">Moving Details</label>
        <select class="fancy-select" name="moving_details" multiple="multiple">
            <option value="alarms">Alarms</option>
            <option class="subopt" value="burglary_alarms">Burglary Alarms</option>
            <option class="subopt" value="alarms_fire_sprinkler_alarms">Fire Sprinkler Alarms</option>
            <option value="air_conditioner">Air Conditioner</option>
            <option class="subopt" value="compact_units">Compact Units</option>
            <option class="subopt" value="heat_pump_ac_units">Heat Pump AC Units</option>
            <option class="subopt" value="title_14">Title 14 Hers Testing &amp; Reports</option>
            <option value="audio">Audio / Photography</option>
            <option class="subopt" value="dj_audio">DJ Audio Connections</option>
            <option class="subopt" value="photography">Photography Services</option>
            <option class="subopt" value="surround_sound">Surround Sound</option>
            <option value="apps_websites">Apps / Website Development</option>
            <option class="subopt" value="apps">Apps Development</option>
            <option class="subopt" value="programming">Custom Programming</option>
            <option class="subopt" value="websites">Website Design</option>
            <option value="computers_pos_it">Computers / POS &amp; IT</option>
            <option class="subopt" value="computer_repair">Computer Repair</option>
            <option class="subopt" value="internet_connection">Internet Connection Spectrum / AT&amp;T</option>
            <option class="subopt" value="networks">Networks</option>
            <option class="subopt" value="pos">POS</option>
            <option value="chemical_uniform">Chemical and Uniform</option>
            <option class="subopt" value="chemical_services">Chemical Services</option>
            <option class="subopt" value="uniformed_services">Uniformed Services</option>
            <option value="electrician">Electrician</option>
            <option class="subopt" value="dc_current">D.C. Current for Vehicle Troubleshooting</option>
            <option class="subopt" value="equipment_troubleshooting">Equipment Troubleshooting</option>
            <option class="subopt" value="high_voltage_vac">Hight Voltage v.a.c. for Buildings</option>
            <option class="subopt" value="low_voltage_vac">Low Voltage v.a.c. for Buildings</option>
            <option value="fire_sprinkles">Fire Protection</option>
            <option class="subopt" value="fire_extinguishers">Fire Extinguishers</option>
            <option class="subopt" value="fire_sprinkler_alarms">Fire Sprinkler Alarms</option>
            <option class="subopt" value="kitchen_hood_suppression">Kitchen Hood Suppression Systems</option>
            <option class="subopt" value="sprinklers">Sprinkler Systems</option>
            <option value="kitchen_swamp_pressure">Kitchen Hood / Swamp Cooler / Pressure Washing</option>
            <option class="subopt" value="kitchen_fire_sprinkler_alarms">Fire Sprinkler Alarms</option>
            <option class="subopt" value="hood_cleaning">Hood Cleaning</option>
            <option class="subopt" value="hood_filter_cleaning">Hood Filter Cleaning</option>
            <option class="subopt" value="hood_repair">Hood Repair</option>
            <option class="subopt" value="kitchen_hood_suppression">Kitchen Hood Suppression Systems</option>
            <option class="subopt" value="sprinklers">Sprinkler Systems</option>
            <option value="kitchen_equipment">Kitchen Equipment</option>
            <option class="subopt" value="baking_equipment">Baking Equipment / Mixers</option>
            <option class="subopt" value="coffee_machines">Coffee Machines</option>
            <option class="subopt" value="oven_repair">Conventional Ovens</option>
            <option class="subopt" value="deli_equipment">Deli Equipment / Slicers</option>
            <option class="subopt" value="dishwashers">Dishwashers</option>
            <option class="subopt" value="knife_sharpening">Knife Sharpening</option>
            <option class="subopt" value="pizza_ovens">Pizza Ovens</option>
            <option class="subopt" value="soda_machines">Soda Machines</option>
            <option value="locksmith_window">Locksmith/ window</option>
            <option class="subopt" value="locksmith">Locksmith service</option>
            <option class="subopt" value="window_service">Window service</option>
            <option value="landscaping">Landscaping</option>
            <option class="subopt" value="landscaping_services">Landscaping services</option>
            <option class="subopt" value="tree_trimming">Tree trimming</option>
            <option value="moving_service">Moving services</option>
            <option class="subopt" value="equipment">Equipment</option>
            <option class="subopt" value="furniture">Furniture</option>
            <option value="pest_control">Pest Control</option>
            <option class="subopt" value="pest_control_services">Pest control services</option>
            <option value="plumbing">Plumbing</option>
            <option class="subopt" value="hydro_jet">Hydro jet service</option>
            <option class="subopt" value="drain_snake_service">Drain snake service</option>
            <option class="subopt" value="cold_and_hot_water_supply">Cold and hot water Supply</option>
            <option class="subopt" value="drain_repair">Drain repair</option>
            <option class="subopt" value="grease_trap_installation">Grease trap installation</option>
            <option class="subopt" value="water_heater_tankless_service">Water heater/ tankless service</option>
            <option class="subopt" value="gas_line">Gas line</option>
        </select>
    </div>
</div>



<script>
    document.querySelectorAll('.fancy-select').forEach(
        sel => fancyMultipleSelect(sel)
    );

    function fancyMultipleSelect(select) {
        const options = select.querySelectorAll('option');

        const fancySelect = document.createElement('div');
        const optionsWrap = document.createElement('div');
        const selectedWrap = document.createElement('div');
        const selectCatsTxt = document.createTextNode('Select moving details...');

        fancySelect.classList.add('fancy-select-wrap');
        fancySelect.dataset.name = select.name;

        optionsWrap.classList.add('options');

        selectedWrap.classList.add('selected');
        selectedWrap.appendChild(selectCatsTxt);

        fancySelect.appendChild(selectedWrap);
        fancySelect.appendChild(optionsWrap);

        options.forEach(opt => {
            const option = document.createElement('div');
            const text = document.createTextNode(opt.textContent);

            option.appendChild(text);

            if (opt.classList.contains('subopt')) option.classList.add('subopt');

            option.dataset.value = opt.value;
            option.dataset.selected = opt.selected ? '1' : '0';

            option.addEventListener('click', function(e) {
                if (this.dataset.selected === '1') {
                    this.dataset.selected = '0';

                    select.querySelectorAll(
                        `option[value="${this.dataset.value}"]`
                    ).selected = false;
                } else {
                    this.dataset.selected = '1';
                    select.querySelector(
                        `option[value="${this.dataset.value}"]`
                    ).selected = true;
                }

                refreshSelecteds(fancySelect);
            });

            optionsWrap.appendChild(option);
        });

        selectedWrap.addEventListener('click', function() {
            this.parentNode.classList.toggle('expanded');
        });

        select.parentNode.insertBefore(fancySelect, select);

        refreshSelecteds(fancySelect);
    }

    function refreshSelecteds(fancySelect) {
        // Nodes of selected opts inside fancySelect
        const selectedOptions = fancySelect.querySelectorAll(
            '.options [data-selected="1"]'
        );

        // Original select
        const originalSelect = fancySelect.nextElementSibling;

        // Wrapper for our selected opts spans
        const selectedWrap = fancySelect.querySelector('.selected');

        // Remove currently selected options
        while (selectedWrap.firstChild) {
            selectedWrap.removeChild(selectedWrap.firstChild);
        }

        if (selectedOptions.length < 1) {
            // No selected options, show empty msg
            const selectCatsTxt = document.createTextNode('Select moving details...');
            selectedWrap.appendChild(selectCatsTxt);
        } else {
            const usedOpts = [];

            // Create selected opts spans and add to wrapper
            selectedOptions.forEach(opt => {
                // Prevent repeats
                if (usedOpts.includes(opt.dataset.value)) return;

                const span = document.createElement('span');
                const text = document.createTextNode(opt.textContent);

                span.appendChild(text);

                span.addEventListener('click', function(e) {
                    e.stopImmediatePropagation();
                    opt.dataset.selected = '0';
                    originalSelect.querySelector(
                        `option[value="${opt.dataset.value}"]`
                    ).selected = false;

                    refreshSelecteds(fancySelect);
                });

                selectedWrap.appendChild(span);

                usedOpts.push(opt.dataset.value);
            });
        }
    }
</script>
