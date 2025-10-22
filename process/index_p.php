<?php
include 'conn.php';
include 'conn_pcad.php';

$method = $_POST['method'];

function count_defect_list($conn, $scan_qr, $scan_product_name, $scan_lot_no, $scan_serial_no, $search_process, $search_line_no, $search_date_from, $search_date_to, $search_defect_category, $search_defect_details, $search_car_maker)
{
    $query = "SELECT COUNT(id) AS total FROM t_minor_defect_f";
    $conditions = [];
    $params = [];

    if (!empty($search_date_from) && !empty($search_date_to)) {
        $conditions[] = "date_detected BETWEEN :search_date_from AND :search_date_to";
        $params[':search_date_from'] = $search_date_from;
        $params[':search_date_to'] = $search_date_to;
    }

    if (!empty($scan_qr) && $scan_qr !== '%') {
        $conditions[] = "nameplate_value LIKE :nameplate_value";
        $params[':nameplate_value'] = '%' . $scan_qr . '%';
    }

    if (!empty($scan_product_name) && $scan_product_name !== '%') {
        $conditions[] = "product_no LIKE :product_no";
        $params[':product_no'] = '%' . $scan_product_name . '%';
    }

    if (!empty($scan_lot_no) && $scan_lot_no !== '%') {
        $conditions[] = "lot_no LIKE :lot_no";
        $params[':lot_no'] = '%' . $scan_lot_no . '%';
    }

    if (!empty($scan_serial_no) && $scan_serial_no !== '%') {
        $conditions[] = "serial_no LIKE :serial_no";
        $params[':serial_no'] = '%' . $scan_serial_no . '%';
    }

    if (!empty($search_process) && $search_process !== '%') {
        $conditions[] = "process LIKE :process";
        $params[':process'] = '%' . $search_process . '%';
    }

    if (!empty($search_line_no) && $search_line_no !== '%') {
        $conditions[] = "line_no LIKE :line_no";
        $params[':line_no'] = '%' . $search_line_no . '%';
    }

    if (!empty($search_defect_category) && $search_defect_category !== '%') {
        $conditions[] = "defect_category LIKE :defect_category";
        $params[':defect_category'] = '%' . $search_defect_category . '%';
    }

    if (!empty($search_defect_details) && $search_defect_details !== '%') {
        $conditions[] = "defect_details LIKE :defect_details";
        $params[':defect_details'] = '%' . $search_defect_details . '%';
    }

    if (!empty($search_car_maker) && $search_car_maker !== '%') {
        $conditions[] = "car_maker LIKE :car_maker";
        $params[':car_maker'] = '%' . $search_car_maker . '%';
    }

    if (!empty($conditions)) {
        $query .= " WHERE " . implode(" AND ", $conditions);
    }

    $stmt = $conn->prepare($query);

    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }

    $stmt->execute();
    $total = $stmt->fetchColumn();

    return $total;
}

if ($method == 'count_defect_list') {
    $scan_qr = trim($_POST['scan_qr']);
    $scan_product_name = trim($_POST['scan_product_name']);
    $scan_lot_no = trim($_POST['scan_lot_no']);
    $scan_serial_no = trim($_POST['scan_serial_no']);
    $search_process = trim($_POST['search_process']);
    $search_line_no = trim($_POST['search_line_no']);
    $search_date_from = trim($_POST['search_date_from']);
    $search_date_to = trim($_POST['search_date_to']);
    $search_defect_category = trim($_POST['search_defect_category']);
    $search_defect_details = trim($_POST['search_defect_details']);
    $search_car_maker = trim($_POST['search_car_maker']);

    echo count_defect_list($conn, $scan_qr, $scan_product_name, $scan_lot_no, $scan_serial_no, $search_process, $search_line_no, $search_date_from, $search_date_to, $search_defect_category, $search_defect_details, $search_car_maker);
}

if ($method == 'defect_list_last_page') {
    $scan_qr = trim($_POST['scan_qr']);
    $scan_product_name = trim($_POST['scan_product_name']);
    $scan_lot_no = trim($_POST['scan_lot_no']);
    $scan_serial_no = trim($_POST['scan_serial_no']);
    $search_process = trim($_POST['search_process']);
    $search_line_no = trim($_POST['search_line_no']);
    $search_date_from = trim($_POST['search_date_from']);
    $search_date_to = trim($_POST['search_date_to']);
    $search_defect_category = trim($_POST['search_defect_category']);
    $search_defect_details = trim($_POST['search_defect_details']);
    $search_car_maker = trim($_POST['search_car_maker']);

    $results_per_page = 200;
    $number_of_result = count_defect_list($conn, $scan_qr, $scan_product_name, $scan_lot_no, $scan_serial_no, $search_process, $search_line_no, $search_date_from, $search_date_to, $search_defect_category, $search_defect_details, $search_car_maker);
    $number_of_page = ceil($number_of_result / $results_per_page);

    echo $number_of_page;
}

if ($method == 'load_defect_list') {
    $current_page = max(1, intval($_POST['current_page']));
    $results_per_page = 200;
    $offset = ($current_page - 1) * $results_per_page;
    $counter = $offset;

    // Collect and trim inputs
    $filters = [
        'scan_qr'              => $_POST['scan_qr'] ?? '',
        'scan_product_name'    => $_POST['scan_product_name'] ?? '',
        'scan_lot_no'          => $_POST['scan_lot_no'] ?? '',
        'scan_serial_no'       => $_POST['scan_serial_no'] ?? '',
        'search_process'       => $_POST['search_process'] ?? '',
        'search_line_no'       => $_POST['search_line_no'] ?? '',
        'search_date_from'     => $_POST['search_date_from'] ?? '',
        'search_date_to'       => $_POST['search_date_to'] ?? '',
        'search_defect_category' => $_POST['search_defect_category'] ?? '',
        'search_defect_details' => $_POST['search_defect_details'] ?? '',
        'search_car_maker'      => $_POST['search_car_maker'] ?? ''
    ];

    foreach ($filters as &$value) $value = trim($value);

    $query = "SELECT * FROM t_minor_defect_f";
    $conditions = [];
    $params = [];

    // Date range
    if ($filters['search_date_from'] && $filters['search_date_to']) {
        $conditions[] = "date_detected BETWEEN :date_from AND :date_to";
        $params[':date_from'] = $filters['search_date_from'];
        $params[':date_to']   = $filters['search_date_to'];
    }

    // Pattern-based filters
    $map = [
        'scan_qr'               => 'nameplate_value',
        'scan_product_name'     => 'product_no',
        'scan_lot_no'           => 'lot_no',
        'scan_serial_no'        => 'serial_no',
        'search_process'        => 'process',
        'search_line_no'        => 'line_no',
        'search_defect_category' => 'defect_category',
        'search_defect_details' => 'defect_details',
        'search_car_maker'      => 'car_maker',
    ];

    foreach ($map as $key => $column) {
        if (!empty($filters[$key]) && $filters[$key] !== '%') {
            $conditions[] = "$column LIKE :$key";
            $params[":$key"] = '%' . $filters[$key] . '%';
        }
    }

    if ($conditions) {
        $query .= ' WHERE ' . implode(' AND ', $conditions);
    }

    $query .= " ORDER BY date_detected DESC 
                OFFSET :offset ROWS FETCH NEXT :limit ROWS ONLY";

    try {
        $stmt = $conn->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $results_per_page, PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($rows) {
            foreach ($rows as $row) {
                $counter++;
                echo '<tr class="text-center">';
                echo '<td>' . $counter . '</td>';
                echo '<td>' . htmlspecialchars($row['date_detected']) . '</td>';
                echo '<td>' . htmlspecialchars($row['car_maker']) . '</td>';
                echo '<td>' . htmlspecialchars($row['car_model']) . '</td>';
                echo '<td>' . htmlspecialchars($row['line_no']) . '</td>';
                echo '<td>' . htmlspecialchars($row['harness_type']) . '</td>';
                echo '<td>' . htmlspecialchars($row['line_category']) . '</td>';
                echo '<td>' . htmlspecialchars($row['process']) . '</td>';
                echo '<td>' . htmlspecialchars($row['group_d']) . '</td>';
                echo '<td>' . htmlspecialchars($row['shift']) . '</td>';
                echo '<td>' . htmlspecialchars($row['product_no']) . '</td>';
                echo '<td>' . htmlspecialchars($row['lot_no']) . '</td>';
                echo '<td>' . htmlspecialchars($row['serial_no']) . '</td>';
                echo '<td>' . htmlspecialchars($row['defect_category_code']) . '</td>';
                echo '<td>' . htmlspecialchars($row['defect_category']) . '</td>';
                echo '<td>' . htmlspecialchars($row['defect_details_code']) . '</td>';
                echo '<td>' . htmlspecialchars($row['defect_details']) . '</td>';
                echo '<td>' . htmlspecialchars($row['treatment_content_defect']) . '</td>';
                echo '<td>' . htmlspecialchars($row['sequence_no']) . '</td>';
                echo '<td>' . htmlspecialchars($row['connector_no']) . '</td>';
                echo '<td>' . htmlspecialchars($row['total_mins']) . '</td>';
                echo '<td>' . htmlspecialchars($row['repaired_by']) . '</td>';
                echo '<td>' . htmlspecialchars($row['verified_by']) . '</td>';
                echo '<td>' . htmlspecialchars($row['record_added_by']) . '</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="18" class="text-center text-danger">No Record Found</td></tr>';
        }
    } catch (PDOException $e) {
        echo '<tr><td colspan="18" class="text-center text-danger">Query failed: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
    }
}

if ($method == 'fetch_search_defect_category') {
    $query = "SELECT defect_category_dc FROM m_defect_category ORDER BY defect_category_dc ASC";
    $stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        echo '<option value="" disabled selected>Select Defect Category</option>';
        foreach ($stmt->fetchAll() as $row) {
            echo '<option value="' . htmlspecialchars($row['defect_category_dc']) . '">' . htmlspecialchars($row['defect_category_dc']) . '</option>';
        }
    } else {
        echo '<option value="">Select Defect Category</option>';
    }
}

if ($method == 'fetch_search_defect_details') {
    $query = "SELECT DISTINCT defect_details_dd FROM m_defect_details ORDER BY defect_details_dd ASC";
    $stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        echo '<option value="" disabled selected>Select Defect Details</option>';
        foreach ($stmt->fetchAll() as $row) {
            echo '<option>' . htmlspecialchars($row['defect_details_dd']) . '</option>';
        }
    } else {
        echo '<option value="">Select Defect Details</option>';
    }
}

if ($method == 'fetch_search_process') {
    $query = "SELECT process_p FROM m_process ORDER BY process_p ASC";
    $stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        echo '<option value="" disabled selected>Select Process</option>';
        foreach ($stmt->fetchAll() as $row) {
            echo '<option>' . htmlspecialchars($row['process_p']) . '</option>';
        }
    } else {
        echo '<option value="">Select Process</option>';
    }
}

if ($method == 'fetch_defect_category_by_code' && isset($_POST['code'])) {
    $code = strtoupper(trim($_POST['code']));
    $query = "SELECT TOP 1 defect_code_value_dd 
                FROM m_defect_details 
                WHERE defect_code_dd = :code";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':code', $code);
    $stmt->execute();
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo htmlspecialchars($row['defect_code_value_dd']);
    } else {
        echo '';
    }
}

if ($method == 'fetch_defect_details_by_code' && isset($_POST['details_code'])) {
    $details_code = strtoupper(trim($_POST['details_code']));

    $query = "SELECT TOP 1 defect_details_dd, defect_treatment_dd 
              FROM m_defect_details 
              WHERE defect_sub_code_dd = :details_code";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':details_code', $details_code);
    $stmt->execute();

    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo json_encode([
            'details' => $row['defect_details_dd'],
            'treatment' => $row['defect_treatment_dd']
        ]);
    } else {
        echo json_encode(['error' => true]);
    }
}

function generate_defect_id($defect_id)
{
    if (empty($defect_id)) {
        $prefix = 'MDR-';
        $unique_part = uniqid('', true);
        $defect_id = $prefix . $unique_part;
    }
    return $defect_id;
}

if ($method == 'add_defect_record') {
    $date_detected = trim($_POST['date_detected']);
    $car_maker = trim($_POST['car_maker']);
    $car_model = trim($_POST['car_model']);
    $line_no = trim($_POST['line_no']);
    $harness_type = trim($_POST['harness_type']);
    $process = trim($_POST['process']);
    $group = trim($_POST['group']);
    $shift = trim($_POST['shift']);
    $nameplate_value = $_POST['nameplate_value'];
    $product_name = trim($_POST['product_name']);
    $lot_no = trim($_POST['lot_no']);
    $serial_no = trim($_POST['serial_no']);
    $defect_category_code = trim($_POST['defect_category_code']);
    $defect_category = trim($_POST['defect_category']);
    $defect_details_code = trim($_POST['defect_details_code']);
    $defect_details = trim($_POST['defect_details']);
    $sequence_no = trim($_POST['sequence_no']);
    $connector_no = trim($_POST['connector_no']);
    $treatment_content_defect = trim($_POST['treatment_content_defect']);
    $total_time = trim($_POST['total_time']);
    $repaired_by = trim($_POST['repaired_by']);
    $verified_by = trim($_POST['verified_by']);
    $defect_id = trim($_POST['defect_id']);
    $ip_address = trim($_POST['ip_address']);
    $auth_id_no = trim($_POST['auth_id_no']);
    $auth_name = trim($_POST['auth_name']);

    if ($line_no === "2130" || $line_no === "2132") {
        if (isset($_POST['category']) && trim($_POST['category']) !== '') {
            $category = trim($_POST['category']);
        } else {
            echo 'error_category_required';
            exit;
        }
    } else {
        $category = "N/A";
    }

    $defect_id = generate_defect_id($defect_id);

    $query = "INSERT INTO t_minor_defect_f 
        (defect_id, date_detected, car_maker, car_model, line_no, harness_type,
        process, group_d, shift, nameplate_value, product_no,
        lot_no, serial_no, defect_category_code, defect_category, defect_details_code,
        defect_details, sequence_no, connector_no, treatment_content_defect, repaired_by,
        verified_by, ip_address, record_by_id_no, record_added_by, line_category, total_time) 
        VALUES (:defect_id, :date_detected, :car_maker, :car_model, :line_no, :harness_type,
        :process, :group_d, :shift, :nameplate_value, :product_no,
        :lot_no, :serial_no, :defect_category_code, :defect_category, :defect_details_code,
        :defect_details, :sequence_no, :connector_no, :treatment_content_defect, :repaired_by,
        :verified_by, :ip_address, :record_by_id_no, :record_added_by, :category, :total_time)";

    $stmt = $conn->prepare($query);

    $success = $stmt->execute([
        ':defect_id' => $defect_id,
        ':date_detected' => $date_detected,
        ':car_maker' => $car_maker,
        ':car_model' => $car_model,
        ':line_no' => $line_no,
        ':process' => $process,
        ':group_d' => $group,
        ':shift' => $shift,
        ':nameplate_value' => $nameplate_value,
        ':product_no' => $product_name,
        ':lot_no' => $lot_no,
        ':serial_no' => $serial_no,
        ':defect_category_code' => $defect_category_code,
        ':defect_category' => $defect_category,
        ':defect_details_code' => $defect_details_code,
        ':defect_details' => $defect_details,
        ':sequence_no' => $sequence_no,
        ':connector_no' => $connector_no,
        ':treatment_content_defect' => $treatment_content_defect,
        ':repaired_by' => $repaired_by,
        ':verified_by' => $verified_by,
        ':ip_address' => $ip_address,
        ':record_by_id_no' => $auth_id_no,
        ':record_added_by' => $auth_name,
        ':category' => $category,
        ':total_time' => $total_time,
        ':harness_type' => $harness_type,
    ]);

    echo $success ? 'success' : 'error';
}

if ($method == 'check_auth_id') {
    $id_no = $_POST['id_no'] ?? '';

    if (!empty($id_no)) {
        $query = "SELECT emp_no, emp_name FROM m_auth_accounts WHERE emp_no = ?";
        $stmt = $conn->prepare($query);

        if ($stmt) {
            $stmt->execute([$id_no]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'emp_name' => $result['emp_name']
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'error' => 'Please contact IT-System Group to register your ID number.'
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'error' => 'Failed to prepare statement'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'error' => 'ID number is empty'
        ]);
    }
}
