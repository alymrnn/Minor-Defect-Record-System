<script type="text/javascript">
    $(document).ready(function() {
        fetch_defect_category();
        fetch_search_defect_category();
        fetch_search_defect_details();
        fetch_search_process();

        toggleQRField();

        $('#a_process').prop('disabled', true).css('background', '#DDD');
        $('#a_defect_details').prop('disabled', true).css('background', '#DDD');
        $('#a_treatment_content_defect').prop('disabled', true).css('background', '#F1F1F1');

        $('#a_defect_category').change(function() {
            const select_defect_category = $(this).val();
            $('#a_treatment_content_defect').val('');
            if (select_defect_category === '') {
                $('#a_defect_details').prop('disabled', true).css('background', '#DDD').val('');
                $('#a_treatment_content_defect').prop('disabled', true).css('background', '#F1F1F1').val('');
            } else {
                fetch_defect_details(select_defect_category);
            }
        });

        $('#a_defect_details').change(function() {
            const select_defect_details = $(this).val();
            if (select_defect_details === '') {
                $('#a_treatment_content_defect').prop('disabled', true).css('background', '#F1F1F1').val('');
            } else {
                fetch_defect_treatment();
            }
        });

        $('#a_line_no').on('keypress', function(e) {
            if (e.which === 13) {
                get_inspection_details();
            }
        });

        $('#a_line_no').on('input', function() {
            if (!$(this).val()) {
                $('#a_car_maker').val('');
                $('#a_car_model').val('');
                $('#a_process').prop('disabled', true).css('background', '#DDD');
            }
        });

        $('#add_defect_record').on('shown.bs.modal', function() {
            clear_add_defect_record();
        });

        var currentDate = new Date().toISOString().split('T')[0];
        $('#search_date_from').val(currentDate);
        $('#search_date_to').val(currentDate);

        load_defect_table(1);
    });

    const toggleQRField = () => {
        const lineNo = $('#a_line_no').val();
        const isValidLineNo = /^[0-9]{4}$/.test(lineNo); // Check if it contains exactly 4 digits
        if (isValidLineNo) {
            $('#a_scan_qr').prop('disabled', false).css('background', '#FFF');
        } else {
            $('#a_scan_qr').prop('disabled', true).css('background', '#DDD');
        }
    };

    $('#a_line_no').on('keyup change', function() {
        toggleQRField();
    });

    const repairedBy = document.getElementById('a_repaired_by');
    const verifiedBy = document.getElementById('a_verified_by');
    let inputTimeout;

    const handleBarcodeInput = (inputField, nextField) => {
        clearTimeout(inputTimeout);

        // Set a new timeout to detect when input is done
        inputTimeout = setTimeout(() => {
            if (inputField.value.trim().length > 0) {
                nextField.focus();
            }
        }, 1000);
    };

    repairedBy.addEventListener('input', () => handleBarcodeInput(repairedBy, verifiedBy));
    verifiedBy.addEventListener('input', () => {
        clearTimeout(inputTimeout);
    });

    const aGroup = document.getElementById('a_group');
    const aScanQR = document.getElementById('a_scan_qr');

    aGroup.addEventListener('change', () => {
        if (aGroup.value) {
            aScanQR.focus();
        }
    });

    aScanQR.addEventListener('input', () => {
        console.log("QR scanned: ", aScanQR.value.trim());
    });

    // Table Responsive Scroll Event for Load More
    document.getElementById("list_of_defect_res").addEventListener("scroll", function() {
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
    };

    const count_defect = () => {
        var scan_qr = sessionStorage.getItem('scan_qr');
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
                scan_qr: scan_qr,
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
            success: function(response) {
                sessionStorage.setItem('count_rows', response);
                var count = `<span style="font-size: 14px;">Total Record: ${response}</span>`;
                $('#defect_table_info').html(count);

                if (response > 0) {
                    load_defect_last_page();
                } else {
                    document.getElementById("btnNextPage").style.display = "none";
                    document.getElementById("btnNextPage").setAttribute('disabled', true);
                }
            }
        });
    };

    const load_defect_last_page = () => {
        var scan_qr = sessionStorage.getItem('scan_qr');
        var scan_product_name = sessionStorage.getItem('scan_product_name');
        var scan_lot_no = sessionStorage.getItem('scan_lot_no');
        var scan_serial_no = sessionStorage.getItem('scan_serial_no');
        var search_process = sessionStorage.getItem('search_process');
        var search_line_no = sessionStorage.getItem('search_line_no');
        var search_date_from = sessionStorage.getItem('search_date_from');
        var search_date_to = sessionStorage.getItem('search_date_to');
        var search_defect_category = sessionStorage.getItem('search_defect_category');
        var search_defect_details = sessionStorage.getItem('search_defect_details');

        var current_page = parseInt(sessionStorage.getItem('defect_table_pagination'));

        $.ajax({
            url: 'process/index_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'defect_list_last_page',
                scan_qr: scan_qr,
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
            success: function(response) {
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
    };

    const load_defect_table = current_page => {
        var scan_qr = document.getElementById('scan_qr').value;
        var scan_product_name = document.getElementById('scan_product_name').value;
        var scan_lot_no = document.getElementById('scan_lot_no').value;
        var scan_serial_no = document.getElementById('scan_serial_no').value;
        var search_process = document.getElementById('search_process').value;
        var search_line_no = document.getElementById('search_line_no').value;
        var search_date_from = document.getElementById('search_date_from').value;
        var search_date_to = document.getElementById('search_date_to').value;
        var search_defect_category = document.getElementById('search_defect_category').value;
        var search_defect_details = document.getElementById('search_defect_details').value;

        var scan_qr_1 = sessionStorage.getItem('scan_qr');
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
                case scan_qr !== scan_qr_1:
                case scan_lot_no !== scan_lot_no_1:
                case scan_serial_no !== scan_serial_no_1:
                case search_process !== search_process_1:
                case search_line_no !== search_line_no_1:
                case search_date_from !== search_date_from_1:
                case search_date_to !== search_date_to_1:
                case search_defect_category !== search_defect_category_1:
                case search_defect_details !== search_defect_details_1:
                    scan_product_name = scan_product_name_1;
                    scan_qr = scan_qr_1;
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
            sessionStorage.setItem('scan_qr', scan_qr);
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
                method: 'load_defect_list',
                scan_qr: scan_qr,
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
                var loading = `<tr id="loading"><td colspan="18" style="text-align:center;"><div class="spinner-border text-dark role="status"><span class="sr-only">Loading...</span></div></td></tr>`;
                if (current_page == 1) {
                    document.getElementById("list_of_defect").innerHTML = loading;
                } else {
                    $('#defect_table tbody').append(loading);
                }
            },
            success: function(response) {
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
    };

    const fetch_search_defect_category = () => {
        $.ajax({
            url: 'process/index_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'fetch_search_defect_category',
            },
            success: function(response) {
                $('#search_defect_category').html(response);
            },
        });
    };

    const fetch_search_defect_details = () => {
        $.ajax({
            url: 'process/index_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'fetch_search_defect_details'
            },
            success: function(response) {
                $('#search_defect_details').html(response);
            },
        });
    };

    const fetch_search_process = () => {
        $.ajax({
            url: 'process/index_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'fetch_search_process',
            },
            success: function(response) {
                $('#search_process').html(response);
            },
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
            success: function(response) {
                $('#a_defect_category').html(response);
            },
        });
    };

    const fetch_defect_details = (category_value) => {
        $.ajax({
            url: 'process/index_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'fetch_defect_details',
                category_value: category_value
            },
            success: function(response) {
                $('#a_defect_details').html(response);
                $('#a_defect_details').prop('disabled', false).css('background', '#FFF');
            },
        });
    };

    const fetch_defect_treatment = () => {
        const treatment = $('#a_defect_details option:selected').data('treatment');
        if (treatment) {
            $('#a_treatment_content_defect').val(treatment);
            $('#a_treatment_content_defect').prop('disabled', true).css('background', '#F1F1F1');
        } else {
            $('#a_treatment_content_defect').val('');
            $('#a_treatment_content_defect').prop('disabled', true).css('background', '#F1F1F1');
        }
    };

    const add_defect_record = () => {
        const fieldIds = [
            "a_date_detected", "a_car_maker", "a_car_model", "a_line_no", "a_process",
            "a_group", "a_shift", "a_product_name", "a_lot_no", "a_serial_no",
            "a_defect_category", "a_defect_details", "a_sequence_no", "a_connector_no",
            "a_treatment_content_defect", "a_repaired_by", "a_verified_by", "a_ip_address"
        ];

        let hasEmpty = false;

        fieldIds.forEach(id => {
            const input = document.getElementById(id);
            if (input && input.value.trim() === '') {
                input.style.border = '1px solid #bc4749';
                hasEmpty = true;
            } else if (input) {
                input.style.border = '';
            }
        });

        if (hasEmpty) {
            Swal.fire({
                icon: 'info',
                title: 'Missing Required Fields',
                text: 'Please fill in all required fields before submitting.',
            });
            return;
        }

        var date_detected = document.getElementById("a_date_detected").value;
        var car_maker = document.getElementById("a_car_maker").value;
        var car_model = document.getElementById("a_car_model").value;
        var line_no = document.getElementById("a_line_no").value;
        var process = document.getElementById("a_process").value;
        var group = document.getElementById("a_group").value;
        var shift = document.getElementById("a_shift").value;

        var product_name = document.getElementById("a_product_name");
        var lot_no = document.getElementById("a_lot_no");
        var serial_no = document.getElementById("a_serial_no");

        var defect_category = document.getElementById("a_defect_category").value;
        var defect_details = document.getElementById("a_defect_details").value;
        var sequence_no = document.getElementById("a_sequence_no").value;
        var connector_no = document.getElementById("a_connector_no").value;
        var treatment_content_defect = document.getElementById("a_treatment_content_defect").value;
        var repaired_by = document.getElementById("a_repaired_by").value;
        var verified_by = document.getElementById("a_verified_by").value;
        var defect_id = document.getElementById('defect_id_no').value;

        var ip_address = document.getElementById("a_ip_address").value;

        var nameplate_value = document.getElementById("nameplate_value").value;

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
                car_maker: car_maker,
                car_model: car_model,
                line_no: line_no,
                process: process,
                group: group,
                shift: shift,
                product_name: product_name.value,
                lot_no: lot_no.value,
                serial_no: serial_no.value,
                defect_category: defect_category,
                defect_details: defect_details,
                sequence_no: sequence_no,
                connector_no: connector_no,
                treatment_content_defect: treatment_content_defect,
                repaired_by: repaired_by,
                verified_by: verified_by,
                defect_id: defect_id,
                ip_address: ip_address,
                nameplate_value: nameplate_value
            },
            success: function(response) {
                if (response == 'success') {
                    document.getElementById("defect_id_no").value = defect_id;
                    Swal.fire({
                        icon: 'success',
                        title: 'Successfully Recorded',
                        showConfirmButton: false,
                        timer: 1100
                    });
                    $('#a_defect_category').val('');
                    $('#a_defect_details').val('');
                    $('#a_sequence_no').val('');
                    $('#a_connector_no').val('');
                    $('#a_treatment_content_defect').val('');
                    $('#defect_id_no').val('');

                    const defect_details_input = document.getElementById("a_defect_details");
                    defect_details_input.value = '';
                    defect_details_input.disabled = true;
                    defect_details_input.style.backgroundColor = "#DDD"

                    load_defect_table(1);

                    $('#add_defect_record').modal('show');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            }
        });
    };

    const clear_add_defect_record = () => {
        document.getElementById("a_car_maker").value = '';
        document.getElementById("a_car_model").value = '';
        document.getElementById("a_line_no").value = '';
        document.getElementById("a_process").value = '';
        document.getElementById("a_group").value = '';
        document.getElementById("a_product_name").value = '';
        document.getElementById("a_lot_no").value = '';
        document.getElementById("a_serial_no").value = '';
        document.getElementById("a_defect_category").value = '';
        document.getElementById("a_sequence_no").value = '';
        document.getElementById("a_connector_no").value = '';
        document.getElementById("a_treatment_content_defect").value = '';
        document.getElementById("a_repaired_by").value = 'N/A';
        document.getElementById("a_verified_by").value = 'N/A';
        document.getElementById("nameplate_value").value = '';
        document.getElementById("a_scan_qr").value = '';

        document.getElementById("a_defect_details").value = '';
        $('#a_defect_details').prop('disabled', true).css('background', '#DDD');
        $('#a_process').prop('disabled', true).css('background', '#DDD');

        $('#a_process').empty().append('<option value="" disabled selected>Select Process</option>');
    };

    const clear_search_defect_record = () => {
        document.getElementById("scan_qr").value = '';
        document.getElementById("scan_product_name").value = '';
        document.getElementById("scan_lot_no").value = '';
        document.getElementById("scan_serial_no").value = '';
        document.getElementById("search_process").value = '';
        document.getElementById("search_line_no").value = '';
        // document.getElementById("search_date_from").value = '';
        // document.getElementById("search_date_to").value = '';
        document.getElementById("search_defect_category").value = '';
        document.getElementById("search_defect_details").value = '';

        // load_defect_table(1);
        location.reload();
    };

    function refresh_page() {
        location.reload();
    };

    const export_defect_record = () => {
        var scan_product_name = document.getElementById('scan_product_name').value.trim();
        var scan_lot_no = document.getElementById('scan_lot_no').value.trim();
        var scan_serial_no = document.getElementById('scan_serial_no').value.trim();
        var search_process = document.getElementById('search_process').value.trim();
        var search_line_no = document.getElementById('search_line_no').value.trim();
        var search_date_from = document.getElementById('search_date_from').value.trim();
        var search_date_to = document.getElementById('search_date_to').value.trim();
        var search_defect_category = document.getElementById('search_defect_category').value.trim();
        var search_defect_details = document.getElementById('search_defect_details').value.trim();

        if (search_date_from === '') {
            search_date_from = new Date().toISOString().slice(0, 10);
        }
        if (search_date_to === '') {
            search_date_to = new Date().toISOString().slice(0, 10);
        }

        window.open(
            'process/exp_defect_record.php?scan_product_name=' + encodeURIComponent(scan_product_name) +
            '&scan_lot_no=' + encodeURIComponent(scan_lot_no) +
            '&scan_serial_no=' + encodeURIComponent(scan_serial_no) +
            '&search_process=' + encodeURIComponent(search_process) +
            '&search_line_no=' + encodeURIComponent(search_line_no) +
            '&search_date_from=' + encodeURIComponent(search_date_from) +
            '&search_date_to=' + encodeURIComponent(search_date_to) +
            '&search_defect_category=' + encodeURIComponent(search_defect_category) +
            '&search_defect_details=' + encodeURIComponent(search_defect_details),
            '_blank'
        );
    };

    const get_inspection_details = () => {
        const line_no = $('#a_line_no').val();

        $('#a_process').prop('disabled', true).css('background', '#DDD');

        $.ajax({
            url: 'process/inspection_p.php',
            type: 'GET',
            data: {
                method: 'get_inspection_details',
                line_no: line_no
            },
            success: function(response) {
                const data = JSON.parse(response);
                if (data.success) {
                    if (!data.car_maker || !data.car_model) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'No Car Maker and Model',
                            text: 'Register car maker and model of the line.',
                            showConfirmButton: true
                        });

                        $('#a_process').prop('disabled', true).css('background', '#DDD');
                    } else {
                        $('#a_car_maker').val(data.car_maker);
                        $('#a_car_model').val(data.car_model);

                        $('#a_process').empty().append('<option value="" disabled selected>Select Process</option>');

                        data.processes.forEach(process => {
                            $('#a_process').append(`<option value="${process}">${process}</option>`);
                        });

                        $('#a_process').prop('disabled', false).css('background', '#FFF');
                        $('#a_car_maker').prop('disabled', true).css('background', '#F1F1F1');

                        // Initialize QR handler
                        setupQRHandler(data.qr_settings);
                    }
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: data.error,
                        showConfirmButton: true
                    });

                    $('#a_process').prop('disabled', true).css('background', '#DDD');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error: ', status, error);
                Swal.fire({
                    icon: 'error',
                    title: 'AJAX Error',
                    showConfirmButton: true
                });
            }
        });
    };

    const setupQRHandler = (qr_settings) => {
        const {
            total_length,
            product_name_start,
            product_name_length,
            lot_no_start,
            lot_no_length,
            serial_no_start,
            serial_no_length
        } = qr_settings;

        $('#a_scan_qr').off('keyup').on('keyup', function(e) {
            if (e.which === 13) { // Enter key
                e.preventDefault();
                let qrCode = this.value;

                // Convert settings to integers
                const totalLength = parseInt(total_length, 10);
                const productNameStart = parseInt(product_name_start, 10);
                const productNameLength = parseInt(product_name_length, 10);
                const lotNoStart = parseInt(lot_no_start, 10);
                const lotNoLength = parseInt(lot_no_length, 10);
                const serialNoStart = parseInt(serial_no_start, 10);
                const serialNoLength = parseInt(serial_no_length, 10);

                console.log('Converted Settings:', {
                    totalLength,
                    productNameStart,
                    productNameLength,
                    lotNoStart,
                    lotNoLength,
                    serialNoStart,
                    serialNoLength
                });

                if (qrCode.length === totalLength) {
                    document.getElementById('nameplate_value').value = qrCode;

                    // Extract values using start and length parameters
                    const productName = qrCode.substring(productNameStart, productNameStart + productNameLength).trim();
                    const lotNo = qrCode.substring(lotNoStart, lotNoStart + lotNoLength).trim();
                    const serialNo = qrCode.substring(serialNoStart, serialNoStart + serialNoLength).trim();

                    $('#a_product_name').val(productName);
                    $('#a_lot_no').val(lotNo);
                    $('#a_serial_no').val(serialNo);

                    this.value = '';
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid QR Code',
                        text: `Expected length: ${totalLength}, but received: ${qrCode.length}`,
                    });
                }
            }
        });
    };
</script>