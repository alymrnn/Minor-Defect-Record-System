<script type="text/javascript">
    $(document).ready(function() {
        load_car_settings();
        load_defect_details();
        load_accounts();
        load_line_process();
        load_auth_account();
        load_line_car_model();
    });

    // for qr settings
    const load_car_settings = () => {
        $.ajax({
            url: '../process/index_m_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'qr_setting_list'
            },
            success: function(response) {
                $('#list_of_qr_setting').html(response);
                $('#spinner').fadeOut();
            }
        });
    }

    const register_setting = () => {
        const fields = [
            'car_maker_qr',
            'car_model_qr',
            'car_value_qr',
            'total_length_qr',
            'pro_name_start_qr',
            'pro_name_length_qr',
            'lot_no_start_qr',
            'lot_no_length_qr',
            'serial_no_start_qr',
            'serial_no_length_qr'
        ];

        let hasEmpty = false;

        fields.forEach(id => {
            const field = document.getElementById(id);
            if (field.value.trim() === '') {
                field.classList.add('is-invalid');
                hasEmpty = true;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        if (hasEmpty) {
            Swal.fire({
                icon: 'info',
                title: 'Missing Information',
                text: 'Please fill in all required fields before submission.',
                showConfirmButton: false,
                timer: 2000
            });
            return;
        }

        const data = {};
        fields.forEach(id => {
            data[id.replace('_qr', '')] = document.getElementById(id).value.trim();
        });

        $.ajax({
            url: '../process/index_m_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'register_setting',
                ...data
            },
            success: function(response) {
                if (response === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'QR Settings Added',
                        text: 'Success',
                        showConfirmButton: false,
                        timer: 1500
                    });

                    fields.forEach(id => {
                        $('#' + id).val('').removeClass('is-invalid');
                    });

                    load_car_settings();
                    $('#add_qr_setting').modal('hide');
                } else if (response === 'Already Exist') {
                    Swal.fire({
                        icon: 'info',
                        title: 'Duplicate Data',
                        showConfirmButton: false,
                        timer: 1500
                    });
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

    const get_car_setting_details = (param) => {
        var string = param.split('~!~');
        var id = string[0];
        var car_maker = string[1];
        var car_model = string[2];
        var car_value = string[3];
        var total_length = string[4];
        var pro_name_start = string[5];
        var pro_name_length = string[6];
        var lot_no_start = string[7];
        var lot_no_length = string[8];
        var serial_no_start = string[9];
        var serial_no_length = string[10];

        document.getElementById('id_qr_update').value = id;
        document.getElementById('car_maker_qr_update').value = car_maker;
        document.getElementById('car_model_qr_update').value = car_model;
        document.getElementById('car_value_qr_update').value = car_value;
        document.getElementById('total_length_qr_update').value = total_length;
        document.getElementById('pro_name_start_qr_update').value = pro_name_start;
        document.getElementById('pro_name_length_qr_update').value = pro_name_length;
        document.getElementById('lot_no_start_qr_update').value = lot_no_start;
        document.getElementById('lot_no_length_qr_update').value = lot_no_length;
        document.getElementById('serial_no_start_qr_update').value = serial_no_start;
        document.getElementById('serial_no_length_qr_update').value = serial_no_length;
    }

    const update_setting = () => {
        var id = document.getElementById('id_qr_update').value;
        var car_maker = document.getElementById('car_maker_qr_update').value;
        var car_model = document.getElementById('car_model_qr_update').value;
        var car_value = document.getElementById('car_value_qr_update').value;
        var total_length = document.getElementById('total_length_qr_update').value;
        var pro_name_start = document.getElementById('pro_name_start_qr_update').value;
        var pro_name_length = document.getElementById('pro_name_length_qr_update').value;
        var lot_no_start = document.getElementById('lot_no_start_qr_update').value;
        var lot_no_length = document.getElementById('lot_no_length_qr_update').value;
        var serial_no_start = document.getElementById('serial_no_start_qr_update').value;
        var serial_no_length = document.getElementById('serial_no_length_qr_update').value;

        $.ajax({
            url: '../process/index_m_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'update_setting',
                id: id,
                car_maker: car_maker,
                car_model: car_model,
                car_value: car_value,
                total_length: total_length,
                pro_name_start: pro_name_start,
                pro_name_length: pro_name_length,
                lot_no_start: lot_no_start,
                lot_no_length: lot_no_length,
                serial_no_start: serial_no_start,
                serial_no_length: serial_no_length
            },
            success: function(response) {
                if (response == 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'QR Settings Updated',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $('#car_maker_qr_update').val('');
                    $('#car_model_qr_update').val('');
                    $('#car_value_qr_update').val('');
                    $('#total_length_qr_update').val('');
                    $('#pro_name_start_qr_update').val('');
                    $('#pro_name_length_qr_update').val('');
                    $('#lot_no_start_qr_update').val('');
                    $('#lot_no_length_qr_update').val('');
                    $('#serial_no_start_qr_update').val('');
                    $('#serial_no_length_qr_update').val('');
                    load_car_settings();
                    $('#update_qr_setting').modal('hide');
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
    }

    const delete_setting = () => {
        var id = document.getElementById('id_qr_update').value;

        $.ajax({
            url: '../process/index_m_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'delete_setting',
                id: id
            },
            success: function(response) {
                if (response == 'success') {
                    Swal.fire({
                        icon: 'info',
                        title: 'QR Settings Deleted',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    load_car_settings();
                    $('#update_qr_setting').modal('hide');
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
    }

    // for defect details
    const load_defect_details = () => {
        $.ajax({
            url: '../process/index_m_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'defect_details_list'
            },
            success: function(response) {
                $('#list_of_defect_details').html(response);
                $('#spinner').fadeOut();
            }
        });
    }

    const register_defect_details = () => {
        const fields = [
            'defect_code_m',
            'defect_category_m',
            'defect_sub_code_m',
            'defect_details_m',
            'defect_treatment_m'
        ];

        let hasEmpty = false;

        fields.forEach(id => {
            const field = document.getElementById(id);
            if (field.value.trim() === '') {
                field.classList.add('is-invalid');
                hasEmpty = true;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        if (hasEmpty) {
            Swal.fire({
                icon: 'info',
                title: 'Missing Information',
                text: 'Please fill in all required fields before submission.',
                showConfirmButton: false,
                timer: 2000
            });
            return;
        }

        $.ajax({
            url: '../process/index_m_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'register_defect_details',
                defect_code: document.getElementById('defect_code_m').value.trim(),
                defect_category: document.getElementById('defect_category_m').value.trim(),
                defect_sub_code: document.getElementById('defect_sub_code_m').value.trim(),
                defect_details: document.getElementById('defect_details_m').value.trim(),
                defect_treatment: document.getElementById('defect_treatment_m').value.trim()
            },
            success: function(response) {
                if (response === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Defect Details Added',
                        text: 'Success',
                        showConfirmButton: false,
                        timer: 1500
                    });

                    fields.forEach(id => $('#' + id).val('').removeClass('is-invalid'));

                    load_defect_details();
                    $('#add_defect_details').modal('hide');
                } else if (response === 'Already Exist') {
                    Swal.fire({
                        icon: 'info',
                        title: 'Duplicate Data',
                        showConfirmButton: false,
                        timer: 1500
                    });
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

    const delete_added_defect = (event) => {
        var id = event.target.dataset.id;

        $.ajax({
            url: '../process/index_m_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'delete_added_defect',
                id: id
            },
            success: function(response) {
                if (response == 'success') {
                    Swal.fire({
                        icon: 'info',
                        text: 'Deleted, please wait.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    load_defect_details();
                }
            }
        });
    }

    // for accounts
    const load_accounts = () => {
        $.ajax({
            url: '../process/index_m_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'accounts_list'
            },
            success: function(response) {
                $('#list_of_accounts').html(response);
                $('#spinner').fadeOut();
            }
        });
    }

    const register_account = () => {
        var usernameField = document.getElementById('username_m');
        var roleField = document.getElementById('role_m');

        var username = usernameField.value.trim();
        var role = roleField.value.trim();

        usernameField.classList.remove('is-invalid');
        roleField.classList.remove('is-invalid');

        if (!username || !role) {
            if (!username) usernameField.classList.add('is-invalid');
            if (!role) roleField.classList.add('is-invalid');

            Swal.fire({
                icon: 'warning',
                title: 'Missing Information',
                text: 'Please fill out all required fields.',
                showConfirmButton: false,
                timer: 1500
            });
            return;
        }

        $.ajax({
            url: '../process/index_m_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'register_account',
                username: username,
                role: role
            },
            success: function(response) {
                if (response == 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Account Added',
                        text: 'Success',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $('#username_m').val('').removeClass('is-invalid');
                    $('#role_m').val('').removeClass('is-invalid');
                    load_accounts();
                    $('#add_account').modal('hide');
                } else if (response == 'Already Exist') {
                    Swal.fire({
                        icon: 'info',
                        title: 'Duplicate Data',
                        showConfirmButton: false,
                        timer: 1500
                    });
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
    }

    const delete_added_account = (event) => {
        var id = event.target.dataset.id;

        $.ajax({
            url: '../process/index_m_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'delete_added_account',
                id: id
            },
            success: function(response) {
                if (response == 'success') {
                    Swal.fire({
                        icon: 'info',
                        text: 'Deleted, please wait.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    load_accounts();
                }
            }
        });
    }

    // for line process
    const load_line_process = () => {
        $.ajax({
            url: '../process/index_m_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'line_process_list'
            },
            success: function(response) {
                $('#list_of_line_process').html(response);
                $('#spinner').fadeOut();
            }
        });
    }
    const register_line_process = () => {
        var lineField = document.getElementById('line_m');
        var processField = document.getElementById('process_m');

        var line_no = lineField.value.trim();
        var process = processField.value.trim();

        lineField.classList.remove('is-invalid');
        processField.classList.remove('is-invalid');

        if (!line_no || !process) {
            if (!line_no) lineField.classList.add('is-invalid');
            if (!process) processField.classList.add('is-invalid');

            Swal.fire({
                icon: 'warning',
                title: 'Missing Information',
                text: 'Please fill out all required fields.',
                showConfirmButton: false,
                timer: 1500
            });
            return;
        }

        $.ajax({
            url: '../process/index_m_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'register_line_process',
                line_no: line_no,
                process: process
            },
            success: function(response) {
                if (response == 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Line No. / Process Added',
                        text: 'Success',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $('#line_m').val('').removeClass('is-invalid');
                    $('#process_m').val('').removeClass('is-invalid');
                    load_line_process();
                    $('#add_line_process').modal('hide');
                } else if (response == 'Already Exist') {
                    Swal.fire({
                        icon: 'info',
                        title: 'Duplicate Data',
                        showConfirmButton: false,
                        timer: 1500
                    });
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
    }

    const delete_added_line_process = (event) => {
        var id = event.target.dataset.id;

        $.ajax({
            url: '../process/index_m_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'delete_added_line_process',
                id: id
            },
            success: function(response) {
                if (response == 'success') {
                    Swal.fire({
                        icon: 'info',
                        text: 'Deleted, please wait.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    load_line_process();
                }
            }
        });
    }

    // for auth account
    const load_auth_account = () => {
        $.ajax({
            url: '../process/index_m_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'auth_account_list'
            },
            success: function(response) {
                $('#list_of_auth_account').html(response);
                $('#spinner').fadeOut();
            }
        });
    }

    const register_auth_account = () => {
        var empIdField = document.getElementById('emp_id_m');
        var empNameField = document.getElementById('name_m');
        var empDeptField = document.getElementById('department_m');

        var emp_id = empIdField.value.trim();
        var emp_name = empNameField.value.trim();
        var emp_dept = empDeptField.value.trim();

        empIdField.classList.remove('is-invalid');
        empNameField.classList.remove('is-invalid');
        empDeptField.classList.remove('is-invalid');

        if (!emp_id || !emp_name || !emp_dept) {
            if (!emp_id) empIdField.classList.add('is-invalid');
            if (!emp_name) empNameField.classList.add('is-invalid');
            if (!emp_dept) empDeptField.classList.add('is-invalid');

            Swal.fire({
                icon: 'warning',
                title: 'Missing Information',
                text: 'Please fill out all required fields.',
                showConfirmButton: false,
                timer: 1500
            });
            return;
        }

        $.ajax({
            url: '../process/index_m_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'register_auth_account',
                emp_id: emp_id,
                emp_name: emp_name,
                emp_dept: emp_dept
            },
            success: function(response) {
                if (response == 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Authorized Account Added',
                        text: 'Success',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $('#emp_id_m').val('').removeClass('is-invalid');
                    $('#name_m').val('').removeClass('is-invalid');
                    $('#department_m').val('').removeClass('is-invalid');
                    load_auth_account();
                    $('#add_auth_account').modal('hide');
                } else if (response == 'Already Exist') {
                    Swal.fire({
                        icon: 'info',
                        title: 'Duplicate Data',
                        showConfirmButton: false,
                        timer: 1500
                    });
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
    }

    const delete_added_auth_account = (event) => {
        var id = event.target.dataset.id;

        $.ajax({
            url: '../process/index_m_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'delete_added_auth_account',
                id: id
            },
            success: function(response) {
                if (response == 'success') {
                    Swal.fire({
                        icon: 'info',
                        text: 'Deleted, please wait.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    load_auth_account();
                }
            }
        });
    }

    // for line car model
    const load_line_car_model = () => {
        $.ajax({
            url: '../process/index_m_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'line_car_model_list'
            },
            success: function(response) {
                $('#list_of_line_car_model').html(response);
                $('#spinner').fadeOut();
            }
        });
    }

    const register_line_car_model = () => {
        var lineNoField = document.getElementById('cm_line_no');
        var sectionField = document.getElementById('cm_section');
        var carMakerField = document.getElementById('cm_car_maker');
        var carModelField = document.getElementById('cm_car_model');

        var line_no = lineNoField.value.trim();
        var section = sectionField.value.trim();
        var car_maker = carMakerField.value.trim();
        var car_model = carModelField.value.trim();

        lineNoField.classList.remove('is-invalid');
        sectionField.classList.remove('is-invalid');
        carMakerField.classList.remove('is-invalid');
        carModelField.classList.remove('is-invalid');

        if (!line_no || !section || !car_maker || !car_model) {
            if (!line_no) lineNoField.classList.add('is-invalid');
            if (!section) sectionField.classList.add('is-invalid');
            if (!car_maker) carMakerField.classList.add('is-invalid');
            if (!car_model) carModelField.classList.add('is-invalid');

            Swal.fire({
                icon: 'warning',
                title: 'Missing Information',
                text: 'Please fill out all required fields.',
                showConfirmButton: false,
                timer: 1500
            });
            return;
        }

        $.ajax({
            url: '../process/index_m_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'register_line_car_model',
                line_no: line_no,
                section: section,
                car_maker: car_maker,
                car_model: car_model
            },
            success: function(response) {
                if (response == 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Car Model Added',
                        text: 'Success',
                        showConfirmButton: false,
                        timer: 1500
                    });

                    $('#cm_line_no').val('').removeClass('is-invalid');
                    $('#cm_section').val('').removeClass('is-invalid');
                    $('#cm_car_maker').val('').removeClass('is-invalid');
                    $('#cm_car_model').val('').removeClass('is-invalid');

                    load_line_car_model();
                    $('#add_line_car_model').modal('hide');
                } else if (response == 'Already Exist') {
                    Swal.fire({
                        icon: 'info',
                        title: 'Duplicate Data',
                        showConfirmButton: false,
                        timer: 1500
                    });
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
    }

    const delete_added_line_car_model = (event) => {
        var id = event.target.dataset.id;

        $.ajax({
            url: '../process/index_m_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'delete_added_line_car_model',
                id: id
            },
            success: function(response) {
                if (response == 'success') {
                    Swal.fire({
                        icon: 'info',
                        text: 'Deleted, please wait.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    load_line_car_model();
                }
            }
        });
    }
</script>