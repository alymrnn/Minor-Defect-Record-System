<script type="text/javascript">
    $(document).ready(function() {
        fetch_search_defect_category();
        fetch_search_defect_details();
        fetch_search_process();

        fetch_line_no();
        fetch_defect_details_code();

        toggleQRField();

        const input = document.getElementById('a_line_no');

        input.addEventListener('input', () => {
            // Remove non-digit characters and limit to 4 digits
            input.value = input.value.replace(/\D/g, '').slice(0, 4);
        });

        $('#qr_settings').prop('disabled', true).css('background', '#DDD');
        $('#a_process').prop('disabled', true).css('background', '#DDD');
        $('#a_defect_category').prop('disabled', true).css('background', '#F1F1F1');
        $('#a_defect_details').prop('disabled', true).css('background', '#F1F1F1');
        $('#a_treatment_content_defect').prop('disabled', true).css('background', '#F1F1F1');

        // // Trigger function when Enter key is pressed (using keydown or keyup)
        // $('#a_line_no').on('keydown', function(e) {
        //     if (e.which === 13) { // 13 is the Enter key
        //         get_inspection_details();
        //     }
        // });

        // // Trigger function when the dropdown loses focus
        // $('#a_line_no').on('blur', function() {
        //     get_inspection_details();
        // });

        // Trigger function when the dropdown value changes
        $('#a_line_no').on('change', function() {
            get_inspection_details();
        });

        $('#a_line_no').on('input', function() {
            if (!$(this).val()) {
                $('#a_car_maker').val('');
                $('#a_car_model').val('');
                $('#a_process').prop('disabled', true).css('background', '#DDD');
            }
        });

        $('#a_defect_details_code').on('input', function() {
            if (!$(this).val()) {
                $('#a_defect_category_code').val('');
                $('#a_defect_category').val('');
                $('#a_defect_details').val('');
                $('#a_treatment_content_defect').val('');
            }
        });

        // // Desktop: Trigger function when Enter key is pressed
        // $('#a_defect_details_code').on('keydown', function(e) {
        //     if (e.key === 'Enter') {
        //         e.preventDefault(); // Prevent form submission or other default behaviors
        //         handleDefectDetailsCode();
        //     }
        // });

        // // Mobile: Trigger function when input loses focus (blur event)
        // $('#a_defect_details_code').on('blur', function() {
        //     handleDefectDetailsCode();
        // });

        // Trigger function when the dropdown value changes (change event)
        $('#a_defect_details_code').on('change', function() {
            handleDefectDetailsCode();
        });

        $('#add_defect_record').on('shown.bs.modal', function() {
            clear_add_defect_record();
        });

        var currentDate = new Date().toISOString().split('T')[0];
        $('#search_date_from').val(currentDate);
        $('#search_date_to').val(currentDate);

        load_defect_table(1);

        document.querySelectorAll('input[name="category_type"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.getElementById('a_category').value = this.value;
            });
        });

        // $('#a_line_no').on('keypress', function(e) {
        //     if (e.which === 13) { // Enter key
        //         let lineNo = $(this).val().trim();

        //         if (lineNo === "2130" || lineNo === "2132") {
        //             $('#category_section').removeClass('d-none');
        //         } else {
        //             $('#category_section').addClass('d-none');
        //             $('input[name="category_type"]').prop('checked', false);
        //         }
        //     }
        // });

        // // Trigger when clearing (backspace/delete or manual clear)
        // $('#a_line_no').on('input', function() {
        //     let lineNo = $(this).val().trim();

        //     if (lineNo === "") {
        //         $('#category_section').addClass('d-none');
        //         $('input[name="category_type"]').prop('checked', false);
        //     }
        // });

        // When Prime or Re-assy is clicked, update hidden input
        // $('input[name="category_type"]').on('change', function() {
        //     $('#a_category').val($(this).val());
        // });
    });

    $(document).on('change', '#a_line_no', function() {
        $('#qr_settings').prop('disabled', false).css('background', '#FFF');
        const line_no = $(this).val();

        $.ajax({
            url: 'process/inspection_p.php',
            type: 'GET',
            data: {
                method: 'get_qr_settings',
                line_no: line_no
            },
            success: function(response) {
                const data = JSON.parse(response);

                $('#qr_settings').empty()
                    .append('<option value="" disabled selected>Select Setting</option>');

                if (data.success) {

                    data.qr_settings.forEach(setting => {
                        $('#qr_settings').append(`
                        <option class='text-xs' value='${JSON.stringify(setting)}'>
                            ${setting.car_model}
                        </option>
                    `);
                    });

                } else {
                    Swal.fire({
                        icon: 'warning',
                        text: data.error,
                        background: '#00375C',
                        color: '#fff',
                    });
                }
            }
        });
    });

    let html5QrCode;
    let selectedQRSetting = null;

    // Store selected QR setting
    $(document).on('change', '#qr_settings', function() {
        selectedQRSetting = JSON.parse($(this).val());
    });

    // Open scanner
    $('#openScanner').on('click', function() {
        if (!selectedQRSetting) {
            Swal.fire({
                icon: 'warning',
                text: 'Please select QR Setting first',
                showConfirmButton: true,
                background: '#00375C',
                color: '#fff'
            });
            return;
        }

        $('#qr-reader').show();
        $('#openScanner').hide();
        $('#closeScanner').show();

        html5QrCode = new Html5Qrcode("qr-reader");

        html5QrCode.start({
                facingMode: "environment"
            }, {
                fps: 10,
                qrbox: 250
            },
            function(decodedText) {
                html5QrCode.stop().then(() => {
                    $('#qr-reader').hide();
                    $('#openScanner').show();
                    $('#closeScanner').hide();
                });

                processScannedQR(decodedText);
            },
            function(errorMessage) {}
        ).catch(err => {
            console.error(err);
            Swal.fire("Camera Error", "Cannot access camera", "error");
        });
    });

    $('#closeScanner').on('click', function() {

        if (html5QrCode) {
            html5QrCode.stop().then(() => {
                $('#qr-reader').hide();
                $('#openScanner').show();
                $('#closeScanner').hide();
            }).catch(err => {
                console.error("Stop failed:", err);
            });
        }
    });

    function processScannedQR(qrCode) {
        if (!selectedQRSetting) return;

        const {
            total_length,
            product_name_start,
            product_name_length,
            lot_no_start,
            lot_no_length,
            serial_no_start,
            serial_no_length
        } = selectedQRSetting;

        if (qrCode.length !== parseInt(total_length)) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid QR Code',
                text: `Expected length: ${total_length}, got ${qrCode.length}`,
                background: '#00375C',
                color: '#fff',
                showConfirmButton: true
            });
            return;
        }

        // Display full scanned QR
        $('#a_scan_qr').val(qrCode);

        // Store full value in hidden input
        $('#nameplate_value').val(qrCode);

        // Step 1: Extract product including padding
        let productWithPadding = qrCode.substring(product_name_start, lot_no_start).trim();

        // Step 2: Extract lot from start of lot_no_start to start of serial
        let lotWithPadding = qrCode.substring(lot_no_start, serial_no_start).trim();

        // Step 3: Extract serial
        let serial = qrCode.substring(serial_no_start).trim();

        $('#a_product_name').val(productWithPadding);
        $('#a_lot_no').val(lotWithPadding);
        $('#a_serial_no').val(serial);

        Swal.fire({
            icon: 'success',
            text: 'Nameplate Scanned',
            showConfirmButton: false,
            timer: 1200,
            background: '#00375C',
            color: '#fff',
        });

        // Highlight product, lot, serial inputs for 5 seconds
        const highlightInputs = ['#a_product_name', '#a_lot_no', '#a_serial_no'];
        highlightInputs.forEach(selector => {
            $(selector).css({
                'border-bottom': '1px solid #22c55e', // green border
                'background-color': '#d4fcd4' // light green
            });
        });

        setTimeout(() => {
            highlightInputs.forEach(selector => {
                $(selector).css({
                    'border-bottom': '',
                    'background-color': ''
                });
            });
        }, 5000);
    }

    const fetch_line_no = () => {
        $.ajax({
            url: 'process/index_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'fetch_line_no',
            },
            success: function(response) {
                $('#a_line_no').html(response);
                $('#search_line_no').html(response);
            },
        });
    };

    const fetch_defect_details_code = () => {
        $.ajax({
            url: 'process/index_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'fetch_defect_details_code',
            },
            success: function(response) {
                $('#a_defect_details_code').html(response);
            },
        });
    };

    // Function to update the UI from sessionStorage
    function updateAuthNameDisplay() {
        const authName = sessionStorage.getItem('auth_name');
        document.getElementById('authNameDisplay').textContent = authName || 'Guest';
    }

    document.addEventListener('DOMContentLoaded', updateAuthNameDisplay);

    // Update when sessionStorage changes in other tabs/windows (optional but useful)
    window.addEventListener('storage', function(e) {
        if (e.key === 'auth_name') {
            updateAuthNameDisplay();
        }
    });

    // OPTIONAL: If user changes are triggered dynamically, observe sessionStorage changes
    const originalSetItem = sessionStorage.setItem;
    sessionStorage.setItem = function(key, value) {
        originalSetItem.apply(this, arguments);
        if (key === 'auth_name') {
            updateAuthNameDisplay();
        }
    };

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
        var search_car_maker = sessionStorage.getItem('search_car_maker');

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
                search_defect_details: search_defect_details,
                search_car_maker: search_car_maker
            },
            success: function(response) {
                sessionStorage.setItem('count_rows', response);
                var count = `<span style="font-size: 12px;">Total Record: ${response}</span>`;
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
        var search_car_maker = sessionStorage.getItem('search_car_maker');

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
                search_defect_details: search_defect_details,
                search_car_maker: search_car_maker
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
        var search_car_maker = document.getElementById('search_car_maker').value;

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
        var search_car_maker_1 = sessionStorage.getItem('search_car_maker');

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
                case search_car_maker !== search_car_maker_1:
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
                    search_car_maker = search_car_maker_1;

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
            sessionStorage.setItem('search_car_maker', search_car_maker);
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
                search_car_maker: search_car_maker,
                current_page: current_page
            },
            beforeSend: () => {
                Swal.fire({
                    title: 'Loading...',
                    text: 'Please wait while we fetch the defect list.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    },
                    background: '#00375C',
                    color: '#fff',
                });
            },
            success: function(response) {
                Swal.close();
                $('#loading').remove();

                if (current_page == 1) {
                    $('#defect_table tbody').html(response);
                } else {
                    $('#defect_table tbody').append(response);
                }

                sessionStorage.setItem('defect_table_pagination', current_page);
                count_defect();
            },
            error: function() {
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong while loading data!',
                    confirmButtonColor: '#0F78DC'
                });
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

    // // When user presses Enter in category code input
    // $('#a_defect_category_code').on('keydown', function(e) {
    //     if (e.key === 'Enter') {
    //         e.preventDefault(); // prevent form submission if inside a form
    //         const code = $(this).val().trim().toUpperCase();

    //         if (code !== '') {
    //             $.ajax({
    //                 url: 'process/index_p.php',
    //                 type: 'POST',
    //                 data: {
    //                     method: 'fetch_defect_category_by_code',
    //                     code: code
    //                 },
    //                 success: function(response) {
    //                     if (response === '' || response === null) {
    //                         Swal.fire({
    //                             icon: 'info',
    //                             title: 'Defect Category Code Not Found',
    //                             text: `No defect category found for code: ${code}`,
    //                             background: '#00375C',
    //                             color: '#f9f9f9',
    //                             ,
    //                         });
    //                         $('#a_defect_category').val('').prop('disabled', true);
    //                     } else {
    //                         $('#a_defect_category').val(response).prop('disabled', true);
    //                     }
    //                 },
    //                 error: function(xhr, status, error) {
    //                     Swal.fire({
    //                         icon: 'error',
    //                         title: 'AJAX Error',
    //                         text: error
    //                     });
    //                 }
    //             });
    //         } else {
    //             $('#a_defect_category').val('').prop('disabled', true);
    //         }
    //     }
    // });

    // // When user presses Enter in defect details code input
    // $('#a_defect_details_code').on('keydown', function(e) {
    //     if (e.key === 'Enter') {
    //         e.preventDefault();
    //         const detailsCode = $(this).val().trim().toUpperCase();

    //         if (detailsCode !== '') {
    //             $.ajax({
    //                 url: 'process/index_p.php',
    //                 type: 'POST',
    //                 dataType: 'json',
    //                 data: {
    //                     method: 'fetch_defect_details_by_code',
    //                     details_code: detailsCode
    //                 },
    //                 success: function(data) {
    //                     if (data.error) {
    //                         Swal.fire({
    //                             icon: 'info',
    //                             title: 'Defect Details Code Not Found',
    //                             text: `No defect details found for code: ${detailsCode}`,
    //                             background: '#00375C',
    //                             color: '#f9f9f9',
    //                             
    //                         });

    //                         $('#a_defect_details').val('').prop('disabled', true);
    //                         $('#a_treatment_content_defect').val('').prop('disabled', true);
    //                     } else {
    //                         $('#a_defect_details').val(data.details).prop('disabled', true);
    //                         $('#a_treatment_content_defect').val(data.treatment).prop('disabled', true);
    //                     }
    //                 },
    //                 error: function(xhr, status, error) {
    //                     Swal.fire({
    //                         icon: 'error',
    //                         title: 'AJAX Error',
    //                         text: error
    //                     });
    //                 }
    //             });
    //         } else {
    //             $('#a_defect_details').val('').prop('disabled', true);
    //             $('#a_treatment_content_defect').val('').prop('disabled', true);
    //         }
    //     }
    // });

    // Function for handling the defect details code entry
    function handleDefectDetailsCode() {
        const detailsCode = $('#a_defect_details_code').val().trim().toUpperCase();

        if (detailsCode !== '') {
            // Step 1: Extract category code (letters only before first digit)
            const categoryCode = detailsCode.match(/^[A-Za-z]+/);
            if (categoryCode) {
                const defectCategoryCode = categoryCode[0];

                // Step 2: Set the category code field
                $('#a_defect_category_code').val(defectCategoryCode);

                // Step 3: Fetch defect category value
                $.ajax({
                    url: 'process/index_p.php',
                    type: 'POST',
                    data: {
                        method: 'fetch_defect_category_by_code',
                        code: defectCategoryCode
                    },
                    success: function(categoryValue) {
                        $('#a_defect_category').val(categoryValue).prop('disabled', true);
                    }
                });
            }

            // Step 4: Fetch defect details and treatment
            $.ajax({
                url: 'process/index_p.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    method: 'fetch_defect_details_by_code',
                    details_code: detailsCode
                },
                success: function(data) {
                    if (data.error) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Defect Details Code Not Found',
                            text: `No defect details found for code: ${detailsCode}`,
                            background: '#00375C',
                            color: '#f9f9f9',
                        });
                        $('#a_defect_details').val('').prop('disabled', true);
                        $('#a_treatment_content_defect').val('').prop('disabled', true);
                    } else {
                        $('#a_defect_details').val(data.details).prop('disabled', true);
                        $('#a_treatment_content_defect').val(data.treatment).prop('disabled', true);
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'AJAX Error',
                        text: error
                    });
                }
            });
        } else {
            $('#a_defect_details').val('').prop('disabled', true);
            $('#a_treatment_content_defect').val('').prop('disabled', true);
        }
    }

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
        // Extract the date from the input field
        var date_detected = document.getElementById("a_date_detected").value;

        // Regular expression to validate the date format (yyyy-mm-dd)
        var datePattern = /^\d{4}-\d{2}-\d{2}$/;

        // Check if the date matches the pattern
        if (!datePattern.test(date_detected)) {
            Swal.fire({
                icon: 'info',
                text: 'Please enter a valid date in the format YYYY-MM-DD.',
                background: '#00375C',
                color: '#f9f9f9',
            });
            return;
        }

        // Split the date into year, month, and day components
        var dateParts = date_detected.split('-');
        var year = parseInt(dateParts[0], 10);
        var currentYear = new Date().getFullYear();

        // Check if the year is a valid 4-digit number
        if (year < 2026 || year > currentYear) {
            Swal.fire({
                icon: 'info',
                text: 'Year should be 2026.',
                background: '#00375C',
                color: '#f9f9f9',
            });
            return;
        }

        const fieldIds = [
            "a_date_detected", "a_car_maker", "a_car_model", "a_line_no", "a_process", "a_category",
            "a_group", "a_shift", "a_product_name", "a_lot_no", "a_serial_no",
            "a_defect_category_code", "a_defect_category", "a_defect_details", "a_defect_details_code", "a_sequence_no",
            "a_connector_no", "a_treatment_content_defect", "a_total_time", "a_repaired_by", "a_verified_by",
            "a_ip_address", "a_harness_type", "a_occurrence_shift", "a_occurrence_board_no", "a_occurrence_station_no"
        ];

        let hasEmpty = false;

        fieldIds.forEach(id => {
            const input = document.getElementById(id);
            if (input && input.value.trim() === '') {
                input.style.border = '1px solid #bc4749';
                input.style.borderRadius = '5px';
                hasEmpty = true;
            } else if (input) {
                input.style.border = '';
            }
        });

        const radios = document.querySelectorAll('input[name="category_type"]');
        let radioSelected = false;

        radios.forEach(r => {
            if (r.checked) radioSelected = true;
        });

        if (!radioSelected) {
            radios.forEach(r => {
                r.parentElement.style.border = '1px solid #bc4749';
                r.parentElement.style.padding = '1px 3px';
                r.parentElement.style.borderRadius = '5px';
            });
        } else {
            radios.forEach(r => {
                r.parentElement.style.border = '';
            });
        }

        if (hasEmpty || !radioSelected) {
            Swal.fire({
                icon: 'info',
                title: 'Missing Required Fields',
                text: 'Please fill in all required fields before submitting.',
                background: '#00375C',
                color: '#f9f9f9',
            });
            return;
        }

        var date_detected = document.getElementById("a_date_detected").value;
        var car_maker = document.getElementById("a_car_maker").value;
        var car_model = document.getElementById("a_car_model").value;
        var line_no = document.getElementById("a_line_no").value;

        var category = document.getElementById("a_category").value;

        // if ((line_no === "2130" || line_no === "2132") && category === "") {
        //     Swal.fire({
        //         icon: 'info',
        //         title: 'Missing Required Field',
        //         text: 'Category is required for Line No. 2130 and 2132.',
        //         background: '#00375C',
        //         color: '#f9f9f9',
        //     });
        //     return;
        // }

        // if (line_no !== "2130" && line_no !== "2132") {
        //     category = "N/A";
        //     document.getElementById("a_category").value = "N/A";
        // }

        var process = document.getElementById("a_process").value;
        var group = document.getElementById("a_group").value;
        var shift = document.getElementById("a_shift").value;

        var product_name = document.getElementById("a_product_name");
        var lot_no = document.getElementById("a_lot_no");
        var serial_no = document.getElementById("a_serial_no");

        var defect_category_code = document.getElementById("a_defect_category_code").value;
        var defect_category = document.getElementById("a_defect_category").value;
        var defect_details_code = document.getElementById("a_defect_details_code").value;
        var defect_details = document.getElementById("a_defect_details").value;
        var sequence_no = document.getElementById("a_sequence_no").value;
        var connector_no = document.getElementById("a_connector_no").value;
        var treatment_content_defect = document.getElementById("a_treatment_content_defect").value;
        var total_time = document.getElementById("a_total_time").value;
        var repaired_by = document.getElementById("a_repaired_by").value;
        var verified_by = document.getElementById("a_verified_by").value;
        var harness_type = document.getElementById("a_harness_type").value;

        var occurrence_shift = document.getElementById("a_occurrence_shift").value;
        var occurrence_board_no = document.getElementById("a_occurrence_board_no").value;
        var occurrence_station_no = document.getElementById("a_occurrence_station_no").value;

        var defect_id = document.getElementById('defect_id_no').value;
        var ip_address = document.getElementById("a_ip_address").value;
        var nameplate_value = document.getElementById("nameplate_value").value;

        var auth_id_no = sessionStorage.getItem('auth_id_no');
        var auth_name = sessionStorage.getItem('auth_name');

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
                category: category,
                process: process,
                group: group,
                shift: shift,
                product_name: product_name.value,
                lot_no: lot_no.value,
                serial_no: serial_no.value,
                defect_category_code: defect_category_code,
                defect_category: defect_category,
                defect_details_code: defect_details_code,
                defect_details: defect_details,
                sequence_no: sequence_no,
                connector_no: connector_no,
                treatment_content_defect: treatment_content_defect,
                total_time: total_time,
                repaired_by: repaired_by,
                verified_by: verified_by,
                harness_type: harness_type,
                occurrence_shift: occurrence_shift,
                occurrence_board_no: occurrence_board_no,
                occurrence_station_no: occurrence_station_no,
                defect_id: defect_id,
                ip_address: ip_address,
                nameplate_value: nameplate_value,
                auth_id_no: auth_id_no,
                auth_name: auth_name
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
                    $('#a_defect_category_code').val('');
                    $('#a_defect_category').val('');
                    $('#a_defect_details_code').val('');
                    $('#a_defect_details').val('');
                    $('#a_sequence_no').val('');
                    $('#a_connector_no').val('');
                    $('#a_treatment_content_defect').val('');
                    $('#a_total_time').val('');
                    $('#a_occurrence_shift').val('');
                    $('#a_occurrence_board_no').val('');
                    $('#a_occurrence_station_no').val('');
                    $('#defect_id_no').val('');

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
        document.getElementById("a_date_detected").value = '';
        document.getElementById("a_car_maker").value = '';
        document.getElementById("a_car_model").value = '';
        document.getElementById("a_line_no").value = '';
        document.getElementById("a_harness_type").value = '';
        document.getElementById("a_process").value = '';
        document.getElementById("a_group").value = '';
        document.getElementById("a_product_name").value = 'N/A';
        document.getElementById("a_lot_no").value = '59';
        document.getElementById("a_serial_no").value = '';
        document.getElementById("a_defect_category_code").value = '';
        document.getElementById("a_defect_category").value = '';
        document.getElementById("a_sequence_no").value = '';
        document.getElementById("a_connector_no").value = '';
        document.getElementById("a_treatment_content_defect").value = '';
        document.getElementById("a_total_time").value = '';
        // document.getElementById("a_occurrence_shift").value = '';
        document.getElementById("a_occurrence_board_no").value = '';
        document.getElementById("a_occurrence_station_no").value = '';
        document.getElementById("a_repaired_by").value = 'N/A';
        document.getElementById("a_verified_by").value = 'N/A';
        document.getElementById("nameplate_value").value = '';
        document.getElementById("a_scan_qr").value = '';

        document.getElementById("a_defect_details_code").value = '';
        document.getElementById("a_defect_details").value = '';

        document.getElementById("qr_settings").value = '';

        $('#a_process').prop('disabled', true).css('background', '#DDD');
        $('#a_process').empty().append('<option value="" disabled selected>Select Process</option>');

        // $('#category_section').addClass('d-none');
        $('input[name="category_type"]').prop('checked', false);

        $('#a_category').val('');
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
        var search_car_maker = document.getElementById('search_car_maker').value.trim();

        // if (search_date_from === '') {
        //     search_date_from = new Date().toISOString().slice(0, 10);
        // }
        // if (search_date_to === '') {
        //     search_date_to = new Date().toISOString().slice(0, 10);
        // }

        window.open(
            'process/exp_defect_record.php?scan_product_name=' + encodeURIComponent(scan_product_name) +
            '&scan_lot_no=' + encodeURIComponent(scan_lot_no) +
            '&scan_serial_no=' + encodeURIComponent(scan_serial_no) +
            '&search_process=' + encodeURIComponent(search_process) +
            '&search_line_no=' + encodeURIComponent(search_line_no) +
            '&search_date_from=' + encodeURIComponent(search_date_from) +
            '&search_date_to=' + encodeURIComponent(search_date_to) +
            '&search_defect_category=' + encodeURIComponent(search_defect_category) +
            '&search_defect_details=' + encodeURIComponent(search_defect_details) +
            '&search_car_maker=' + encodeURIComponent(search_car_maker),
            '_blank'
        );
    };

    // const get_inspection_details = () => {
    //     const line_no = $('#a_line_no').val();

    //     $('#a_process').prop('disabled', true).css('background', '#DDD');

    //     $.ajax({
    //         url: 'process/inspection_p.php',
    //         type: 'GET',
    //         data: {
    //             method: 'get_inspection_details',
    //             line_no: line_no
    //         },
    //         success: function(response) {
    //             const data = JSON.parse(response);
    //             if (data.success) {
    //                 if (!data.car_maker || !data.car_model) {
    //                     Swal.fire({
    //                         icon: 'warning',
    //                         title: 'No Car Maker and Model',
    //                         text: 'Register car maker and model of the line.',
    //                         showConfirmButton: true
    //                     });

    //                     $('#a_process').prop('disabled', true).css('background', '#DDD');
    //                 } else {
    //                     $('#a_car_maker').val(data.car_maker);
    //                     $('#a_car_model').val(data.car_model);

    //                     $('#a_process').empty().append('<option value="" disabled selected>Select Process</option>');

    //                     data.processes.forEach(process => {
    //                         $('#a_process').append(`<option value="${process}">${process}</option>`);
    //                     });

    //                     $('#a_process').prop('disabled', false).css('background', '#FFF');
    //                     $('#a_car_maker').prop('disabled', true).css('background', '#F1F1F1');

    //                     // Initialize QR handler
    //                     setupQRHandler(data.qr_settings);
    //                 }
    //             } else {
    //                 Swal.fire({
    //                     icon: 'warning',
    //                     title: data.error,
    //                     showConfirmButton: true
    //                 });

    //                 $('#a_process').prop('disabled', true).css('background', '#DDD');
    //             }
    //         },
    //         error: function(xhr, status, error) {
    //             console.error('AJAX Error: ', status, error);
    //             Swal.fire({
    //                 icon: 'error',
    //                 title: 'AJAX Error',
    //                 showConfirmButton: true
    //             });
    //         }
    //     });
    // };

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
                    $('#a_car_maker').val(data.car_maker);

                    // Handle car_model
                    if (Array.isArray(data.car_model)) {
                        let selectHtml = `
                                            <select id="a_car_model" class="form-control form-control-sm form-control-border text-xs" required>
                                                <option value="" disabled selected>Select Car Model</option>
                                        `;
                        data.car_model.forEach(model => {
                            selectHtml += `<option value="${model}">${model}</option>`;
                        });
                        selectHtml += `</select>`;

                        // Replace existing input with select
                        $('#a_car_model').replaceWith(selectHtml);

                        // Initialize QR handler
                        // setupQRHandler(data.qr_settings);
                    } else {
                        // If single car model, ensure it's a text input
                        const inputHtml = `
                                                <input type="text" id="a_car_model" class="form-control form-control-sm form-control-border text-xs" autocomplete="off"
                                                    value="${data.car_model}" required>
                                            `;
                        $('#a_car_model').replaceWith(inputHtml);
                    }

                    // Handle processes
                    $('#a_process').empty().append('<option value="" disabled selected>Select Process</option>');
                    data.processes.forEach(process => {
                        $('#a_process').append(`<option value="${process}">${process}</option>`);
                    });

                    $('#a_process').prop('disabled', false).css('background', '#FFF');

                    $('#a_harness_type').val(data.harness_type);
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: data.error,
                        showConfirmButton: true,
                        background: '#00375C',
                        color: '#f9f9f9',

                    });

                    $('#a_process').prop('disabled', true).css('background', '#DDD');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error: ', status, error);
                Swal.fire({
                    icon: 'error',
                    title: 'AJAX Error',
                    showConfirmButton: true,
                    background: '#00375C',
                    color: '#f9f9f9',

                });
            }
        });
    };

    // const setupQRHandler = (qr_settings) => {
    //     const {
    //         total_length,
    //         product_name_start,
    //         product_name_length,
    //         lot_no_start,
    //         lot_no_length,
    //         serial_no_start,
    //         serial_no_length
    //     } = qr_settings;

    //     $('#a_scan_qr').off('keyup').on('keyup', function(e) {
    //         if (e.which === 13) { // Enter key
    //             e.preventDefault();
    //             let qrCode = this.value;

    //             // Convert settings to integers
    //             const totalLength = parseInt(total_length, 10);
    //             const productNameStart = parseInt(product_name_start, 10);
    //             const productNameLength = parseInt(product_name_length, 10);
    //             const lotNoStart = parseInt(lot_no_start, 10);
    //             const lotNoLength = parseInt(lot_no_length, 10);
    //             const serialNoStart = parseInt(serial_no_start, 10);
    //             const serialNoLength = parseInt(serial_no_length, 10);

    //             console.log('Converted Settings:', {
    //                 totalLength,
    //                 productNameStart,
    //                 productNameLength,
    //                 lotNoStart,
    //                 lotNoLength,
    //                 serialNoStart,
    //                 serialNoLength
    //             });

    //             if (qrCode.length === totalLength) {
    //                 document.getElementById('nameplate_value').value = qrCode;

    //                 // Extract values using start and length parameters
    //                 const productName = qrCode.substring(productNameStart, productNameStart + productNameLength).trim();
    //                 const lotNo = qrCode.substring(lotNoStart, lotNoStart + lotNoLength).trim();
    //                 const serialNo = qrCode.substring(serialNoStart, serialNoStart + serialNoLength).trim();

    //                 $('#a_product_name').val(productName);
    //                 $('#a_lot_no').val(lotNo);
    //                 $('#a_serial_no').val(serialNo);

    //                 this.value = '';
    //             } else {
    //                 Swal.fire({
    //                     icon: 'error',
    //                     title: 'Invalid QR Code',
    //                     text: `Expected length: ${totalLength}, but received: ${qrCode.length}`,
    //                 });
    //             }
    //         }
    //     });
    // };

    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('auth_id_no');

        input.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                const inputValue = input.value.trim();
                if (inputValue !== '') {
                    sessionStorage.setItem('auth_id_no', inputValue);
                    checkAuthId(inputValue);
                }
            }
        });
    });

    let authHtml5QrCode = null;

    $('#openAuthScanner').on('click', function() {
        const qrContainer = $('#auth-qr-reader');
        qrContainer.show();

        // Initialize Html5Qrcode if not already
        if (!authHtml5QrCode) {
            authHtml5QrCode = new Html5Qrcode("auth-qr-reader");
        }

        authHtml5QrCode.start({
                facingMode: "environment"
            }, // back camera
            {
                fps: 10,
                qrbox: 250
            },
            function(decodedText) {
                // Stop scanner after successful scan
                authHtml5QrCode.stop().then(() => {
                    qrContainer.hide();
                });

                // Fill input and trigger your existing check
                $('#auth_id_no').val(decodedText);
                sessionStorage.setItem('auth_id_no', decodedText);
                checkAuthId(decodedText);
            },
            function(errorMessage) {
                // optional: ignore scanning errors
            }
        ).catch(err => {
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Camera Error',
                text: 'Cannot access camera. Make sure your device allows camera access.',
                background: '#00375C',
                color: '#fff'
            });
        });
    });

    function checkAuthId(inputValue) {
        console.log(`Checking scanned ID: ${inputValue}`);
        $.ajax({
            url: 'process/index_p.php',
            type: 'POST',
            data: {
                method: 'check_auth_id',
                id_no: inputValue
            },
            success: function(response) {
                handleServerResponse(response);
            },
            error: function(xhr, status, error) {
                console.error(`AJAX Error [${status}]: ${error}`);
                showAlert('error', 'Error', 'There was a problem connecting to the server.');
            }
        });
    }

    function handleServerResponse(response) {
        try {
            const res = JSON.parse(response);

            if (res.success) {
                // ✅ Store emp_name in hidden input and sessionStorage
                if (res.emp_name) {
                    document.getElementById("auth_name").value = res.emp_name;
                    sessionStorage.setItem('auth_name', res.emp_name);
                }

                // Close auth modal
                $('#add_record_auth').modal('hide');

                // Open defect modal
                $('#add_defect_record').modal('show');

                document.getElementById("auth_id_no").value = "";
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'ID Not Registered',
                    text: res.error || 'The entered ID number is not registered in the system.',
                    confirmButtonColor: '#d33',
                    background: '#00375C',
                    color: '#fff',
                    customClass: {
                        popup: 'custom-swal-popup'
                    }
                });

                document.getElementById("auth_id_no").value = "";
            }
        } catch (e) {
            console.error('Response parsing error:', e);
            Swal.fire({
                icon: 'error',
                title: 'Unexpected Error',
                text: 'Something went wrong while processing the response.',
                confirmButtonColor: '#d33'
            });
        }
    }
</script>