<?php
include 'conn.php';
include 'conn_pcad.php';

$method = $_POST['method'];

function count_defect_list($conn, $scan_product_name, $scan_lot_no, $scan_serial_no, $search_process, $search_line_no, $search_date_from, $search_date_to, $search_defect_category, $search_defect_details)
{
    $query = "SELECT count(id) AS total FROM t_minor_defect_f";
    $conditions = [];
    $params = [];

    if (!empty($date_from) && !empty($date_to)) {
        $conditions[] = "date_detected BETWEEN ? AND ?";
        $params[] = $search_date_from;
        $params[] = $search_date_to;
    }

    if (!empty($scan_product_name) && $scan_product_name !== '%') {
        $conditions[] = "product_no LIKE ?";
        $params[] = '%' . $scan_product_name . '%';
    }

    if (!empty($scan_lot_no) && $scan_lot_no !== '%') {
        $conditions[] = "lot_no LIKE ?";
        $params[] = '%' . $scan_lot_no . '%';
    }

    if (!empty($scan_serial_no) && $scan_serial_no !== '%') {
        $conditions[] = "serial_no LIKE ?";
        $params[] = '%' . $scan_serial_no . '%';
    }

    if (!empty($search_process) && $search_process !== '%') {
        $conditions[] = "process LIKE ?";
        $params[] = '%' . $search_process . '%';
    }

    if (!empty($search_line_no) && $search_line_no !== '%') {
        $conditions[] = "line_no LIKE ?";
        $params[] = '%' . $search_line_no . '%';
    }

    if (!empty($search_defect_category) && $search_defect_category !== '%') {
        $conditions[] = "defect_category LIKE ?";
        $params[] = '%' . $search_defect_category . '%';
    }

    if (!empty($search_defect_details) && $search_defect_details !== '%') {
        $conditions[] = "defect_details LIKE ?";
        $params[] = '%' . $search_defect_details . '%';
    }


    if (!empty($conditions)) {
        $query .= " WHERE " . implode(" AND ", $conditions);
    }

    $stmt = $conn->prepare($query);
    $stmt->execute($params);

    $total = $stmt->fetchColumn();

    return $total;
}

if ($method == 'count_defect_list') {
    $scan_product_name = trim($_POST['scan_product_name']);
    $scan_lot_no = trim($_POST['scan_lot_no']);
    $scan_serial_no = trim($_POST['scan_serial_no']);
    $search_process = trim($_POST['search_process']);
    $search_line_no = trim($_POST['search_line_no']);

    $search_date_from = trim($_POST['search_date_from']);
    if (!empty($search_date_from)) {
        $search_date_from = date_create($search_date_from);
        $search_date_from = date_format($search_date_from, "Y/m/d");
    }

    $search_date_to = trim($_POST['search_date_to']);
    if (!empty($search_date_to)) {
        $search_date_to = date_create($search_date_to);
        $search_date_to = date_format($search_date_to, "Y/m/d");
    }

    $search_defect_category = trim($_POST['search_defect_category']);
    $search_defect_details = trim($_POST['search_defect_details']);

    echo count_defect_list($conn, $scan_product_name, $scan_lot_no, $scan_serial_no, $search_process, $search_line_no, $search_date_from, $search_date_to, $search_defect_category, $search_defect_details);
}

if ($method == 'defect_list_last_page') {
    $scan_product_name = trim($_POST['scan_product_name']);
    $scan_lot_no = trim($_POST['scan_lot_no']);
    $scan_serial_no = trim($_POST['scan_serial_no']);
    $search_process = trim($_POST['search_process']);
    $search_line_no = trim($_POST['search_line_no']);

    $search_date_from = trim($_POST['search_date_from']);
    if (!empty($search_date_from)) {
        $search_date_from = date_create($search_date_from);
        $search_date_from = date_format($search_date_from, "Y/m/d");
    }

    $search_date_to = trim($_POST['search_date_to']);
    if (!empty($search_date_to)) {
        $search_date_to = date_create($search_date_to);
        $search_date_to = date_format($search_date_to, "Y/m/d");
    }

    $search_defect_category = trim($_POST['search_defect_category']);
    $search_defect_details = trim($_POST['search_defect_details']);

    $results_per_page = 10;
    $number_of_result = intval(count_defect_list($conn, $scan_product_name, $scan_lot_no, $scan_serial_no, $search_process, $search_line_no, $search_date_from, $search_date_to, $search_defect_category, $search_defect_details));

    $number_of_page = ceil($number_of_result / $results_per_page);

    echo $number_of_page;
}


if ($method == 'load_defect_list') {
    $current_page = intval($_POST['current_page']);

    $scan_product_name = trim($_POST['scan_product_name']);
    $scan_lot_no = trim($_POST['scan_lot_no']);
    $scan_serial_no = trim($_POST['scan_serial_no']);
    $search_process = trim($_POST['search_process']);
    $search_line_no = trim($_POST['search_line_no']);

    $search_date_from = trim($_POST['search_date_from']);
    if (!empty($search_date_from)) {
        $search_date_from = date_create($search_date_from);
        $search_date_from = date_format($search_date_from, "Y/m/d");
    }

    $search_date_to = trim($_POST['search_date_to']);
    if (!empty($search_date_to)) {
        $search_date_to = date_create($search_date_to);
        $search_date_to = date_format($search_date_to, "Y/m/d");
    }

    $search_defect_category = trim($_POST['search_defect_category']);
    $search_defect_details = trim($_POST['search_defect_details']);

    $c = 0;

    $results_per_page = 10;

    $page_first_result = ($current_page - 1) * $results_per_page;

    $c = $page_first_result;

    $query = "SELECT * FROM t_minor_defect_f";
    $conditions = [];

    if (!empty($date_from) && !empty($date_to)) {
        $conditions[] = "date_detected BETWEEN '$search_date_from' AND '$search_date_to'";
    }

    if (!empty($scan_product_name) && $scan_product_name !== '%') {
        $conditions[] = "product_no LIKE '$scan_product_name'";
    }

    if (!empty($scan_lot_no) && $scan_lot_no !== '%') {
        $conditions[] = "lot_no LIKE '$scan_lot_no'";
    }

    if (!empty($scan_serial_no) && $scan_serial_no !== '%') {
        $conditions[] = "serial_no LIKE '$scan_serial_no'";
    }

    if (!empty($search_process) && $search_process !== '%') {
        $conditions[] = "process LIKE '$search_process'";
    }

    if (!empty($search_line_no) && $search_line_no !== '%') {
        $conditions[] = "line_no LIKE '$search_line_no'";
    }

    if (!empty($search_defect_category) && $search_defect_category !== '%') {
        $conditions[] = "defect_category LIKE '$search_defect_category'";
    }

    if (!empty($search_defect_details) && $search_defect_details !== '%') {
        $conditions[] = "defect_details LIKE '$search_defect_details'";
    }

    if (!empty($conditions)) {
        $query .= " WHERE " . implode(" AND ", $conditions);
    }

    $query .= " ORDER BY date_added DESC";

    $query .= " LIMIT " . $page_first_result . ", " . $results_per_page;

    $stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        foreach ($stmt->fetchALL() as $row) {
            $c++;
            echo '<tr>';
            echo '<td style="text-align:center;">' . $c . '</td>';
            echo '<td style="text-align:center;">' . $row['date_detected'] . '</td>';
            echo '<td style="text-align:center;">' . $row['car_model'] . '</td>';
            echo '<td style="text-align:center;">' . $row['line_no'] . '</td>';
            echo '<td style="text-align:center;">' . $row['process'] . '</td>';
            echo '<td style="text-align:center;">' . $row['group_d'] . '</td>';
            echo '<td style="text-align:center;">' . $row['shift'] . '</td>';
            echo '<td style="text-align:center;">' . $row['product_no'] . '</td>';
            echo '<td style="text-align:center;">' . $row['lot_no'] . '</td>';
            echo '<td style="text-align:center;">' . $row['serial_no'] . '</td>';
            echo '<td style="text-align:center;">' . $row['defect_category'] . '</td>';
            echo '<td style="text-align:center;">' . $row['defect_details'] . '</td>';
            echo '<td style="text-align:center;">' . $row['sequence_no'] . '</td>';
            echo '<td style="text-align:center;">' . $row['connector_no'] . '</td>';
            echo '<td style="text-align:center;">' . $row['repaired_by'] . '</td>';
            echo '<td style="text-align:center;">' . $row['verified_by'] . '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr>';
        echo '<td colspan="10" style="text-align:center; color:red;">No Record Found</td>';
        echo '</tr>';
    }
}

if ($method == 'fetch_search_defect_category') {
    $query = "SELECT `defect_category_dc` FROM m_defect_category ORDER BY defect_category_dc ASC";
    $stmt = $conn->prepare($query);
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
    $query = "SELECT DISTINCT `defect_details_dd` FROM m_defect_details ORDER BY defect_details_dd ASC";
    $stmt = $conn->prepare($query);
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

// if ($method == 'fetch_search_defect_details' && isset($_POST['search_category_value'])) {
//     $search_category_value = $_POST['search_category_value'];
//     $query = "SELECT `defect_details_dd` FROM m_defect_details WHERE defect_code_value_dd = :search_category_value ORDER BY defect_details_dd ASC";
//     $stmt = $conn->prepare($query);
//     $stmt->bindParam(':search_category_value', $search_category_value);
//     $stmt->execute();
//     if ($stmt->rowCount() > 0) {
//         echo '<option value="" disabled selected>Select Defect Details</option>';
//         foreach ($stmt->fetchAll() as $row) {
//             echo '<option>' . htmlspecialchars($row['defect_details_dd']) . '</option>';
//         }
//     } else {
//         echo '<option value="">Select Defect Details</option>';
//     }
// }

if ($method == 'fetch_defect_category') {
    $query = "SELECT `defect_category_dc` FROM m_defect_category ORDER BY defect_category_dc ASC";
    $stmt = $conn->prepare($query);
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

if ($method == 'fetch_defect_details' && isset($_POST['category_value'])) {
    $category_value = $_POST['category_value'];
    $query = "SELECT `defect_details_dd` FROM m_defect_details WHERE defect_code_value_dd = :category_value ORDER BY defect_details_dd ASC";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':category_value', $category_value);
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
    $car_model = trim($_POST['car_model']);
    $line_no = trim($_POST['line_no']);
    $process = trim($_POST['process']);
    $group = trim($_POST['group']);
    $shift = trim($_POST['shift']);
    $product_name = trim($_POST['product_name']);
    $lot_no = trim($_POST['lot_no']);
    $serial_no = trim($_POST['serial_no']);
    $defect_category = trim($_POST['defect_category']);
    $defect_details = trim($_POST['defect_details']);
    $sequence_no = trim($_POST['sequence_no']);
    $connector_no = trim($_POST['connector_no']);
    $repaired_by = trim($_POST['repaired_by']);
    $verified_by = trim($_POST['verified_by']);
    $defect_id = trim($_POST['defect_id']);

    $defect_id = generate_defect_id($defect_id);

    $query = "INSERT INTO t_minor_defect_f (`defect_id`,`date_detected`,`car_model`,`line_no`,`process`,`group_d`,`shift`,`product_no`,`lot_no`,`serial_no`,`defect_category`,`defect_details`,`sequence_no`,`connector_no`,`repaired_by`,`verified_by`) VALUES ('$defect_id','$date_detected','$car_model','$line_no','$process','$group','$shift','$product_name','$lot_no','$serial_no','$defect_category','$defect_details','$sequence_no','$connector_no','$repaired_by','$verified_by')";
    $stmt = $conn->prepare($query);
    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
}

?>