<?php
include 'conn.php';

$method = $_POST['method'];

function count_defect_list($search_arr, $conn)
{
    $query = "SELECT count(id) AS total FROM t_minor_defect_f WHERE product_no LIKE '" . $search_arr['scan_product_name'] . "%' AND lot_no LIKE '" . $search_arr['scan_lot_no'] . "%' AND serial_no LIKE '" . $search_arr['scan_serial_no'] . "%' AND process LIKE '" . $search_arr['search_process'] . "%' AND line_no LIKE '" . $search_arr['search_line_no'] . "%' AND date_detected LIKE '" . $search_arr['search_date_from'] . "%' AND date_detected LIKE '" . $search_arr['search_date_to'] . "%' AND defect_category LIKE '" . $search_arr['search_defect_category'] . "%' AND defect_details LIKE '" . $search_arr['search_defect_details'] . "%'";

    $stmt = $conn->prepare($query);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        foreach ($stmt->fetchALL() as $row) {
            $total = $row['total'];
        }
    } else {
        $total = 0;
    }
    return $total;
}

if ($method == 'count_defect_list') {
    $scan_product_name = $_POST['scan_product_name'];
    $scan_lot_no = $_POST['scan_lot_no'];
    $scan_serial_no = $_POST['scan_serial_no'];
    $search_process = $_POST['search_process'];
    $search_line_no = $_POST['search_line_no'];
    $search_date_from = $_POST['search_date_from'];
    $search_date_to = $_POST['search_date_to'];
    $search_defect_category = $_POST['search_defect_category'];
    $search_defect_details = $_POST['search_defect_details'];

    $search_arr = array(
        "scan_product_name" => $scan_product_name,
        "scan_lot_no" => $scan_lot_no,
        "scan_serial_no" => $scan_serial_no,
        "search_process" => $search_process,
        "search_line_no" => $search_line_no,
        "search_date_from" => $search_date_from,
        "search_date_to" => $search_date_to,
        "search_defect_category" => $search_defect_category,
        "search_defect_details" => $search_defect_details,
    );
    echo count_defect_list($search_arr, $conn);
}

if ($method == 'defect_list_pagination') {
    $scan_product_name = $_POST['scan_product_name'];
    $scan_lot_no = $_POST['scan_lot_no'];
    $scan_serial_no = $_POST['scan_serial_no'];
    $search_process = $_POST['search_process'];
    $search_line_no = $_POST['search_line_no'];
    $search_date_from = $_POST['search_date_from'];
    $search_date_to = $_POST['search_date_to'];
    $search_defect_category = $_POST['search_defect_category'];
    $search_defect_details = $_POST['search_defect_details'];

    $search_arr = array(
        "scan_product_name" => $scan_product_name,
        "scan_lot_no" => $scan_lot_no,
        "scan_serial_no" => $scan_serial_no,
        "search_process" => $search_process,
        "search_line_no" => $search_line_no,
        "search_date_from" => $search_date_from,
        "search_date_to" => $search_date_to,
        "search_defect_category" => $search_defect_category,
        "search_defect_details" => $search_defect_details,
    );

    $results_per_page = 10;

    $number_of_result = intval(count_defect_list($search_arr, $conn));

    //determine the total number of pages available
    $number_of_page = ceil($number_of_result / $results_per_page);

    for ($page = 1; $page <= $number_of_page; $page++) {
        echo '<option value="' . $page . '">' . $page . '</option>';
    }
}

if ($method == 'defect_list_last_page') {
    $scan_product_name = $_POST['scan_product_name'];
    $scan_lot_no = $_POST['scan_lot_no'];
    $scan_serial_no = $_POST['scan_serial_no'];
    $search_process = $_POST['search_process'];
    $search_line_no = $_POST['search_line_no'];
    $search_date_from = $_POST['search_date_from'];
    $search_date_to = $_POST['search_date_to'];
    $search_defect_category = $_POST['search_defect_category'];
    $search_defect_details = $_POST['search_defect_details'];

    $search_arr = array(
        "scan_product_name" => $scan_product_name,
        "scan_lot_no" => $scan_lot_no,
        "scan_serial_no" => $scan_serial_no,
        "search_process" => $search_process,
        "search_line_no" => $search_line_no,
        "search_date_from" => $search_date_from,
        "search_date_to" => $search_date_to,
        "search_defect_category" => $search_defect_category,
        "search_defect_details" => $search_defect_details,
    );

    $results_per_page = 10;
    $number_of_result = intval(count_defect_list($search_arr, $conn));

    $number_of_page = ceil($number_of_result / $results_per_page);

    echo $number_of_page;
}

if ($method == 'load_defect_table_list') {
    $scan_product_name = $_POST['scan_product_name'];
    $scan_lot_no = $_POST['scan_lot_no'];
    $scan_serial_no = $_POST['scan_serial_no'];
    $search_process = $_POST['search_process'];
    $search_line_no = $_POST['search_line_no'];
    $search_date_from = $_POST['search_date_from'];
    $search_date_to = $_POST['search_date_to'];
    $search_defect_category = $_POST['search_defect_category'];
    $search_defect_details = $_POST['search_defect_details'];

    $current_page = isset($_POST['current_page']) ? max(1, intval($_POST['current_page'])) : 1;
    $c = 0;

    $results_per_page = 10;

    $page_first_result = ($current_page - 1) * $results_per_page;

    $c = $page_first_result;

    $query = "SELECT * FROM t_minor_defect_f WHERE product_no LIKE '$scan_product_name%' AND lot_no LIKE '$scan_lot_no%' AND serial_no LIKE '$scan_serial_no%' AND process LIKE '$search_process%' AND line_no LIKE '$search_line_no%' AND date_detected LIKE '$search_date_from%' AND date_detected LIKE '$search_date_to%' AND defect_category LIKE '$search_defect_category%' AND defect_details LIKE '$search_defect_details%' LIMIT " . $page_first_result . ", " . $results_per_page;

    $stmt = $conn->prepare($query);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        foreach ($stmt->fetchALL() as $row) {
            $c++;
            echo '<tr';
            echo '<td style="text-align:center;">' . $c . '</td>';
            echo '<td style="text-align:center;">' . $row['date_detected'] . '</td>';
            echo '<td style="text-align:center;">' . $row['car_model'] . '</td>';
            echo '<td style="text-align:center;">' . $row['line_no'] . '</td>';
            echo '<td style="text-align:center;">' . $row['process'] . '</td>';
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

    $query = "INSERT INTO t_minor_defect_f (`defect_id`,`date_detected`,`car_model`,`line_no`,`process`,`shift`,`product_no`,`lot_no`,`serial_no`,`defect_category`,`defect_details`,`sequence_no`,`connector_no`,`repaired_by`,`verified_by`) VALUES ('$defect_id','$date_detected','$car_model','$line_no','$process','$shift','$product_name','$lot_no','$serial_no','$defect_category','$defect_details','$sequence_no','$connector_no','$repaired_by','$verified_by')";
    $stmt = $conn->prepare($query);
    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
}

?>