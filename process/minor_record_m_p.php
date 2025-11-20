<?php
include 'conn.php';

$method = $_POST['method'];

if ($method == 'load_minor_defect_list') {
    $limit  = isset($_POST['limit']) ? (int)$_POST['limit'] : 300; // default 300 rows
    $page   = isset($_POST['page']) ? (int)$_POST['page'] : 1;
    $offset = ($page - 1) * $limit;

    // search filters
    $dateFrom = $_POST['m_search_date_from'] ?? '';
    $dateTo   = $_POST['m_search_date_to'] ?? '';
    $lineNo   = $_POST['m_search_line_no'] ?? '';
    $lotNo    = $_POST['m_search_lot_no'] ?? '';
    $process  = $_POST['m_search_process'] ?? '';

    // build WHERE conditions
    $conditions = [];
    $params = [];

    if (!empty($dateFrom) && !empty($dateTo)) {
        $conditions[] = "CAST(date_detected AS DATE) BETWEEN :dateFrom AND :dateTo";
        $params[':dateFrom'] = $dateFrom;
        $params[':dateTo']   = $dateTo;
    } elseif (!empty($dateFrom)) {
        $conditions[] = "CAST(date_detected AS DATE) >= :dateFrom";
        $params[':dateFrom'] = $dateFrom;
    } elseif (!empty($dateTo)) {
        $conditions[] = "CAST(date_detected AS DATE) <= :dateTo";
        $params[':dateTo'] = $dateTo;
    }

    if (!empty($lineNo)) {
        $conditions[] = "line_no = :lineNo";
        $params[':lineNo'] = $lineNo;
    }

     if (!empty($lotNo)) {
        $conditions[] = "lot_no = :lotNo";
        $params[':lotNo'] = $lotNo;
    }

    if (!empty($process)) {
        $conditions[] = "process = :process";
        $params[':process'] = $process;
    }

    $whereSql = $conditions ? "WHERE " . implode(" AND ", $conditions) : "";

    // count total records with filters
    $countQuery = "SELECT COUNT(*) as total FROM t_minor_defect_f $whereSql";
    $countStmt = $conn->prepare($countQuery);
    foreach ($params as $key => $val) {
        $countStmt->bindValue($key, $val, PDO::PARAM_STR);
    }
    $countStmt->execute();
    $totalRecords = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($totalRecords / $limit);

    // fetch records with filters
    $query = "SELECT * 
              FROM t_minor_defect_f
              $whereSql
              ORDER BY date_added DESC 
              OFFSET :offset ROWS 
              FETCH NEXT :limit ROWS ONLY";

    $stmt = $conn->prepare($query);
    foreach ($params as $key => $val) {
        $stmt->bindValue($key, $val, PDO::PARAM_STR);
    }
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $count = $offset;
    $output = '';

    if ($rows) {
        foreach ($rows as $row) {
            $count++;
            $output .= "<tr class='text-center text-xs'>
                <td style='vertical-align: middle;'>{$count}</td>
                <td style='vertical-align: middle;'>
                    <button type='button' class='btn btn-xs btn-outline-info'
                        data-toggle='modal' 
                        data-target='#edit_defect_record'
                        onclick=\"edit_minor_defect('" . $row['defect_id'] . "')\">
                        <i class='fas fa-edit'></i> Edit
                    </button>
                </td>
                <td style='vertical-align: middle;'>{$row['date_detected']}</td>
                <td style='vertical-align: middle;'>{$row['car_maker']}</td>
                <td style='vertical-align: middle;'>{$row['car_model']}</td>
                <td style='vertical-align: middle;'>{$row['line_no']}</td>
                <td style='vertical-align: middle;'>{$row['harness_type']}</td>
                <td style='vertical-align: middle;'>{$row['line_category']}</td>
                <td style='vertical-align: middle;'>{$row['process']}</td>
                <td style='vertical-align: middle;'>{$row['group_d']}</td>
                <td style='vertical-align: middle;'>{$row['shift']}</td>
                <td style='vertical-align: middle;'>{$row['product_no']}</td>
                <td style='vertical-align: middle;'>{$row['lot_no']}</td>
                <td style='vertical-align: middle;'>{$row['serial_no']}</td>
                <td style='vertical-align: middle;'>{$row['defect_category_code']}</td>
                <td style='vertical-align: middle;'>{$row['defect_category']}</td>
                <td style='vertical-align: middle;'>{$row['defect_details_code']}</td>
                <td style='vertical-align: middle;'>{$row['defect_details']}</td>
                <td style='vertical-align: middle;'>{$row['occurrence_shift']}</td>
                <td style='vertical-align: middle;'>{$row['occurrence_board_no']}</td>
                <td style='vertical-align: middle;'>{$row['occurrence_station_no']}</td>
                <td style='vertical-align: middle;'>{$row['treatment_content_defect']}</td>
                <td style='vertical-align: middle;'>{$row['sequence_no']}</td>
                <td style='vertical-align: middle;'>{$row['connector_no']}</td>
                <td style='vertical-align: middle;'>{$row['total_time']}</td>
                <td style='vertical-align: middle;'>{$row['repaired_by']}</td>
                <td style='vertical-align: middle;'>{$row['verified_by']}</td>
                <td style='vertical-align: middle;'>{$row['record_added_by']}</td>
             </tr>";
        }
    } else {
        $output .= "<tr><td colspan='22' class='text-center text-danger'>No records found</td></tr>";
    }

    // pagination wrapper
    $pagination  = "<div class='d-flex justify-content-between align-items-center w-100'>";
    $pagination .= "<div class='text-sm text-muted'>Total Records: {$totalRecords}</div>";
    $pagination .= "<div class='d-flex align-items-center gap-2'>";

    // Prev button
    if ($page > 1) {
        $pagination .= "<button type='button' id='btnPrevPage' class='btn btn-default btn-sm' onclick='load_minor_defect_list(" . ($page - 1) . ")'>
                      <i class='fas fa-chevron-left'></i> Prev
                    </button>";
    } else {
        $pagination .= "<button type='button' class='btn btn-default btn-sm' disabled>
                      <i class='fas fa-chevron-left'></i> Prev
                    </button>";
    }

    // Page input with datalist
    $pagination .= "<input type='text' list='pagination' class='form-control form-control-sm mx-2' 
                    id='inventory_pagination' 
                    style='width: 70px; text-align: center;' 
                    value='{$page}' 
                    placeholder='#'>
                <datalist id='pagination'>";
    for ($i = 1; $i <= $totalPages; $i++) {
        $pagination .= "<option value='{$i}'>";
    }
    $pagination .= "</datalist>";

    // Next button
    if ($page < $totalPages) {
        $pagination .= "<button type='button' id='btnNextPage' class='btn btn-default btn-sm' onclick='load_minor_defect_list(" . ($page + 1) . ")'>
                      Next <i class='fas fa-chevron-right'></i>
                    </button>";
    } else {
        $pagination .= "<button type='button' class='btn btn-default btn-sm' disabled>
                      Next <i class='fas fa-chevron-right'></i>
                    </button>";
    }

    $pagination .= "</div></div>";

    echo json_encode([
        "table" => $output,
        "pagination" => $pagination
    ]);
}

if ($method == 'get_minor_defect_details') {
    $defect_id = trim($_POST['defect_id'] ?? '');

    $query = "SELECT * 
              FROM t_minor_defect_f 
              WHERE LTRIM(RTRIM(defect_id)) = LTRIM(RTRIM(:defect_id))";

    $stmt = $conn->prepare($query);
    $stmt->bindValue(':defect_id', $defect_id, PDO::PARAM_STR);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => $data ? 'success' : 'error',
        "message" => $data ? '' : 'No record found for defect_id [' . $defect_id . ']',
        "data" => $data ?: null
    ]);
}

if ($method == 'update_minor_defect_record') {
    $defect_id                = trim($_POST['defect_id'] ?? '');
    $date_detected            = trim($_POST['date_detected'] ?? '');
    $car_maker                = trim($_POST['car_maker'] ?? '');
    $car_model                = trim($_POST['car_model'] ?? '');
    $line_no                  = trim($_POST['line_no'] ?? '');
    $harness_type             = trim($_POST['harness_type'] ?? '');
    $line_category            = trim($_POST['line_category'] ?? '');
    $process                  = trim($_POST['process'] ?? '');
    $group_d                  = trim($_POST['group_d'] ?? '');
    $shift                    = trim($_POST['shift'] ?? '');
    $product_no               = trim($_POST['product_no'] ?? '');
    $lot_no                   = trim($_POST['lot_no'] ?? '');
    $serial_no                = trim($_POST['serial_no'] ?? '');
    $defect_category_code     = trim($_POST['defect_category_code'] ?? '');
    $defect_category          = trim($_POST['defect_category'] ?? '');
    $defect_details_code      = trim($_POST['defect_details_code'] ?? '');
    $defect_details           = trim($_POST['defect_details'] ?? '');
    $treatment_content_defect = trim($_POST['treatment_content_defect'] ?? '');
    $occurrence_shift         = trim($_POST['occurrence_shift'] ?? '');
    $occurrence_board_no      = trim($_POST['occurrence_board_no'] ?? '');
    $occurrence_station_no    = trim($_POST['occurrence_station_no'] ?? '');
    $sequence_no              = trim($_POST['sequence_no'] ?? '');
    $connector_no             = trim($_POST['connector_no'] ?? '');
    $total_time               = trim($_POST['total_time'] ?? '');
    $repaired_by              = trim($_POST['repaired_by'] ?? '');
    $verified_by              = trim($_POST['verified_by'] ?? '');

    // ⚠ Do NOT overwrite record_added_by if not provided
    $record_added_by = isset($_POST['record_added_by']) && $_POST['record_added_by'] !== ''
        ? trim($_POST['record_added_by'])
        : null;

    $query = "UPDATE t_minor_defect_f SET
                date_detected = :date_detected,
                car_maker = :car_maker,
                car_model = :car_model,
                line_no = :line_no,
                harness_type = :harness_type,
                line_category = :line_category,
                process = :process,
                group_d = :group_d,
                shift = :shift,
                product_no = :product_no,
                lot_no = :lot_no,
                serial_no = :serial_no,
                defect_category_code = :defect_category_code,
                defect_category = :defect_category,
                defect_details_code = :defect_details_code,
                defect_details = :defect_details,
                treatment_content_defect = :treatment_content_defect,
                occurrence_shift = :occurrence_shift,
                occurrence_board_no = :occurrence_board_no,
                occurrence_station_no = :occurrence_station_no,
                sequence_no = :sequence_no,
                connector_no = :connector_no,
                total_time = :total_time,
                repaired_by = :repaired_by,
                verified_by = :verified_by" .
        ($record_added_by !== null ? ", record_added_by = :record_added_by" : "") . "
              WHERE LTRIM(RTRIM(defect_id)) = LTRIM(RTRIM(:defect_id))";

    $stmt = $conn->prepare($query);

    // Bind common fields
    $stmt->bindValue(':date_detected', $date_detected);
    $stmt->bindValue(':car_maker', $car_maker);
    $stmt->bindValue(':car_model', $car_model);
    $stmt->bindValue(':line_no', $line_no);
    $stmt->bindValue(':harness_type', $harness_type);
    $stmt->bindValue(':line_category', $line_category);
    $stmt->bindValue(':process', $process);
    $stmt->bindValue(':group_d', $group_d);
    $stmt->bindValue(':shift', $shift);
    $stmt->bindValue(':product_no', $product_no);
    $stmt->bindValue(':lot_no', $lot_no);
    $stmt->bindValue(':serial_no', $serial_no);
    $stmt->bindValue(':defect_category_code', $defect_category_code);
    $stmt->bindValue(':defect_category', $defect_category);
    $stmt->bindValue(':defect_details_code', $defect_details_code);
    $stmt->bindValue(':defect_details', $defect_details);
    $stmt->bindValue(':treatment_content_defect', $treatment_content_defect);
    $stmt->bindValue(':occurrence_shift', $occurrence_shift);
    $stmt->bindValue(':occurrence_board_no', $occurrence_board_no);
    $stmt->bindValue(':occurrence_station_no', $occurrence_station_no);
    $stmt->bindValue(':sequence_no', $sequence_no);
    $stmt->bindValue(':connector_no', $connector_no);
    $stmt->bindValue(':total_time', $total_time);
    $stmt->bindValue(':repaired_by', $repaired_by);
    $stmt->bindValue(':verified_by', $verified_by);
    $stmt->bindValue(':defect_id', $defect_id);

    // Bind only if record_added_by is provided
    if ($record_added_by !== null) {
        $stmt->bindValue(':record_added_by', $record_added_by);
    }

    $updated = $stmt->execute();

    echo json_encode([
        "status" => $updated ? "success" : "error",
        "message" => $updated ? "Record updated successfully." : "Failed to update record."
    ]);
}

$conn = null;
