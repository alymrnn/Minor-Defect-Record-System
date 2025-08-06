<?php
include 'conn.php';
include 'conn_pcad.php';

$method = $_GET['method'];

// CHECKING USING IRCS LINE COLUMN
// FETCHING CAR MAKER AND MODEL BASED ON LINE NO AND FETCHING OF PROCESSES BASED ON IRCS LINE

// if ($method == 'get_inspection_details') {
//     $line_no = $_GET['line_no'];

//     // Step 1: Fetch car maker, car model, and IRCS line
//     $query = "SELECT car_maker, car_model, ircs_line FROM m_ircs_line WHERE line_no = ?";
//     $stmt = $conn_pcad->prepare($query);
//     $stmt->execute([$line_no]);
//     $row_ircs = $stmt->fetch(PDO::FETCH_ASSOC);

//     if (!$row_ircs) {
//         echo json_encode([
//             'success' => false,
//             'error' => 'Line no. is not registered.'
//         ]);
//         exit;
//     }

//     $car_maker = $row_ircs['car_maker'];
//     $car_model = $row_ircs['car_model'];
//     $ircs_line = $row_ircs['ircs_line'];

//     // Step 2: Fetch distinct processes for the car maker and model
//     $query = "SELECT DISTINCT process FROM m_inspection_ip WHERE ircs_line = ?";
//     $stmt = $conn_pcad->prepare($query);
//     $stmt->execute([$ircs_line]);
//     $processes = $stmt->fetchAll(PDO::FETCH_COLUMN);

//     if (!$processes || count($processes) === 0) {
//         echo json_encode([
//             'success' => false,
//             'error' => 'No processes found for this car maker and model.'
//         ]);
//         exit;
//     }

//     // Step 3: Fetch QR settings only if processes exist
//     $query = "SELECT total_length, product_name_start, product_name_length, lot_no_start, lot_no_length, serial_no_start, serial_no_length 
//               FROM m_car_qr_setting WHERE car_maker = ? AND car_model = ?";
//     $stmt = $conn->prepare($query);
//     $stmt->execute([$car_maker, $car_model]);
//     $qr_settings = $stmt->fetch(PDO::FETCH_ASSOC);

//     if (!$qr_settings) {
//         echo json_encode([
//             'success' => false,
//             'error' => 'QR settings not found for this car maker and model.'
//         ]);
//         exit;
//     }

//     // Step 4: Construct and return the response
//     $response = [
//         'success' => true,
//         'car_maker' => $car_maker,
//         'car_model' => $car_model,
//         'processes' => $processes,
//         'qr_settings' => $qr_settings
//     ];

//     echo json_encode($response);
//     exit;
// }

// if ($method == 'get_inspection_details') {
//     $line_no = $_GET['line_no'];

//     // Step 1: Fetch car maker, car model, and IRCS line
//     $query = "SELECT car_maker, car_model, ircs_line FROM m_ircs_line WHERE line_no = ?";
//     $stmt = $conn_pcad->prepare($query);
//     $stmt->execute([$line_no]);
//     $row_ircs = $stmt->fetch(PDO::FETCH_ASSOC);

//     if (!$row_ircs) {
//         echo json_encode([
//             'success' => false,
//             'error' => 'Line no. is not registered.'
//         ]);
//         exit;
//     }

//     $car_maker = $row_ircs['car_maker'];
//     $car_model = $row_ircs['car_model'];
//     $ircs_line = $row_ircs['ircs_line'];

//     // Step 2: Fetch distinct processes for the IRCS line
//     $query = "SELECT DISTINCT process FROM m_inspection_ip WHERE ircs_line = ?";
//     $stmt = $conn_pcad->prepare($query);
//     $stmt->execute([$ircs_line]);
//     $processes = $stmt->fetchAll(PDO::FETCH_COLUMN);

//     if (!$processes || count($processes) === 0) {
//         echo json_encode([
//             'success' => false,
//             'error' => 'No processes found for this IRCS line.'
//         ]);
//         exit;
//     }

//     // Step 3: Construct and return the response (QR settings removed)
//     $response = [
//         'success' => true,
//         'car_maker' => $car_maker,
//         'car_model' => $car_model,
//         'processes' => $processes
//     ];

//     echo json_encode($response);
//     exit;
// }

if ($method == 'get_inspection_details') {
    $line_no = $_GET['line_no'];

    // Step 1: Fetch from m_ircs_line using $conn_pcad
    $query_ircs = "SELECT car_maker, ircs_line FROM m_ircs_line WHERE line_no = ?";
    $stmt_ircs = $conn_pcad->prepare($query_ircs);
    $stmt_ircs->execute([$line_no]);
    $row_ircs = $stmt_ircs->fetch(PDO::FETCH_ASSOC);

    // Also fetch distinct car_model(s) and car_maker from m_line_no using $conn
    $query_models = "SELECT DISTINCT car_model FROM m_line_no WHERE line_no = ?";
    $stmt_models = $conn->prepare($query_models);
    $stmt_models->execute([$line_no]);
    $car_models = $stmt_models->fetchAll(PDO::FETCH_COLUMN);

    // Fetch car_maker (just get one since it's usually the same)
    $query_maker = "SELECT TOP 1 car_maker FROM m_line_no WHERE line_no = ?";
    $stmt_maker = $conn->prepare($query_maker);
    $stmt_maker->execute([$line_no]);
    $car_maker_row = $stmt_maker->fetch(PDO::FETCH_ASSOC);

    // Check if both failed
    if (!$row_ircs && !$car_models) {
        echo json_encode([
            'success' => false,
            'error' => 'Line no. is not registered.'
        ]);
        exit;
    }

    // Car maker from m_line_no or fallback to m_ircs_line
    $car_maker = $car_maker_row['car_maker'] ?? ($row_ircs['car_maker'] ?? '');

    // Determine output format: string or array
    if (count($car_models) === 1) {
        $car_model = $car_models[0]; // single car model
    } else {
        $car_model = $car_models; // multiple car models
    }

    $ircs_line = $row_ircs['ircs_line'] ?? '';

    // Step 2: Try to fetch distinct processes from m_inspection_ip
    $processes = [];

    // Step 1: Fetch from m_inspection_ip (PCAD)
    $processes_pcad = [];
    if ($ircs_line) {
        $query_process = "SELECT DISTINCT process FROM m_inspection_ip WHERE ircs_line = ?";
        $stmt_process = $conn_pcad->prepare($query_process);
        $stmt_process->execute([$ircs_line]);
        $processes_pcad = $stmt_process->fetchAll(PDO::FETCH_COLUMN);
    }

    // Step 2: Fetch from m_line_process (Main DB)
    $query_line_process = "SELECT DISTINCT process FROM m_line_process WHERE line_no = ?";
    $stmt_line_process = $conn->prepare($query_line_process);
    $stmt_line_process->execute([$line_no]);
    $processes_main = $stmt_line_process->fetchAll(PDO::FETCH_COLUMN);

    // Step 3: Combine and deduplicate, prioritizing longer names
    $combined_raw = array_merge($processes_pcad, $processes_main);
    $normalized_map = [];

    // Sort by length descending to prioritize longer names (e.g., Appearance_1 over Appearance)
    usort($combined_raw, function ($a, $b) {
        return strlen($b) - strlen($a);
    });

    foreach ($combined_raw as $proc) {
        $normalized = strtolower(trim($proc));
        $is_duplicate = false;

        foreach ($normalized_map as $existing => $original) {
            // If shorter one exists inside the longer one, skip it
            if (strpos($existing, $normalized) !== false || strpos($normalized, $existing) !== false) {
                $is_duplicate = true;
                break;
            }
        }

        if (!$is_duplicate) {
            $normalized_map[$normalized] = $proc; // store original casing
        }
    }

    $processes = array_values($normalized_map);

    // Step 4: Fallback if still empty
    if (count($processes) === 0) {
        $query_fallback = "SELECT DISTINCT final_process FROM m_final_process";
        $stmt_fallback = $conn_pcad->query($query_fallback);
        $processes = $stmt_fallback->fetchAll(PDO::FETCH_COLUMN);
    }

    if (!$processes || count($processes) === 0) {
        echo json_encode([
            'success' => false,
            'error' => 'No processes or fallback processes found.'
        ]);
        exit;
    }

    // Step 3: Return response
    echo json_encode([
        'success' => true,
        'car_maker' => $car_maker,
        'car_model' => $car_model, // Can be string or array
        'processes' => $processes
    ]);
    exit;
}
