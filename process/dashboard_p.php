<?php
include 'conn.php';

$method = $_POST['method'];

if ($method == 'fetch_line_no') {
    try {
        $query = "SELECT DISTINCT line_no FROM m_line_no ORDER BY line_no ASC";
        $stmt = $conn->query($query);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($data);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

if ($method == 'fetch_total_defect_record') {
    $line_no   = $_POST['line_no'] ?? null;
    $date_from = $_POST['date_from'] ?? null;
    $date_to   = $_POST['date_to'] ?? null;

    try {
        // Current period total
        $query = "SELECT COUNT(*) AS total FROM t_minor_defect_f WHERE 1=1";

        if (!empty($date_from) && !empty($date_to)) {
            $query .= " AND date_detected BETWEEN :start_date AND :end_date";
        }
        if (!empty($line_no)) {
            $query .= " AND line_no = :line_no";
        }

        $stmt = $conn->prepare($query);

        $params = [];
        if (!empty($date_from) && !empty($date_to)) {
            $params[':start_date'] = $date_from;
            $params[':end_date']   = $date_to;
        }
        if (!empty($line_no)) {
            $params[':line_no'] = $line_no;
        }

        $stmt->execute($params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $current_total = $row['total'] ?? 0;

        // Previous month total
        $prev_total = 0;
        if (!empty($date_from) && !empty($date_to)) {
            $prev_start = date("Y-m-01", strtotime("$date_from -1 month"));
            $prev_end   = date("Y-m-t", strtotime("$date_from -1 month"));

            $query_prev = "SELECT COUNT(*) AS total FROM t_minor_defect_f 
                           WHERE date_detected BETWEEN :prev_start AND :prev_end";
            if (!empty($line_no)) {
                $query_prev .= " AND line_no = :line_no";
            }

            $stmt_prev = $conn->prepare($query_prev);
            $params_prev = [
                ':prev_start' => $prev_start,
                ':prev_end'   => $prev_end
            ];
            if (!empty($line_no)) {
                $params_prev[':line_no'] = $line_no;
            }

            $stmt_prev->execute($params_prev);
            $row_prev = $stmt_prev->fetch(PDO::FETCH_ASSOC);
            $prev_total = $row_prev['total'] ?? 0;
        }

        // Determine trend (increase/decrease/same)
        $comparison = "same";
        if ($current_total > $prev_total) {
            $comparison = "increase";
        } elseif ($current_total < $prev_total) {
            $comparison = "decrease";
        }

        echo json_encode([
            "current_total" => $current_total,
            "previous_total" => $prev_total,
            "comparison" => $comparison
        ]);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

if ($method == 'fetch_daily_trend') {
    $line_no   = $_POST['line_no'] ?? null;
    $date_from = $_POST['date_from'] ?? null;
    $date_to   = $_POST['date_to'] ?? null;

    try {
        $query = "SELECT 
                    CAST(date_detected AS DATE) AS defect_date,
                    COUNT(*) AS total
                  FROM t_minor_defect_f
                  WHERE date_detected BETWEEN :start_date AND :end_date";

        if (!empty($line_no)) {
            $query .= " AND line_no = :line_no";
        }

        $query .= " GROUP BY CAST(date_detected AS DATE)
                    ORDER BY defect_date ASC";

        $stmt = $conn->prepare($query);

        $params = [
            ':start_date' => $date_from,
            ':end_date'   => $date_to
        ];
        if (!empty($line_no)) {
            $params[':line_no'] = $line_no;
        }

        $stmt->execute($params);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

if ($method == 'fetch_top_lot_no') {
    $line_no   = $_POST['line_no'] ?? null;
    $date_from = $_POST['date_from'] ?? null;
    $date_to   = $_POST['date_to'] ?? null;

    try {
        $query = "SELECT 
                    lot_no,
                    COUNT(*) AS total
                  FROM t_minor_defect_f
                  WHERE date_detected BETWEEN :start_date AND :end_date";

        if (!empty($line_no)) {
            $query .= " AND line_no = :line_no";
        }

        $query .= " GROUP BY lot_no
                    ORDER BY total DESC
                    OFFSET 0 ROWS FETCH NEXT 10 ROWS ONLY";

        $stmt = $conn->prepare($query);

        $params = [
            ':start_date' => $date_from,
            ':end_date'   => $date_to
        ];
        if (!empty($line_no)) {
            $params[':line_no'] = $line_no;
        }

        $stmt->execute($params);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

if ($method == 'fetch_top_sequence_no') {
    $line_no   = $_POST['line_no'] ?? null;
    $date_from = $_POST['date_from'] ?? null;
    $date_to   = $_POST['date_to'] ?? null;

    try {
        $query = "SELECT 
                    sequence_no,
                    COUNT(*) AS total
                FROM t_minor_defect_f
                WHERE date_detected BETWEEN :start_date AND :end_date
                    AND LTRIM(RTRIM(sequence_no)) NOT IN ('0', 'NA')";

        if (!empty($line_no)) {
            $query .= " AND line_no = :line_no";
        }

        $query .= " GROUP BY sequence_no
                ORDER BY total DESC
                OFFSET 0 ROWS FETCH NEXT 10 ROWS ONLY";

        $stmt = $conn->prepare($query);

        $params = [
            ':start_date' => $date_from,
            ':end_date'   => $date_to
        ];
        if (!empty($line_no)) {
            $params[':line_no'] = $line_no;
        }

        $stmt->execute($params);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

if ($method == 'fetch_top_connector_no') {
    $line_no   = $_POST['line_no'] ?? null;
    $date_from = $_POST['date_from'] ?? null;
    $date_to   = $_POST['date_to'] ?? null;

    try {
        $query = "SELECT 
                    connector_no,
                    COUNT(*) AS total
                FROM t_minor_defect_f
                WHERE date_detected BETWEEN :start_date AND :end_date
                    AND LTRIM(RTRIM(connector_no)) NOT IN ('0', 'NA')";

        if (!empty($line_no)) {
            $query .= " AND line_no = :line_no";
        }

        $query .= " GROUP BY connector_no
                ORDER BY total DESC
                OFFSET 0 ROWS FETCH NEXT 10 ROWS ONLY";

        $stmt = $conn->prepare($query);

        $params = [
            ':start_date' => $date_from,
            ':end_date'   => $date_to
        ];
        if (!empty($line_no)) {
            $params[':line_no'] = $line_no;
        }

        $stmt->execute($params);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}