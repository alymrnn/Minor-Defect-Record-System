<?php
include 'conn.php';
include 'conn_pcad.php';

$method = $_GET['method'];

// CHECKING USING IRCS LINE COLUMN
// FETCHING CAR MAKER AND MODEL BASED ON LINE NO AND FETCHING OF PROCESES BASED ON IRCS LINE

if ($method == 'get_inspection_details') {
    $line_no = $_GET['line_no'];

    $query = "SELECT car_maker, car_model, ircs_line FROM m_ircs_line WHERE line_no = ?";
    $stmt = $conn_pcad->prepare($query);
    $stmt->execute([$line_no]);
    $row_ircs = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row_ircs) {
        echo json_encode([
            'success' => false,
            'error' => 'Line no. is not registered.'
        ]);
        exit;
    }

    $ircs_line = $row_ircs['ircs_line'];

    $query = "SELECT DISTINCT process FROM m_inspection_ip WHERE ircs_line = ?";
    $stmt = $conn_pcad->prepare($query);
    $stmt->execute([$ircs_line]);
    $processes = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $response = [
        'success' => true,
        'car_maker' => $row_ircs['car_maker'],
        'car_model' => $row_ircs['car_model'],
        'processes' => $processes 
    ];

    echo json_encode($response);
    exit;
}

// FETCHING CAR MAKER, CAR MODEL, LINE NO, AND PROCESS
// if ($method == 'get_inspection_details') {
//     $ip_address = $_GET['ip_address'];

//     // Check if the IP address exists in m_inspection_ip
//     $query1 = "SELECT process, ircs_line FROM m_inspection_ip WHERE ip_address = ?";
//     $stmt1 = $conn_pcad->prepare($query1, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
//     $stmt1->execute([$ip_address]);
//     $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);

//     if ($stmt1->rowCount() > 0) {
//         $ircs_line = $row1['ircs_line'];
//         $process = $row1['process'];

//         // Check if the same ircs_line exists in m_ircs_line
//         $query2 = "SELECT car_maker, car_model, line_no FROM m_ircs_line WHERE ircs_line = ?";
//         $stmt2 = $conn_pcad->prepare($query2, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
//         $stmt2->execute([$ircs_line]);
//         $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);

//         if ($stmt2->rowCount() > 0) {
//             echo json_encode(
//                 array(
//                     'success' => true,
//                     'car_maker' => $row2['car_maker'],
//                     'car_model' => $row2['car_model'],
//                     'line_no' => $row2['line_no'],
//                     'process' => $process
//                 )
//             );
//         } else {
//             echo json_encode(
//                 array(
//                     'error' => 'No matching IRCS line found in m_ircs_line'
//                 )
//             );
//         }
//     } else {
//         echo json_encode(
//             array(
//                 'error' => 'IP address is not registered in m_inspection_ip'
//             )
//         );
//     }
//     exit;
// }

// if ($method == 'check_ip_address') {
//     $ip_address = $_GET['ip_address'];

//     $query = "SELECT * FROM m_inspection_ip WHERE ip_address = ?";
//     $stmt = $conn_pcad->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
//     $stmt->execute([$ip_address]);
//     $row = $stmt->fetch(PDO::FETCH_ASSOC);

//     if ($stmt->rowCount() > 0) {
//         echo json_encode([
//             'success' => true,
//             'message' => 'IP address is registered.'
//         ]);
//     } else {
//         echo json_encode([
//             'success' => false,
//             'error' => 'IP address is not registered to add records.'
//         ]);
//     }
//     exit;
// }

?>