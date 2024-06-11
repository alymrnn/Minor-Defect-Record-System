<script type="text/javascript">
    $(document).ready(function () {
        fetch_defect_category();
        load_defect_table(1);

        $('#a_scan_qr').prop('disabled', true).css('background', '#F1F1F1');

        $('#a_defect_details').prop('disabled', true).css('background', '#F1F1F1');

        $('#a_defect_category').change(function () {
            const select_defect_category = $(this).val();
            fetch_defect_details(select_defect_category);
        });
    });

    document.getElementById("scan_product_name").addEventListener("keyup", e => {
        load_defect_table(1);
    });

    // Table Responsive Scroll Event for Load More
    document.getElementById("list_of_defect_res").addEventListener("scroll", function () {
        var scrollTop = document.getElementById("list_of_defect_res").scrollTop;
        var scrollHeight = document.getElementById("list_of_defect_res").scrollHeight;
        var offsetHeight = document.getElementById("list_of_defect_res").offsetHeight;

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

        $.ajax({
            url: 'process/index_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'count_defect_list',
                scan_product_name: scan_product_name,
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

        var current_page = parseInt(sessionStorage.getItem('defect_table_pagination'));

        $.ajax({
            url: 'process/index_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'defect_list_last_page',
                scan_product_name: scan_product_name,
            },
            success: function (response) {
                sessionStorage.setItem('last_page', response);
                let total = parseInt(sessionStorage.getItem('count_rows'));
                var next_page = current_page + 1;

                if (next_page > parseInt(response) || total < 1) {
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
        var scan_product_name = document.getElementById('scan_product_name').value;

        var scan_product_name_1 = sessionStorage.getItem('scan_product_name');

        if (current_page > 1) {
            switch (true) {
                case scan_product_name !== scan_product_name_1:
                    scan_product_name = scan_product_name_1;
                    break;
                default:
            }
        } else {
            sessionStorage.setItem('scan_product_name', scan_product_name);
        }
        $.ajax({
            url: 'process/index_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'load_defect_list',
                scan_product_name: scan_product_name,
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

    const fetch_defect_category = () => {
        $.ajax({
            url: 'process/index_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'fetch_defect_category',
            },
            success: function (response) {
                $('#a_defect_category').html(response);
            },
        });
    }

    const fetch_defect_details = (category_code) => {
        $.ajax({
            url: 'process/index_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'fetch_defect_details',
                category_code: category_code
            },
            success: function (response) {
                $('#a_defect_details').html(response);
                $('#a_defect_details').prop('disabled', false).css('background', '#FFF');
            },
        });
    }


    // const load_defect_table = () => {
    //     $.ajax({
    //         url: 'process/index_p.php',
    //         type: 'POST',
    //         cache: false,
    //         data: {
    //             method: 'defect_list'
    //         }, success: function (response) {
    //             $('#list_of_defect').html(response);
    //             $('#spinner').fadeOut();
    //         }
    //     });
    // }

    function handleCarMakerChange(selectOpt) {
        var carMaker = selectOpt.value;
        $('#a_scan_qr').off('keyup');

        switch (carMaker) {
            case 'Honda':
                enableScanAndAttachHandler(handleHondaScan);
                break;
            case 'Mazda':
                enableScanAndAttachHandler(handleMazdaScan);
                break;
            case 'Nissan':
                enableScanAndAttachHandler(handleNissanScan);
                break;
            case 'Subaru':
                enableScanAndAttachHandler(handleSubaruScan);
                break;
            case 'Suzuki':
                enableScanAndAttachHandler(handleSuzukiScan);
                break;
            case 'Toyota':
                enableScanAndAttachHandler(handleToyotaScan);
                break;
            case 'Daihatsu':
                enableScanAndAttachHandler(handleDaihatsuScan);
                break;
            default:
                $('#a_scan_qr').prop('disabled', true).css('background', '#F1F1F1');
                break;
        }
    }

    function enableScanAndAttachHandler(scanHandler) {
        $('#a_scan_qr').prop('disabled', false).css('background', '#FFF');
        scanHandler();
    }

    function handleSuzukiScan() {
        $('#a_scan_qr').on('keyup', function (e) {
            if (e.which === 13) {
                e.preventDefault();
                var qrCode = this.value;
                if (qrCode.length === 50) {
                    $('#a_product_name').val(qrCode.substring(10, 35));
                    $('#a_lot_no').val(qrCode.substring(35, 41));
                    $('#a_serial_no').val(qrCode.substring(41, 50));
                    this.value = '';
                } else {
                    // Handle invalid QR code length
                }
            }
        });
    }

    // WORKING SCAN IN LIVE
    function handleSuzukiScan() {
        document.getElementById('a_scan_qr').addEventListener('keyup', function (e) {
            if (e.which === 13) {
                e.preventDefault();
                var qrCode = this.value;
                if (qrCode.length === 50) {
                    document.getElementById('a_product_name').value = qrCode.substring(10, 35);
                    document.getElementById('a_lot_no').value = qrCode.substring(35, 41);
                    document.getElementById('a_serial_no').value = qrCode.substring(41, 50);

                    this.value = '';
                }
                else {

                }
            }
        });
    }

    function handleMazdaScan() {
        document.getElementById('a_scan_qr').addEventListener('keyup', function (e) {
            if (e.which === 13) {
                e.preventDefault();
                var qrCode = this.value;
                if (qrCode.length === 50) {
                    document.getElementById('a_product_name').value = qrCode.substring(10, 35);
                    document.getElementById('a_lot_no').value = qrCode.substring(35, 41);
                    document.getElementById('a_serial_no').value = qrCode.substring(41, 50);

                    this.value = '';
                }
                else {

                }
            }
        });
    }

    function handleDaihatsuScan() {
        document.getElementById('a_scan_qr').addEventListener('keyup', function (e) {
            if (e.which === 13) {
                e.preventDefault();
                var qrCode = this.value;
                if (qrCode.length === 50) {
                    document.getElementById('a_product_name').value = qrCode.substring(10, 35);
                    document.getElementById('a_lot_no').value = qrCode.substring(35, 41);
                    document.getElementById('a_serial_no').value = qrCode.substring(41, 50);

                    this.value = '';
                }
                else {

                }
            }
        });
    }

    function handleHondaScan() {
        document.getElementById('a_scan_qr').addEventListener('keyup', function (e) {
            if (e.which === 13) {
                e.preventDefault();
                var qrCode = this.value;
                if (qrCode.length === 50) {
                    document.getElementById('a_product_name').value = qrCode.substring(10, 35);
                    document.getElementById('a_lot_no').value = qrCode.substring(35, 41);
                    document.getElementById('a_serial_no').value = qrCode.substring(41, 50);

                    this.value = '';
                }
                else {

                }
            }
        });
    }

    function handleNissanScan() {
        document.getElementById('a_scan_qr').addEventListener('keyup', function (e) {
            if (e.which === 13) {
                e.preventDefault();
                var qrCode = this.value;
                if (qrCode.length === 50) {
                    document.getElementById('a_product_name').value = qrCode.substring(10, 35);
                    document.getElementById('a_lot_no').value = qrCode.substring(35, 41);
                    document.getElementById('a_serial_no').value = qrCode.substring(41, 50);

                    this.value = '';
                }
                else {

                }
            }
        });
    }

    function handleSubaruScan() {
        document.getElementById('a_scan_qr').addEventListener('keyup', function (e) {
            if (e.which === 13) {
                e.preventDefault();
                var qrCode = this.value;
                if (qrCode.length === 50) {
                    document.getElementById('a_product_name').value = qrCode.substring(10, 35);
                    document.getElementById('a_lot_no').value = qrCode.substring(35, 41);
                    document.getElementById('a_serial_no').value = qrCode.substring(41, 50);

                    this.value = '';
                }
                else {

                }
            }
        });
    }

    function handleToyotaScan() {
        document.getElementById('a_scan_qr').addEventListener('keyup', function (e) {
            if (e.which === 13) {
                e.preventDefault();
                var qrCode = this.value;
                if (qrCode.length === 50) {
                    document.getElementById('a_product_name').value = qrCode.substring(10, 35);
                    document.getElementById('a_lot_no').value = qrCode.substring(35, 41);
                    document.getElementById('a_serial_no').value = qrCode.substring(41, 50);

                    this.value = '';
                }
                else {

                }
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

        document.getElementById("scan_qr").value = '';
        document.getElementById("scan_product_name").value = '';
        document.getElementById("scan_lot_no").value = '';
        document.getElementById("scan_serial_no").value = '';
        document.getElementById("search_process").value = '';
        document.getElementById("search_line_no").value = '';
        document.getElementById("search_date_from").value = '';
        document.getElementById("search_date_to").value = '';
        document.getElementById("search_defect_category").value = '';
        document.getElementById("search_defect_details").value = '';

        load_defect_table(1);
    }

    function refresh_page() {
        location.reload();
    }
</script>