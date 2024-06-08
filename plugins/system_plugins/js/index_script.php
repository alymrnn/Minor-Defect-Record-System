<script type="text/javascript">
    $(document).ready(function () {
        load_defect_table(1);
    });

    document.getElementById("scan_product_name").addEventListener("keyup", e => {
        load_defect_table(1);
    });

    document.getElementById("scan_lot_no").addEventListener("keyup", e => {
        load_defect_table(1);
    });

    document.getElementById("scan_serial_no").addEventListener("keyup", e => {
        load_defect_table(1);
    });

    document.getElementById("search_process").addEventListener("keyup", e => {
        load_defect_table(1);
    });

    document.getElementById("search_line_no").addEventListener("keyup", e => {
        load_defect_table(1);
    });

    document.getElementById("search_date_from").addEventListener("change", e => {
        load_defect_table(1);
    });

    document.getElementById("search_date_to").addEventListener("change", e => {
        load_defect_table(1);
    });

    document.getElementById("search_defect_category").addEventListener("change", e => {
        load_defect_table(1);
    });

    document.getElementById("search_defect_details").addEventListener("change", e => {
        load_defect_table(1);
    });

    // Table Responsive Scroll Event for Load More
    document.getElementById("list_of_defect_res").addEventListener("scroll", function () {
        var scrollTop = document.getElementById("list_of_defect_res").scrollTop;
        var scrollHeight = document.getElementById("list_of_defect_res").scrollHeight;
        var offsetHeight = document.getElementById("list_of_defect_res").offsetHeight;

        //check if the scroll reached the bottom
        if ((offsetHeight + scrollTop + 1) >= scrollHeight) {
            get_next_page();
        }
    });

    const get_next_page = () => {
        var current_page = parseInt(sessionStorage.getItem('defect_table_pagination'));
        let total = sessionStorage.getItem('count_rows');
        var last_page = parseInt(sessionStorage.getItem('last_page'));
        var next_page = current_page + 1;
        if (next_page <= last_page && total > 0) {
            load_defect_table(next_page);
        }
    }

    const count_defect = () => {
        var scan_product_name = sessionStorage.getItem('scan_product_name');
        var scan_lot_no = sessionStorage.getItem('scan_lot_no');
        var scan_serial_no = sessionStorage.getItem('scan_serial_no');
        var search_process = sessionStorage.getItem('search_process');
        var search_line_no = sessionStorage.getItem('search_line_no');
        var search_date_from = sessionStorage.getItem('search_date_from');
        var search_date_to = sessionStorage.getItem('search_date_to');
        var search_defect_category = sessionStorage.getItem('search_defect_category');
        var search_defect_details = sessionStorage.getItem('search_defect_details');

        $.ajax({
            url: 'process/index_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'count_defect_list',
                scan_product_name: scan_product_name,
                scan_lot_no: scan_lot_no,
                scan_serial_no: scan_serial_no,
                search_process: search_process,
                search_line_no: search_line_no,
                search_date_from: search_date_from,
                search_date_to: search_date_to,
                search_defect_category: search_defect_category,
                search_defect_details: search_defect_details
            },
            success: function (response) {
                sessionStorage.setItem('count_rows', response);
                var count = `Total Record: ${response}`;
                $('#defect_table_info').html(count);

                if (response > 0) {
                    load_defect_last_page();
                } else {
                    document.getElementById("btnNextPage").style.display = "none";
                    document.getElementById("btnNextPage").setAttribute('disabled', true);
                }
            }
        });
    }

    const load_defect_last_page = () => {
        var scan_product_name = sessionStorage.getItem('scan_product_name');
        var scan_lot_no = sessionStorage.getItem('scan_lot_no');
        var scan_serial_no = sessionStorage.getItem('scan_serial_no');
        var search_process = sessionStorage.getItem('search_process');
        var search_line_no = sessionStorage.getItem('search_line_no');
        var search_date_from = sessionStorage.getItem('search_date_from');
        var search_date_to = sessionStorage.getItem('search_date_to');
        var search_defect_category = sessionStorage.getItem('search_defect_category');
        var search_defect_details = sessionStorage.getItem('search_defect_details');

        $.ajax({
            url: 'process/index_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'load_defect_last_page',
                scan_product_name: scan_product_name,
                scan_lot_no: scan_lot_no,
                scan_serial_no: scan_serial_no,
                search_process: search_process,
                search_line_no: search_line_no,
                search_date_from: search_date_from,
                search_date_to: search_date_to,
                search_defect_category: search_defect_category,
                search_defect_details: search_defect_details
            },
            success: function (response) {
                sessionStorage.setItem('last_page', response);
                let total = sessionStorage.getItem('count_rows');
                var next_page = current_page + 1;

                if (next_page > response || total < 1) {
                    document.getElementById("btnNextPage").style.display = "none";
                    document.getElementById("btnNextPage").setAttribute('disabled', true);
                } else {
                    document.getElementById("btnNextPage").style.display = "block";
                    document.getElementById("btnNextPage").removeAttribute('disabled');
                }
            }
        });
    }

    const load_defect_table = current_page => {
        var scan_product_name = document.getElementById('scan_product_name');
        var scan_lot_no = document.getElementById('scan_lot_no');
        var scan_serial_no = document.getElementById('scan_serial_no');
        var search_process = document.getElementById('search_process');
        var search_line_no = document.getElementById('search_line_no');
        var search_date_from = document.getElementById('search_date_from');
        var search_date_to = document.getElementById('search_date_to');
        var search_defect_category = document.getElementById('search_defect_category');
        var search_defect_details = document.getElementById('search_defect_details');

        var scan_product_name_1 = sessionStorage.getItem('scan_product_name');
        var scan_lot_no_1 = sessionStorage.getItem('scan_lot_no');
        var scan_serial_no_1 = sessionStorage.getItem('scan_serial_no');
        var search_process_1 = sessionStorage.getItem('search_process');
        var search_line_no_1 = sessionStorage.getItem('search_line_no');
        var search_date_from_1 = sessionStorage.getItem('search_date_from');
        var search_date_to_1 = sessionStorage.getItem('search_date_to');
        var search_defect_category_1 = sessionStorage.getItem('search_defect_category');
        var search_defect_details_1 = sessionStorage.getItem('search_defect_details');

        if (current_page > 1) {
            switch (true) {
                case scan_product_name !== scan_product_name_1:
                case scan_lot_no !== scan_lot_no_1:
                case scan_serial_no !== scan_serial_no_1:
                case search_process !== search_process_1:
                case search_line_no !== search_line_no_1:
                case search_date_from !== search_date_from_1:
                case search_date_to !== search_date_to_1:
                case search_defect_category !== search_defect_category_1:
                case search_defect_details !== search_defect_details_1:
                    scan_product_name = scan_product_name_1;
                    scan_lot_no = scan_lot_no_1;
                    scan_serial_no = scan_serial_no_1;
                    search_process = search_process_1;
                    search_line_no = search_line_no_1;
                    search_date_from = search_date_from_1;
                    search_date_to = search_date_to_1;
                    search_defect_category = search_defect_category_1;
                    search_defect_details = search_defect_details_1;
                    break;
                default:
            }
        } else {
            sessionStorage.setItem('scan_product_name', scan_product_name);
            sessionStorage.setItem('scan_lot_no', scan_lot_no);
            sessionStorage.setItem('scan_serial_no', scan_serial_no);
            sessionStorage.setItem('search_process', search_process);
            sessionStorage.setItem('search_line_no', search_line_no);
            sessionStorage.setItem('search_date_from', search_date_from);
            sessionStorage.setItem('search_date_to', search_date_to);
            sessionStorage.setItem('search_defect_category', search_defect_category);
            sessionStorage.setItem('search_defect_details', search_defect_details);
        }
        $.ajax({
            url: 'process/index_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'load_defect_table',
                scan_product_name: scan_product_name,
                scan_lot_no: scan_lot_no,
                scan_serial_no: scan_serial_no,
                search_process: search_process,
                search_line_no: search_line_no,
                search_date_from: search_date_from,
                search_date_to: search_date_to,
                search_defect_category: search_defect_category,
                search_defect_details: search_defect_details,
                current_page: current_page
            },
            beforeSend: () => {
                var loading = `<tr id="loading"><td colspan="6" style="text-align:center;"><div class="spinner-border text-dark role="status"><span class="sr-only">Loading...</span></div></td></tr>`;
                if (current_page == 1) {
                    document.getElementById("list_of_defect").innerHTML = loading;
                } else {
                    $('#defect_table tbody').append(loading);
                }
            },
            success: function (response) {
                $('#loading').remove();
                if (current_page == 1) {
                    $('#defect_table tbody').html(response);
                } else {
                    $('#defect_table tbody').append(response);
                }
                sessionStorage.setItem('defect_table_pagination', current_page);
                count_defect();
            }
        });
    }

    // highlight input field when empty
    document.getElementById("a_date_detected").addEventListener("input", function () {
        var date_detected = this;
        date_detected.classList.remove('highlight');
        document.getElementById("dateDetectedError").style.display = 'none';
    });

    document.getElementById("a_car_model").addEventListener("input", function () {
        var car_model = this;
        car_model.classList.remove('highlight');
        document.getElementById("carModelError").style.display = 'none';
    });

    document.getElementById("a_line_no").addEventListener("input", function () {
        var line_no = this;
        line_no.classList.remove('highlight');
        document.getElementById("lineNoError").style.display = 'none';
    });

    document.getElementById("a_process").addEventListener("input", function () {
        var process = this;
        process.classList.remove('highlight');
        document.getElementById("processError").style.display = 'none';
    });

    document.getElementById("a_shift_group").addEventListener("input", function () {
        var shift = this;
        shift.classList.remove('highlight');
        document.getElementById("shiftError").style.display = 'none';
    });

    document.getElementById("a_product_name").addEventListener("input", function () {
        var product_name = this;
        product_name.classList.remove('highlight');
        document.getElementById("productNoError").style.display = 'none';
    });

    document.getElementById("a_lot_no").addEventListener("input", function () {
        var lot_no = this;
        process.classList.remove('highlight');
        document.getElementById("lotNoError").style.display = 'none';
    });

    document.getElementById("a_serial_no").addEventListener("input", function () {
        var serial_no = this;
        serial_no.classList.remove('highlight');
        document.getElementById("serialNoError").style.display = 'none';
    });

    document.getElementById("a_defect_category").addEventListener("input", function () {
        var defect_category = this;
        defect_category.classList.remove('highlight');
        document.getElementById("defectCategoryError").style.display = 'none';
    });

    document.getElementById("a_defect_details").addEventListener("input", function () {
        var defect_details = this;
        defect_details.classList.remove('highlight');
        document.getElementById("defectDetailsError").style.display = 'none';
    });

    document.getElementById("a_sequence_no").addEventListener("input", function () {
        var sequence_no = this;
        sequence_no.classList.remove('highlight');
        document.getElementById("sequenceNoError").style.display = 'none';
    });

    document.getElementById("a_connector_no").addEventListener("input", function () {
        var connector_no = this;
        connector_no.classList.remove('highlight');
        document.getElementById("connectorNoError").style.display = 'none';
    });

    document.getElementById("a_repaired_by").addEventListener("input", function () {
        var repaired_by = this;
        repaired_by.classList.remove('highlight');
        document.getElementById("repairedByError").style.display = 'none';
    });

    document.getElementById("a_verified_by").addEventListener("input", function () {
        var verified_by = this;
        verified_by.classList.remove('highlight');
        document.getElementById("verifiedByError").style.display = 'none';
    });

    const add_defect_record = () => {
        var date_detected = document.getElementById("a_date_detected").value;
        var car_model = document.getElementById("a_car_model").value;
        var line_no = document.getElementById("a_line_no").value;
        var process = document.getElementById("a_process").value;
        var shift = document.getElementById("a_shift_group").value;

        // These fields are populated by QR code scan
        var product_name = document.getElementById("a_product_name");
        var lot_no = document.getElementById("a_lot_no");
        var serial_no = document.getElementById("a_serial_no");

        var defect_category = document.getElementById("a_defect_category").value;
        var defect_details = document.getElementById("a_defect_details").value;
        var sequence_no = document.getElementById("a_sequence_no").value;
        var connector_no = document.getElementById("a_connector_no").value;
        var repaired_by = document.getElementById("a_repaired_by").value;
        var verified_by = document.getElementById("a_verified_by").value;
        var defect_id = document.getElementById('defect_id_no').value;

        let hasError = false;

        // Validation checks
        if (date_detected === '') {
            document.getElementById("a_date_detected").classList.add('highlight');
            document.getElementById("dateDetectedError").style.display = 'block';
            hasError = true;
        }

        if (car_model === '') {
            document.getElementById("a_car_model").classList.add('highlight');
            document.getElementById("carModelError").style.display = 'block';
            hasError = true;
        }

        if (line_no === '') {
            document.getElementById("a_line_no").classList.add('highlight');
            document.getElementById("lineNoError").style.display = 'block';
            hasError = true;
        }

        if (process === '') {
            document.getElementById("a_process").classList.add('highlight');
            document.getElementById("processError").style.display = 'block';
            hasError = true;
        }

        if (shift === '') {
            document.getElementById("a_shift_group").classList.add('highlight');
            document.getElementById("shiftError").style.display = 'block';
            hasError = true;
        }

        if (defect_category === '') {
            document.getElementById("a_defect_category").classList.add('highlight');
            document.getElementById("defectCategoryError").style.display = 'block';
            hasError = true;
        }

        if (defect_details === '') {
            document.getElementById("a_defect_details").classList.add('highlight');
            document.getElementById("defectDetailsError").style.display = 'block';
            hasError = true;
        }

        if (sequence_no === '') {
            document.getElementById("a_sequence_no").classList.add('highlight');
            document.getElementById("sequenceNoError").style.display = 'block';
            hasError = true;
        }

        if (connector_no === '') {
            document.getElementById("a_connector_no").classList.add('highlight');
            document.getElementById("connectorNoError").style.display = 'block';
            hasError = true;
        }

        if (repaired_by === '') {
            document.getElementById("a_repaired_by").classList.add('highlight');
            document.getElementById("repairedByError").style.display = 'block';
            hasError = true;
        }

        if (verified_by === '') {
            document.getElementById("a_verified_by").classList.add('highlight');
            document.getElementById("verifiedByError").style.display = 'block';
            hasError = true;
        }

        // Additional validation for QR code populated fields
        if (product_name.value === '') {
            product_name.classList.add('highlight');
            document.getElementById("productNoError").style.display = 'block';
            hasError = true;
        }

        if (lot_no.value === '') {
            lot_no.classList.add('highlight');
            document.getElementById("lotNoError").style.display = 'block';
            hasError = true;
        }

        if (serial_no.value === '') {
            serial_no.classList.add('highlight');
            document.getElementById("serialNoError").style.display = 'block';
            hasError = true;
        }

        if (hasError) {
            return;
        }

        // Enable fields before sending data
        product_name.disabled = false;
        lot_no.disabled = false;
        serial_no.disabled = false;

        // Debugging: Log all variables before sending the AJAX request
        console.log({
            method: 'add_defect_record',
            date_detected: date_detected,
            car_model: car_model,
            line_no: line_no,
            process: process,
            shift: shift,
            product_name: product_name.value,
            lot_no: lot_no.value,
            serial_no: serial_no.value,
            defect_category: defect_category,
            defect_details: defect_details,
            sequence_no: sequence_no,
            connector_no: connector_no,
            repaired_by: repaired_by,
            verified_by: verified_by,
            defect_id: defect_id
        });

        $.ajax({
            url: 'process/index_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'add_defect_record',
                date_detected: date_detected,
                car_model: car_model,
                line_no: line_no,
                process: process,
                shift: shift,
                product_name: product_name.value,
                lot_no: lot_no.value,
                serial_no: serial_no.value,
                defect_category: defect_category,
                defect_details: defect_details,
                sequence_no: sequence_no,
                connector_no: connector_no,
                repaired_by: repaired_by,
                verified_by: verified_by,
                defect_id: defect_id
            },
            success: function (response) {
                if (response == 'success') {
                    document.getElementById("defect_id_no").value = defect_id;
                    Swal.fire({
                        icon: 'success',
                        title: 'Successfully Recorded',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $('#a_date_detected').val('');
                    $('#a_car_model').val('');
                    $('#a_line_no').val('');
                    $('#a_process').val('');
                    $('#a_shift_group').val('');
                    $('#a_product_name').val('');
                    $('#a_lot_no').val('');
                    $('#a_serial_no').val('');
                    $('#a_defect_category').val('');
                    $('#a_defect_details').val('');
                    $('#a_sequence_no').val('');
                    $('#a_connector_no').val('');
                    $('#a_repaired_by').val('');
                    $('#a_verified_by').val('');
                    $('#defect_id_no').val('');

                    load_defect_table(1);

                    $('#add_defect_record').modal('hide');
                } else {
                    console.error("Unexpected response from the server:", response);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            }
        });
    }




    const clear_defect_record = () => {
        document.getElementById("a_date_detected").value = '';
        document.getElementById("a_car_model").value = '';
        document.getElementById("a_line_no").value = '';
        document.getElementById("a_process").value = '';
        document.getElementById("a_shift_group").value = '';
        document.getElementById("a_product_name").value = '';
        document.getElementById("a_lot_no").value = '';
        document.getElementById("a_serial_no").value = '';
        document.getElementById("a_defect_category").value = '';
        document.getElementById("a_defect_details").value = '';
        document.getElementById("a_sequence_no").value = '';
        document.getElementById("a_connector_no").value = '';
        document.getElementById("a_repaired_by").value = '';
        document.getElementById("a_verified_by").value = '';
    }

    function refresh_page() {
        location.reload();
    }




</script>