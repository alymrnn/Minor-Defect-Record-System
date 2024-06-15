<?php
include 'conn.php';
include 'conn_pcad.php';

$method = $_GET['method'];

// GETTING DETAILS ONLY WITHOUT VALIDATION OF IP ADDRESS EXISTENCE
// if ($method == 'get_inspection_details') {
//     $ip_address = $_GET['ip_address'];

//     $response = array('line_no' => '', 'process' => '');

//     $query1 = "SELECT line_no FROM m_ircs_line WHERE ip = ?";
//     $stmt1 = $conn_pcad->prepare($query1);
//     $stmt1->execute([$ip_address]);

//     if ($stmt1->rowCount() > 0) {
//         $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);
//         $response['line_no'] = $row1['line_no'];
//     }

//     $query2 = "SELECT process FROM m_inspection_ip WHERE ip_address = ?";
//     $stmt2 = $conn_pcad->prepare($query2);
//     $stmt2->execute([$ip_address]);

//     if ($stmt2->rowCount() > 0) {
//         $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
//         $response['process'] = $row2['process'];
//     }

// $query3 = "SELECT car_maker FROM m_ircs_line WHERE ip = ?";
// $stmt3 = $conn_pcad->prepare($query3);
// $stmt3->execute([$ip_address]);

// if ($stmt3->rowCount() > 0) {
//     $row3 = $stmt3->fetch(PDO::FETCH_ASSOC);
//     $response['car_maker'] = $row3['car_maker'];
// }

// $query4 = "SELECT car_model FROM m_ircs_line WHERE ip = ?";
// $stmt4 = $conn_pcad->prepare($query4);
// $stmt4->execute([$ip_address]);

// if ($stmt4->rowCount() > 0) {
//     $row4 = $stmt4->fetch(PDO::FETCH_ASSOC);
//     $response['car_model'] = $row4['car_model'];
// }

//     echo json_encode($response);
//     exit;
// }


// WITH CHECKING OF IP ADDRESS IF EXISTS IN BOTH TABLES
// if ($method == 'get_inspection_details') {
//     $ip_address = $_GET['ip_address'];

//     $query1 = "SELECT process FROM m_inspection_ip WHERE ip_address = ?";
//     $stmt1 = $conn_pcad->prepare($query1);
//     $stmt1->execute([$ip_address]);
//     $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);
//     $process_exists = ($stmt1->rowCount() > 0);

//     $query2 = "SELECT line_no FROM m_ircs_line WHERE ip = ?";
//     $stmt2 = $conn_pcad->prepare($query2);
//     $stmt2->execute([$ip_address]);
//     $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
//     $line_exists = ($stmt2->rowCount() > 0);

//     $query3 = "SELECT car_maker, car_model FROM m_ircs_line WHERE ip = ?";
//     $stmt3 = $conn_pcad->prepare($query3);
//     $stmt3->execute([$ip_address]);
//     $row3 = $stmt3->fetch(PDO::FETCH_ASSOC);
//     $car_maker_exists = !empty($row3['car_maker']);
//     $car_model_exists = !empty($row3['car_model']);

//     if ($process_exists && $line_exists && $car_maker_exists && $car_model_exists) {
//         echo json_encode(
//             array(
//                 'success' => true,
//                 'car_maker' => $row3['car_maker'],
//                 'car_model' => $row3['car_model'],
//                 'line_no' => $row2['line_no'],
//                 'process' => $row1['process']
//             )
//         );
//         exit;
//     } else {
//         echo json_encode(
//             array(
//                 'error' => 'IP address is not registered in the inspection masterlist'
//             )
//         );
//         exit;
//     }
// }

// CHECKING USING IRCS LINE COLUMN
if ($method == 'get_inspection_details') {
    $ip_address = $_GET['ip_address'];

    // Check if the IP address exists in m_inspection_ip
    $query1 = "SELECT process, ircs_line FROM m_inspection_ip WHERE ip_address = ?";
    $stmt1 = $conn_pcad->prepare($query1);
    $stmt1->execute([$ip_address]);
    $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);

    if ($stmt1->rowCount() > 0) {
        $ircs_line = $row1['ircs_line'];
        $process = $row1['process'];

        // Check if the same ircs_line exists in m_ircs_line
        $query2 = "SELECT car_maker, car_model, line_no FROM m_ircs_line WHERE ircs_line = ?";
        $stmt2 = $conn_pcad->prepare($query2);
        $stmt2->execute([$ircs_line]);
        $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);

        if ($stmt2->rowCount() > 0) {
            echo json_encode(
                array(
                    'success' => true,
                    'car_maker' => $row2['car_maker'],
                    'car_model' => $row2['car_model'],
                    'line_no' => $row2['line_no'],
                    'process' => $process
                )
            );
        } else {
            echo json_encode(
                array(
                    'error' => 'No matching IRCS line found in m_ircs_line'
                )
            );
        }
    } else {
        echo json_encode(
            array(
                'error' => 'IP address is not registered in m_inspection_ip'
            )
        );
    }

    exit;
}

?>