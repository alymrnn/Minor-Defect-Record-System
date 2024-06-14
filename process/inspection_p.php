<?php
include 'conn.php';
include 'conn_pcad.php';

$method = $_GET['method'];

if ($method == 'get_inspection_details') {
    $ip_address = $_GET['ip_address'];

    $response = array('line_no' => '', 'process' => '');

    $query1 = "SELECT line_no FROM m_ircs_line WHERE ip = ?";
    $stmt1 = $conn_pcad->prepare($query1);
    $stmt1->execute([$ip_address]);

    if ($stmt1->rowCount() > 0) {
        $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);
        $response['line_no'] = $row1['line_no'];
    }

    $query2 = "SELECT process FROM m_inspection_ip WHERE ip_address = ?";
    $stmt2 = $conn_pcad->prepare($query2);
    $stmt2->execute([$ip_address]);

    if ($stmt2->rowCount() > 0) {
        $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
        $response['process'] = $row2['process'];
    }

    $query3 = "SELECT car_maker FROM m_ircs_line WHERE ip = ?";
    $stmt3 = $conn_pcad->prepare($query3);
    $stmt3->execute([$ip_address]);

    if ($stmt3->rowCount() > 0) {
        $row3 = $stmt3->fetch(PDO::FETCH_ASSOC);
        $response['car_maker'] = $row3['car_maker'];
    }

    $query4 = "SELECT car_model FROM m_ircs_line WHERE ip = ?";
    $stmt4 = $conn_pcad->prepare($query4);
    $stmt4->execute([$ip_address]);

    if ($stmt4->rowCount() > 0) {
        $row4 = $stmt4->fetch(PDO::FETCH_ASSOC);
        $response['car_model'] = $row4['car_model'];
    }

    echo json_encode($response);
    exit;
}

?>