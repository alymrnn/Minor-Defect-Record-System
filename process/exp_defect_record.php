<?php
include 'conn.php';
ini_set("memory_limit", "-1");

$scan_product_name = $_GET['scan_product_name'];
$scan_lot_no = $_GET['scan_lot_no'];
$scan_serial_no = $_GET['scan_serial_no'];
$search_process = $_GET['search_process'];
$search_line_no = $_GET['search_line_no'];
$search_date_from = $_GET['search_date_from'];
$search_date_to = $_GET['search_date_to'];
$search_defect_category = $_GET['search_defect_category'];
$search_defect_details = $_GET['search_defect_details'];

$filename = 'Minor-Defect-Record_' . date("Y-m-d") . '.csv';
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '";');

$f = fopen('php://memory', 'w');

fputs($f, "\xEF\xBB\xBF");

$delimiter = ',';

$headers = array(
    'Date Detected',
    'Car Model',
    'Line No.',
    'Process',
    'Group',
    'Product Number',
    'Lot Number',
    'Serial Number',
    'Defect Category',
    'Defect Details',
    'Sequence No.',
    'Connector No.',
    'Repaired By',
    'Verified By'
);

fputcsv($f, $headers, $delimiter);

$query = "SELECT date_detected, car_model, line_no, process, shift, product_no, lot_no, serial_no, defect_category, defect_details, sequence_no, connector_no, repaired_by, verified_by FROM t_minor_defect_f WHERE 1=1";

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

if (count($conditions) > 0) {
    $query .= ' AND ' . implode(' AND ', $conditions);
}

$stmt = $conn->prepare($query);
$stmt->execute($params);

if ($stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $lineData = array(
            $row['date_detected'],
            $row['car_model'],
            $row['line_no'],
            $row['process'],
            $row['shift'],
            $row['product_no'],
            $row['lot_no'],
            $row['serial_no'],
            $row['defect_category'],
            $row['defect_details'],
            $row['sequence_no'],
            $row['connector_no'],
            $row['repaired_by'],
            $row['verified_by']
        );
        fputcsv($f, $lineData, $delimiter);
    }
} else {
    echo 'NO RECORD FOUND';
    exit;
}

fseek($f, 0);
fpassthru($f);

$conn = null;
?>