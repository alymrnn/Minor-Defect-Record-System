<?php
include 'conn.php';

$method = $_POST['method'];

if ($method == "fetch_year_month_options") {
    $queryYears = "
        SELECT DISTINCT YEAR(date_detected) AS year_val
        FROM t_minor_defect_f
        WHERE date_detected IS NOT NULL
          AND YEAR(date_detected) >= 2000
        ORDER BY year_val DESC
    ";
    $stmtYears = $conn->prepare($queryYears);
    $stmtYears->execute();
    $years = $stmtYears->fetchAll(PDO::FETCH_COLUMN);

    $queryMonths = "
        SELECT DISTINCT MONTH(date_detected) AS month_val
        FROM t_minor_defect_f
        WHERE date_detected IS NOT NULL
          AND YEAR(date_detected) >= 2000
        ORDER BY month_val ASC
    ";
    $stmtMonths = $conn->prepare($queryMonths);
    $stmtMonths->execute();
    $months = $stmtMonths->fetchAll(PDO::FETCH_COLUMN);

    echo json_encode([
        "years" => $years,
        "months" => $months
    ]);
    exit;
}

if ($method == 'fetch_weekly_defect_category_count') {
    $query = "
        SELECT  
            defect_category,
            DATEPART(WEEK, date_detected) AS week_no,
            DATEADD(WEEK, DATEDIFF(WEEK, 0, date_detected), 0) AS week_start,
            DATEADD(DAY, 6, DATEADD(WEEK, DATEDIFF(WEEK, 0, date_detected), 0)) AS week_end,
            COUNT(*) AS defect_count
        FROM t_minor_defect_f
        WHERE date_detected >= '2025-08-01'
          AND date_detected < '2025-09-01'
        GROUP BY 
            defect_category,
            DATEPART(WEEK, date_detected),
            DATEADD(WEEK, DATEDIFF(WEEK, 0, date_detected), 0),
            DATEADD(DAY, 6, DATEADD(WEEK, DATEDIFF(WEEK, 0, date_detected), 0))
        ORDER BY 
            defect_category,
            week_start;
    ";

    try {
        $stmt = $conn->query($query);
        $data = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data[] = [
                "defect_category" => $row['defect_category'],
                "week_no"         => (int)$row['week_no'],
                "week_range"      => date("M d", strtotime($row['week_start'])) . " - " .
                    date("M d", strtotime($row['week_end'])),
                "defect_count"    => (int)$row['defect_count']
            ];
        }

        echo json_encode($data);
    } catch (PDOException $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }
    exit;
}

if ($method == 'fetch_weekly_defect_per_section') {
    $year  = $_POST['year'] ?? date("Y");
    $month = $_POST['month'] ?? date("n"); // numeric month

    // Compute first & last day of selected month
    $startDate = "$year-$month-01";
    $endDate   = date("Y-m-d", strtotime("+1 month", strtotime($startDate)));

    $query = "
        SELECT  
            ml.section,
            DATEPART(WEEK, t.date_detected) AS week_no,
            DATEADD(WEEK, DATEDIFF(WEEK, 0, t.date_detected), 0) AS week_start,  
            DATEADD(DAY, 6, DATEADD(WEEK, DATEDIFF(WEEK, 0, t.date_detected), 0)) AS week_end, 
            COUNT(*) AS defect_count
        FROM t_minor_defect_f t
        INNER JOIN m_line_no ml
            ON t.line_no = ml.line_no
        WHERE t.date_detected >= :startDate
          AND t.date_detected < :endDate
        GROUP BY 
            ml.section,
            DATEPART(WEEK, t.date_detected),
            DATEADD(WEEK, DATEDIFF(WEEK, 0, t.date_detected), 0),
            DATEADD(DAY, 6, DATEADD(WEEK, DATEDIFF(WEEK, 0, t.date_detected), 0))
        ORDER BY 
            ml.section,
            week_start;
    ";

    try {
        $stmt = $conn->prepare($query);
        $stmt->execute([
            ":startDate" => $startDate,
            ":endDate"   => $endDate
        ]);

        $data = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data[] = [
                "section"      => $row['section'],
                "week_no"      => (int)$row['week_no'],
                "week_range"   => date("M d", strtotime($row['week_start'])) . " - " . 
                                  date("M d", strtotime($row['week_end'])),
                "defect_count" => (int)$row['defect_count']
            ];
        }

        echo json_encode($data);
    } catch (PDOException $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }
    exit;
}
