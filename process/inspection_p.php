<?php
include 'conn.php';
include 'conn_pcad.php';

$method = $_GET['method'];

// CHECKING USING IRCS LINE COLUMN
// FETCHING CAR MAKER AND MODEL BASED ON LINE NO AND FETCHING OF PROCESSES BASED ON IRCS LINE

if ($method == 'get_inspection_details') {
    $line_no = $_GET['line_no'];

    // Step 1: Fetch car maker, car model, and IRCS line
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

    $car_maker = $row_ircs['car_maker'];
    $car_model = $row_ircs['car_model'];
    $ircs_line = $row_ircs['ircs_line'];

    // Step 2: Fetch distinct processes for the car maker and model
    $query = "SELECT DISTINCT process FROM m_inspection_ip WHERE ircs_line = ?";
    $stmt = $conn_pcad->prepare($query);
    $stmt->execute([$ircs_line]);
    $processes = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (!$processes || count($processes) === 0) {
        echo json_encode([
            'success' => false,
            'error' => 'No processes found for this car maker and model.'
        ]);
        exit;
    }

    // Step 3: Fetch QR settings only if processes exist
    $query = "SELECT total_length, product_name_start, product_name_length, lot_no_start, lot_no_length, serial_no_start, serial_no_length 
              FROM m_car_qr_setting WHERE car_maker = ? AND car_model = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$car_maker, $car_model]);
    $qr_settings = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$qr_settings) {
        echo json_encode([
            'success' => false,
            'error' => 'QR settings not found for this car maker and model.'
        ]);
        exit;
    }

    // Step 4: Construct and return the response
    $response = [
        'success' => true,
        'car_maker' => $car_maker,
        'car_model' => $car_model,
        'processes' => $processes,
        'qr_settings' => $qr_settings
    ];

    echo json_encode($response);
    exit;
}


?>