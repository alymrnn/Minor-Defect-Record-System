<script type="text/javascript">
    $(document).ready(function () {
        fetch_defect_category();
        fetch_search_defect_category();
        fetch_search_defect_details();
        fetch_process();
        load_defect_table(1);

        // $('#a_scan_qr').prop('disabled', true).css('background', '#F1F1F1');

        $('#a_defect_details').prop('disabled', true).css('background', '#DDD');

        $('#a_defect_category').change(function () {
            const select_defect_category = $(this).val();
            if (select_defect_category === '') {
                $('#a_defect_details').prop('disabled', true).css('background', '#DDD').val('');
            } else {
                fetch_defect_details(select_defect_category);
            }
        });

        get_inspection_details();
        check_ip_address();

        $('#add_defect_record').on('shown.bs.modal', function () {
            set_current_date_time();
            load_stored_inspection_details();
            clear_add_defect_record();
        });

        // $('#search_defect_details').prop('disabled', true).css('background', '#F1F1F1');

        // $('#search_defect_category').change(function () {
        //     const select_search_defect_category = $(this).val();
        //     if (select_search_defect_category === '') {
        //         $('#search_defect_details').prop('disabled', true).css('background', '#F1F1F1').val('');
        //     } else {
        //         fetch_search_defect_details(select_search_defect_category);
        //     }
        // });
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

    document.getElementById("search_process").addEventListener("change", e => {
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

    document.getElementById('scan_qr').addEventListener('input', function (e) {
        var qr_code_scan = this.value;

        if (qr_code_scan.length === 50) {
            const product_name_field = document.getElementById('scan_product_name');
            const lot_no_field = document.getElementById('scan_lot_no');
            const serial_no_field = document.getElementById('scan_serial_no');

            if (product_name_field && lot_no_field && serial_no_field) {
                product_name_field.value = qr_code_scan.substring(10, 35);
                lot_no_field.value = qr_code_scan.substring(35, 41);
                serial_no_field.value = qr_code_scan.substring(41, 50);

                load_defect_table(1);
            } else {
                console.error("One or more elements were not found in the DOM.");
            }

            this.value = '';
        } else if (qr_code_scan.length > 50) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid QR Code',
                text: 'Invalid',
                showConfirmButton: false,
                timer: 1000
            });
            this.value = '';
        }
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
    };

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
    };

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

        var current_page = parseInt(sessionStorage.getItem('defect_table_pagination'));

        $.ajax({
            url: 'process/index_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'defect_list_last_page',
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
        var scan_product_name = document.getElementById('scan_product_name').value;
        var scan_lot_no = document.getElementById('scan_lot_no').value;
        var scan_serial_no = document.getElementById('scan_serial_no').value;
        var search_process = document.getElementById('search_process').value;
        var search_line_no = document.getElementById('search_line_no').value;
        var search_date_from = document.getElementById('search_date_from').value;
        var search_date_to = document.getElementById('search_date_to').value;
        var search_defect_category = document.getElementById('search_defect_category').value;
        var search_defect_details = document.getElementById('search_defect_details').value;

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
                method: 'load_defect_list',
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
    };

    const fetch_search_defect_category = () => {
        $.ajax({
            url: 'process/index_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'fetch_search_defect_category',
            },
            success: function (response) {
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
            success: function (response) {
                $('#search_defect_details').html(response);
            },
        });
    };

    // const fetch_search_defect_details = (search_category_value) => {
    //     $.ajax({
    //         url: 'process/index_p.php',
    //         type: 'POST',
    //         cache: false,
    //         data: {
    //             method: 'fetch_search_defect_details',
    //             search_category_value: search_category_value
    //         },
    //         success: function (response) {
    //             $('#search_defect_details').html(response);
    //             $('#search_defect_details').prop('disabled', false).css('background', '#FFF');
    //         },
    //     });
    // }

    const fetch_process = () => {
        $.ajax({
            url: 'process/index_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'fetch_process',
            },
            success: function (response) {
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
            success: function (response) {
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
            success: function (response) {
                $('#a_defect_details').html(response);
                $('#a_defect_details').prop('disabled', false).css('background', '#FFF');
            },
        });
    };

    function handleCarMakerChange(selectOpt) {
        var carMaker = selectOpt.value;
        $('#a_scan_qr').off('keyup');

        switch (carMaker) {
            case 'HONDA':
                enableScanAndAttachHandler(handleHondaScan);
                break;
            case 'MAZDA':
                enableScanAndAttachHandler(handleMazdaScan);
                break;
            case 'NISSAN':
                enableScanAndAttachHandler(handleNissanScan);
                break;
            case 'SUBARU':
                enableScanAndAttachHandler(handleSubaruScan);
                break;
            case 'SUZUKI':
                enableScanAndAttachHandler(handleSuzukiScan);
                break;
            case 'TOYOTA':
                enableScanAndAttachHandler(handleToyotaScan);
                break;
            case 'DAIHATSU':
                enableScanAndAttachHandler(handleDaihatsuScan);
                break;
            default:
                // $('#a_scan_qr').prop('disabled', true).css('background', '#F1F1F1');
                $('#a_scan_qr').prop('disabled', false).css('background', '#FFF');
                break;
        }
    };

    function enableScanAndAttachHandler(scanHandler) {
        // $('#a_scan_qr').prop('disabled', false).css('background', '#FFF');
        scanHandler();
    };

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
    };

    // WORKING SCAN IN LIVE
    function handleSuzukiScan() {
        document.getElementById('a_scan_qr').addEventListener('keyup', function (e) {
            if (e.which === 13) {
                e.preventDefault();
                var qrCode = this.value;
                if (qrCode.length === 50) {
                    var product_name = document.getElementById('a_product_name');
                    var lot_no = document.getElementById('a_lot_no');
                    var serial_no = document.getElementById('a_serial_no');

                    product_name.value = qrCode.substring(10, 35);
                    var input_event = new Event('input', { bubbles: true });
                    product_name.dispatchEvent(input_event);

                    lot_no.value = qrCode.substring(35, 41);
                    lot_no.dispatchEvent(input_event);

                    serial_no.value = qrCode.substring(41, 50);
                    serial_no.dispatchEvent(input_event);

                    this.value = '';
                }
                else {

                }
            }
        });
    };

    function handleMazdaScan() {
        document.getElementById('a_scan_qr').addEventListener('keyup', function (e) {
            if (e.which === 13) {
                e.preventDefault();
                var qrCode = this.value;
                if (qrCode.length === 50) {
                    var product_name = document.getElementById('a_product_name');
                    var lot_no = document.getElementById('a_lot_no');
                    var serial_no = document.getElementById('a_serial_no');

                    product_name.value = qrCode.substring(10, 35);
                    var input_event = new Event('input', { bubbles: true });
                    product_name.dispatchEvent(input_event);

                    lot_no.value = qrCode.substring(35, 41);
                    lot_no.dispatchEvent(input_event);

                    serial_no.value = qrCode.substring(41, 50);
                    serial_no.dispatchEvent(input_event);

                    this.value = '';
                }
                else {

                }
            }
        });
    };

    function handleDaihatsuScan() {
        document.getElementById('a_scan_qr').addEventListener('keyup', function (e) {
            if (e.which === 13) {
                e.preventDefault();
                var qrCode = this.value;
                if (qrCode.length === 50) {
                    var product_name = document.getElementById('a_product_name');
                    var lot_no = document.getElementById('a_lot_no');
                    var serial_no = document.getElementById('a_serial_no');

                    product_name.value = qrCode.substring(10, 35);
                    var input_event = new Event('input', { bubbles: true });
                    product_name.dispatchEvent(input_event);

                    lot_no.value = qrCode.substring(35, 41);
                    lot_no.dispatchEvent(input_event);

                    serial_no.value = qrCode.substring(41, 50);
                    serial_no.dispatchEvent(input_event);

                    this.value = '';
                }
                else {

                }
            }
        });
    };

    function handleHondaScan() {
        document.getElementById('a_scan_qr').addEventListener('keyup', function (e) {
            if (e.which === 13) {
                e.preventDefault();
                var qrCode = this.value;
                if (qrCode.length === 50) {
                    var product_name = document.getElementById('a_product_name');
                    var lot_no = document.getElementById('a_lot_no');
                    var serial_no = document.getElementById('a_serial_no');

                    product_name.value = qrCode.substring(10, 35);
                    var input_event = new Event('input', { bubbles: true });
                    product_name.dispatchEvent(input_event);

                    lot_no.value = qrCode.substring(35, 41);
                    lot_no.dispatchEvent(input_event);

                    serial_no.value = qrCode.substring(41, 50);
                    serial_no.dispatchEvent(input_event);

                    this.value = '';
                }
                else {

                }
            }
        });
    };

    function handleNissanScan() {
        document.getElementById('a_scan_qr').addEventListener('keyup', function (e) {
            if (e.which === 13) {
                e.preventDefault();
                var qrCode = this.value;
                if (qrCode.length === 50) {
                    var product_name = document.getElementById('a_product_name');
                    var lot_no = document.getElementById('a_lot_no');
                    var serial_no = document.getElementById('a_serial_no');

                    product_name.value = qrCode.substring(10, 35);
                    var input_event = new Event('input', { bubbles: true });
                    product_name.dispatchEvent(input_event);

                    lot_no.value = qrCode.substring(35, 41);
                    lot_no.dispatchEvent(input_event);

                    serial_no.value = qrCode.substring(41, 50);
                    serial_no.dispatchEvent(input_event);

                    this.value = '';
                }
                else {

                }
            }
        });
    };

    function handleSubaruScan() {
        document.getElementById('a_scan_qr').addEventListener('keyup', function (e) {
            if (e.which === 13) {
                e.preventDefault();
                var qrCode = this.value;
                if (qrCode.length === 50) {
                    var product_name = document.getElementById('a_product_name');
                    var lot_no = document.getElementById('a_lot_no');
                    var serial_no = document.getElementById('a_serial_no');

                    product_name.value = qrCode.substring(10, 35);
                    var input_event = new Event('input', { bubbles: true });
                    product_name.dispatchEvent(input_event);

                    lot_no.value = qrCode.substring(35, 41);
                    lot_no.dispatchEvent(input_event);

                    serial_no.value = qrCode.substring(41, 50);
                    serial_no.dispatchEvent(input_event);

                    this.value = '';
                }
                else {

                }
            }
        });
    };

    function handleToyotaScan() {
        document.getElementById('a_scan_qr').addEventListener('keyup', function (e) {
            if (e.which === 13) {
                e.preventDefault();
                var qrCode = this.value;
                if (qrCode.length === 50) {
                    var product_name = document.getElementById('a_product_name');
                    var lot_no = document.getElementById('a_lot_no');
                    var serial_no = document.getElementById('a_serial_no');

                    product_name.value = qrCode.substring(10, 35);
                    var input_event = new Event('input', { bubbles: true });
                    product_name.dispatchEvent(input_event);

                    lot_no.value = qrCode.substring(35, 41);
                    lot_no.dispatchEvent(input_event);

                    serial_no.value = qrCode.substring(41, 50);
                    serial_no.dispatchEvent(input_event);

                    this.value = '';
                } else {

                }
            }
        });
    };


    // highlight input field when empty
    document.getElementById("a_date_detected").addEventListener("input", function () {
        var date_detected = this;
        date_detected.classList.remove('highlight');
        document.getElementById("dateDetectedError").style.display = 'none';
    });

    document.getElementById("a_car_maker").addEventListener("input", function () {
        var car_maker = this;
        car_maker.classList.remove('highlight');
        document.getElementById("carMakerError").style.display = 'none';
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

    document.getElementById("a_group").addEventListener("input", function () {
        var group = this;
        group.classList.remove('highlight');
        document.getElementById("groupError").style.display = 'none';
    });

    document.getElementById("a_shift").addEventListener("input", function () {
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
        lot_no.classList.remove('highlight');
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
        var car_maker = document.getElementById("a_car_maker").value;
        var car_model = document.getElementById("a_car_model").value;
        var line_no = document.getElementById("a_line_no").value;
        var process = document.getElementById("a_process").value;
        var group = document.getElementById("a_group").value;
        var shift = document.getElementById("a_shift").value;

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

        var ip_address = document.getElementById("a_ip_address").value;

        let hasError = false;

        if (date_detected === '') {
            document.getElementById("a_date_detected").classList.add('highlight');
            document.getElementById("dateDetectedError").style.display = 'block';
            hasError = true;
        }

        if (car_maker === '') {
            document.getElementById("a_car_maker").classList.add('highlight');
            document.getElementById("carMakerError").style.display = 'block';
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

        if (group === '') {
            document.getElementById("a_group").classList.add('highlight');
            document.getElementById("groupError").style.display = 'block';
            hasError = true;
        }

        if (shift === '') {
            document.getElementById("a_shift").classList.add('highlight');
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
                repaired_by: repaired_by,
                verified_by: verified_by,
                defect_id: defect_id,
                ip_address: ip_address
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
                    $('#a_car_maker').val('');
                    $('#a_car_model').val('');
                    $('#a_line_no').val('');
                    $('#a_process').val('');
                    $('#a_group').val('');
                    $('#a_shift').val('');
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
                    $('#a_ip_address').val('');

                    load_defect_table(1);

                    $('#add_defect_record').modal('hide');
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
        document.getElementById("a_group").value = '';
        document.getElementById("a_product_name").value = '';
        document.getElementById("a_lot_no").value = '';
        document.getElementById("a_serial_no").value = '';
        document.getElementById("a_defect_category").value = '';
        document.getElementById("a_sequence_no").value = '';
        document.getElementById("a_connector_no").value = '';
        document.getElementById("a_repaired_by").value = '';
        document.getElementById("a_verified_by").value = '';

        const defect_details_input = document.getElementById("a_defect_details");
        defect_details_input.value = '';
        defect_details_input.disabled = true;
        defect_details_input.style.backgroundColor = "#DDD"
    };

    const clear_search_defect_record = () => {
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
        // location.reload();
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

        window.open(
            'process/exp_defect_record.php?scan_product_name=' + scan_product_name +
            "&scan_lot_no=" + scan_lot_no +
            "&scan_serial_no=" + scan_serial_no +
            "&search_process=" + search_process +
            "&search_line_no=" + search_line_no +
            "&search_date_from=" + search_date_from +
            "&search_date_to=" + search_date_to +
            "&search_defect_category=" + search_defect_category +
            "&search_defect_details=" + search_defect_details,
            '_blank'
        );
    };

    // const get_inspection_details = () => {
    //     const ip_address = $('#a_ip_address').val();

    //     $.ajax({
    //         url: 'process/inspection_p.php',
    //         type: 'GET',
    //         data: {
    //             method: 'get_inspection_details',
    //             ip_address: ip_address
    //         },
    //         success: function (response) {
    //             // console.log(response);

    //             const data = JSON.parse(response);
    //             $('#a_car_maker').val(data.car_maker);
    //             $('#a_car_model').val(data.car_model);
    //             $('#a_line_no').val(data.line_no);
    //             $('#a_process').val(data.process);

    //             sessionStorage.setItem('car_maker', data.car_maker);
    //             sessionStorage.setItem('car_model', data.car_model);
    //             sessionStorage.setItem('line_no', data.line_no);
    //             sessionStorage.setItem('process', data.process);

    //             handleCarMakerChange(document.getElementById('a_car_maker'));

    //             $('#a_car_maker').prop('disabled', true).css('background', '#F1F1F1');
    //         },
    //         error: function (xhr, status, error) {
    //             console.error('AJAX Error: ', status, error);
    //         }
    //     });
    // };

    const check_ip_address = () => {
        const add_record_btn = document.getElementById('add_record_btn');
        const ip_address = '<?= $_SERVER['REMOTE_ADDR']; ?>';

        $.ajax({
            url: 'process/inspection_p.php',
            type: 'GET',
            data: {
                method: 'check_ip_address',
                ip_address: ip_address
            },
            success: function (response) {
                const data = JSON.parse(response);
                if (!data.success) {
                    add_record_btn.disabled = true;
                    add_record_btn.title = data.error;
                }
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
            }
        });
    };

    const get_inspection_details = () => {
        const ip_address = $('#a_ip_address').val();

        $.ajax({
            url: 'process/inspection_p.php',
            type: 'GET',
            data: {
                method: 'get_inspection_details',
                ip_address: ip_address
            },
            success: function (response) {
                const data = JSON.parse(response);
                if (data.success) {
                    $('#a_car_maker').val(data.car_maker);
                    $('#a_car_model').val(data.car_model);
                    $('#a_line_no').val(data.line_no);
                    $('#a_process').val(data.process);

                    sessionStorage.setItem('car_maker', data.car_maker);
                    sessionStorage.setItem('car_model', data.car_model);
                    sessionStorage.setItem('line_no', data.line_no);
                    sessionStorage.setItem('process', data.process);

                    $('#a_car_maker').prop('disabled', true).css('background', '#F1F1F1');
                    handleCarMakerChange(document.getElementById('a_car_maker'));
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'IP address is not registered.',
                        text: 'Register IP address to fetch inspection details.',
                        showConfirmButton: false,
                        color: '#525252',
                        background: '#FFFDF2',
                        backdrop: 'rgba(0, 0, 0, 0.8)'
                    });
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error: ', status, error);
                Swal.fire({
                    icon: 'error',
                    title: 'AJAX Error',
                    showConfirmButton: true
                });
            }
        });
    };

    const load_stored_inspection_details = () => {
        const car_maker = sessionStorage.getItem('car_maker');
        const car_model = sessionStorage.getItem('car_model');
        const line_no = sessionStorage.getItem('line_no');
        const process = sessionStorage.getItem('process');

        if (car_maker) $('#a_car_maker').val(car_maker);
        if (car_model) $('#a_car_model').val(car_model);
        if (line_no) $('#a_line_no').val(line_no);
        if (process) $('#a_process').val(process);
    };

    const set_current_date_time = () => {
        const date_input = document.getElementById('a_date_detected');
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');

        let shift = '';
        if ((hours > 6 && hours < 18) || (hours === 6 && minutes >= 0 && seconds >= 0) || (hours === 17 && minutes <= 59 && seconds <= 59)) {
            shift = 'DS';
        } else {
            shift = 'NS';
        }

        const date_time_format = `${year}-${month}-${day}T${hours}:${minutes}:${seconds}`;
        date_input.value = date_time_format;

        document.getElementById('a_shift').value = shift;
    };

</script>