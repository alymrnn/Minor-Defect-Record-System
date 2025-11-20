<script type="text/javascript">
    $(document).ready(function() {
        load_minor_defect_list();

    });

    const load_minor_defect_list = (page = 1) => {
        $.ajax({
            url: '../process/minor_record_m_p.php',
            type: 'POST',
            dataType: 'json',
            cache: false,
            data: {
                method: 'load_minor_defect_list',
                page: page,
                limit: 300,
                m_search_date_from: $('#m_search_date_from').val(),
                m_search_date_to: $('#m_search_date_to').val(),
                m_search_line_no: $('#m_search_line_no').val(),
                m_search_lot_no: $('#m_search_lot_no').val(),
                m_search_process: $('#m_search_process').val()
            },
            beforeSend: function() {
                Swal.fire({
                    title: 'Loading...',
                    text: 'Fetching minor defect records, please wait.',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    background: '#1b263b',
                    color: '#fff',
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            success: function(response) {
                $('#list_of_minor_defect').html(response.table);
                $('#pagination_controls').html(response.pagination);
                Swal.close();
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong while loading data.',
                    background: '#1b263b',
                    color: '#fff',
                });
            }
        });
    };

    $(document).on('change', '#inventory_pagination', function() {
        let page = parseInt($(this).val());
        let maxPage = parseInt($('#pagination option').last().val());

        if (!page || page < 1) page = 1;
        if (page > maxPage) page = maxPage;

        load_minor_defect_list(page);
    });

    const edit_minor_defect = (defect_id) => {
        $.ajax({
            url: '../process/minor_record_m_p.php',
            type: 'POST',
            dataType: 'json',
            cache: false,
            data: {
                method: 'get_minor_defect_details',
                defect_id: defect_id
            },
            beforeSend: function() {
                Swal.fire({
                    title: 'Loading...',
                    text: 'Fetching defect details, please wait.',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    background: '#1b263b',
                    color: '#fff',
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            success: function(response) {
                if (response.status === 'success') {
                    const data = response.data;
                    $('#edit_defect_id').val(data.defect_id);
                    $('#edit_date_detected').val(data.date_detected);
                    $('#edit_car_maker').val(data.car_maker);
                    $('#edit_car_model').val(data.car_model);
                    $('#edit_line_no').val(data.line_no);
                    $('#edit_harness_type').val(data.harness_type);
                    $('#edit_category').val(data.line_category);
                    $('#edit_process').val(data.process);
                    $('#edit_group_d').val(data.group_d);
                    $('#edit_shift').val(data.shift);
                    $('#edit_product_name').val(data.product_no);
                    $('#edit_lot_no').val(data.lot_no);
                    $('#edit_serial_no').val(data.serial_no);
                    $('#edit_defect_category_code').val(data.defect_category_code);
                    $('#edit_defect_category').val(data.defect_category);
                    $('#edit_defect_details_code').val(data.defect_details_code);
                    $('#edit_defect_details').val(data.defect_details);
                    $('#edit_treatment_content').val(data.treatment_content_defect);
                    $('#edit_occurrence_shift').val(data.occurrence_shift);
                    $('#edit_occurrence_board_no').val(data.occurrence_board_no);
                    $('#edit_occurrence_station_no').val(data.occurrence_station_no);
                    $('#edit_sequence_no').val(data.sequence_no);
                    $('#edit_connector_no').val(data.connector_no);
                    $('#edit_total_time').val(data.total_time);
                    $('#edit_repaired_by').val(data.repaired_by);
                    $('#edit_verified_by').val(data.verified_by);

                    Swal.close();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong while fetching defect details.'
                });
            }
        });
    };

    const edit_defect_record = () => {
        const defect_id = $('#edit_defect_id').val();
        const date_detected = $('#edit_date_detected').val();
        const car_maker = $('#edit_car_maker').val();
        const car_model = $('#edit_car_model').val();
        const line_no = $('#edit_line_no').val();
        const harness_type = $('#edit_harness_type').val();
        const line_category = $('#edit_category').val();
        const process = $('#edit_process').val();
        const group_d = $('#edit_group_d').val();
        const shift = $('#edit_shift').val();
        const product_no = $('#edit_product_name').val();
        const lot_no = $('#edit_lot_no').val();
        const serial_no = $('#edit_serial_no').val();
        const defect_category_code = $('#edit_defect_category_code').val();
        const defect_category = $('#edit_defect_category').val();
        const defect_details_code = $('#edit_defect_details_code').val();
        const defect_details = $('#edit_defect_details').val();
        const treatment_content_defect = $('#edit_treatment_content').val();
        const occurrence_shift = $('edit_occurrence_shift').val();
        const occurrence_board_no = $('edit_occurrence_board_no').val();
        const occurrence_station_no = $('edit_occurrence_station_no').val();
        const sequence_no = $('#edit_sequence_no').val();
        const connector_no = $('#edit_connector_no').val();
        const total_time = $('#edit_total_time').val();
        const repaired_by = $('#edit_repaired_by').val();
        const verified_by = $('#edit_verified_by').val();

        $.ajax({
            url: '../process/minor_record_m_p.php',
            type: 'POST',
            dataType: 'json',
            cache: false,
            data: {
                method: 'update_minor_defect_record',
                defect_id: defect_id,
                date_detected: date_detected,
                car_maker: car_maker,
                car_model: car_model,
                line_no: line_no,
                harness_type: harness_type,
                line_category: line_category,
                process: process,
                group_d: group_d,
                shift: shift,
                product_no: product_no,
                lot_no: lot_no,
                serial_no: serial_no,
                defect_category_code: defect_category_code,
                defect_category: defect_category,
                defect_details_code: defect_details_code,
                defect_details: defect_details,
                treatment_content_defect: treatment_content_defect,
                occurrence_shift: occurrence_shift,
                occurrence_board_no: occurrence_board_no,
                occurrence_station_no: occurrence_station_no,
                sequence_no: sequence_no,
                connector_no: connector_no,
                total_time: total_time,
                repaired_by: repaired_by,
                verified_by: verified_by
            },
            beforeSend: function() {
                Swal.fire({
                    title: 'Updating...',
                    text: 'Updating defect record, please wait.',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    background: '#1b263b',
                    color: '#fff',
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated',
                        text: response.message,
                        background: '#1b263b',
                        color: '#fff',
                        showConfirmButton: false,
                        timer: 1000
                    }).then(() => {
                        $('#edit_defect_record').modal('hide');
                        load_minor_defect_list();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message,
                        background: '#1b263b',
                        color: '#fff',
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong while updating the record.',
                    background: '#1b263b',
                    color: '#fff',
                });
            }
        });
    };
</script>