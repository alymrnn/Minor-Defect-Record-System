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
    $year  = isset($_POST['year']) ? (int)$_POST['year'] : date("Y");
    $month = isset($_POST['month']) ? (int)$_POST['month'] : date("n");

    $startDate = date("Y-m-01", strtotime("$year-$month-01"));
    $endDate   = date("Y-m-d", strtotime("$startDate +1 month"));

    $query = "
        WITH WeeklyData AS (
            SELECT  
                defect_category,
                DATEPART(WEEK, date_detected) AS week_no,
                CAST(DATEADD(WEEK, DATEDIFF(WEEK, 0, date_detected), 0) AS DATE) AS week_start,
                CAST(DATEADD(DAY, 6, DATEADD(WEEK, DATEDIFF(WEEK, 0, date_detected), 0)) AS DATE) AS week_end
            FROM t_minor_defect_f
            WHERE date_detected >= CAST(:start_date AS DATE)
              AND date_detected <  CAST(:end_date   AS DATE)
        )
        SELECT 
            defect_category,
            week_no,
            week_start,
            week_end,
            COUNT(*) AS defect_count
        FROM WeeklyData
        GROUP BY defect_category, week_no, week_start, week_end
        ORDER BY defect_category, week_start;
    ";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(":start_date", $startDate, PDO::PARAM_STR);
    $stmt->bindParam(":end_date",   $endDate,   PDO::PARAM_STR);
    $stmt->execute();

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
    exit;
}

if ($method == 'fetch_weekly_top_lines_based_on_defect_category') {
    $defectCategory = $_POST['defect_category'] ?? '';
    $year  = isset($_POST['year']) ? (int)$_POST['year'] : date("Y");
    $month = isset($_POST['month']) ? (int)$_POST['month'] : date("n");

    $startDate = date("Y-m-01", strtotime("$year-$month-01"));
    $endDate   = date("Y-m-d", strtotime("$startDate +1 month"));

    $query = "
        WITH LineTotals AS (
            SELECT 
                t.line_no,
                COUNT(*) AS total_defects
            FROM t_minor_defect_f t
            WHERE t.defect_category = ?
              AND t.date_detected >= CAST(? AS DATE)
              AND t.date_detected < CAST(? AS DATE)
            GROUP BY t.line_no
        ),
        TopLines AS (
            SELECT TOP 10 line_no
            FROM LineTotals
            ORDER BY total_defects DESC
        )
        SELECT 
            t.line_no,
            DATEPART(WEEK, t.date_detected) AS week_no,
            CAST(DATEADD(WEEK, DATEDIFF(WEEK, 0, t.date_detected), 0) AS DATE) AS week_start,
            CAST(DATEADD(DAY, 6, DATEADD(WEEK, DATEDIFF(WEEK, 0, t.date_detected), 0)) AS DATE) AS week_end,
            COUNT(*) AS defect_count
        FROM t_minor_defect_f t
        INNER JOIN TopLines tl ON t.line_no = tl.line_no
        WHERE t.defect_category = ?
          AND t.date_detected >= CAST(? AS DATE)
          AND t.date_detected < CAST(? AS DATE)
        GROUP BY 
            t.line_no,
            DATEPART(WEEK, t.date_detected),
            DATEADD(WEEK, DATEDIFF(WEEK, 0, t.date_detected), 0),
            DATEADD(DAY, 6, DATEADD(WEEK, DATEDIFF(WEEK, 0, t.date_detected), 0))
        ORDER BY t.line_no, week_start;
    ";

    try {
        $stmt = $conn->prepare($query);

        $stmt->execute([
            $defectCategory,
            $startDate,
            $endDate,
            $defectCategory,
            $startDate,
            $endDate
        ]);

        $data = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data[] = [
                "line_no"      => $row['line_no'],
                "week_no"      => (int)$row['week_no'],
                "week_range"   => date("M d", strtotime($row['week_start'])) . " - " .
                    date("M d", strtotime($row['week_end'])),
                "defect_count" => (int)$row['defect_count']
            ];
        }

        echo json_encode($data);
    } catch (PDOException $e) {
        $err = $stmt ? $stmt->errorInfo() : null;
        echo json_encode([
            "error" => $e->getMessage(),
            "stmt_error_info" => $err
        ]);
    }
    exit;
}

if ($method == 'fetch_weekly_defect_per_section') {
    $year  = isset($_POST['year']) ? (int)$_POST['year'] : date("Y");
    $month = isset($_POST['month']) ? (int)$_POST['month'] : date("n");

    $startDate = date("Y-m-01", strtotime("$year-$month-01"));
    $endDate   = date("Y-m-d", strtotime("$startDate +1 month"));

    $query = "
        WITH WeeklyData AS (
            SELECT  
                ml.section,
                DATEPART(WEEK, t.date_detected) AS week_no,
                CAST(DATEADD(WEEK, DATEDIFF(WEEK, 0, t.date_detected), 0) AS DATE) AS week_start,
                CAST(DATEADD(DAY, 6, DATEADD(WEEK, DATEDIFF(WEEK, 0, t.date_detected), 0)) AS DATE) AS week_end
            FROM t_minor_defect_f t
            INNER JOIN m_line_no ml
                ON t.line_no = ml.line_no
            WHERE t.date_detected >= CAST(:startDate AS DATE)
              AND t.date_detected <  CAST(:endDate   AS DATE)
        )
        SELECT 
            section,
            week_no,
            week_start,
            week_end,
            COUNT(*) AS defect_count
        FROM WeeklyData
        GROUP BY section, week_no, week_start, week_end
        ORDER BY section, week_start;
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