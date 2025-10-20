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




// =================================================================================================================
// OVERALL MONITORING V2
if ($method == 'fetch_defect_category_list') {
    $query = "SELECT DISTINCT defect_code_value_dd 
              FROM m_defect_details 
              WHERE defect_code_value_dd IS NOT NULL 
              ORDER BY defect_code_value_dd ASC";

    $stmt = $conn->query($query);
    $data = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $category = $row['defect_code_value_dd'];

        // Apply short name replacements
        switch ($category) {
            case 'Insufficient taping (with dimension requirement)':
                $category = 'Insufficient taping (w/ dim req)';
                break;
        }

        $data[] = $category;
    }

    echo json_encode($data);
    exit;
}

if ($method == 'fetch_year_list') {
    $query = "SELECT DISTINCT YEAR(date_detected) AS year_detected
              FROM t_minor_defect_f
              WHERE date_detected IS NOT NULL
              ORDER BY year_detected DESC";

    $stmt = $conn->query($query);
    $data = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row['year_detected'];
    }

    echo json_encode($data);
    exit;
}

if ($method == 'fetch_month_list') {
    $query = "SELECT DISTINCT MONTH(date_detected) AS month_detected
              FROM t_minor_defect_f
              WHERE date_detected IS NOT NULL
              ORDER BY month_detected ASC";

    $stmt = $conn->query($query);
    $data = [];

    $monthNames = [
        1 => 'Jan',
        2 => 'Feb',
        3 => 'Mar',
        4 => 'Apr',
        5 => 'May',
        6 => 'Jun',
        7 => 'Jul',
        8 => 'Aug',
        9 => 'Sept',
        10 => 'Oct',
        11 => 'Nov',
        12 => 'Dec'
    ];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $monthNum = (int)$row['month_detected'];
        if (isset($monthNames[$monthNum])) {
            $data[] = [
                'num' => $monthNum,
                'name' => $monthNames[$monthNum]
            ];
        }
    }

    echo json_encode($data);
    exit;
}

if ($method == 'fetch_week_list') {
    $years = $_POST['years'] ?? [];
    $months = $_POST['months'] ?? [];

    if (!is_array($years)) $years = [$years];
    if (!is_array($months)) $months = [$months];

    if (empty($years) || empty($months)) {
        echo json_encode(['error' => 'Missing year or month']);
        exit;
    }

    $data = [];
    $monthNames = [
        1 => 'January',
        2 => 'February',
        3 => 'March',
        4 => 'April',
        5 => 'May',
        6 => 'June',
        7 => 'July',
        8 => 'August',
        9 => 'September',
        10 => 'October',
        11 => 'November',
        12 => 'December'
    ];

    foreach ($years as $year) {
        foreach ($months as $month) {
            $monthName = $monthNames[(int)$month] ?? '';
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

            $weeks = [];
            $startDay = 1;
            $weekNum = 1;

            while ($startDay <= $daysInMonth) {
                $dayOfWeek = date('N', strtotime("$year-$month-$startDay"));
                $daysUntilSunday = 7 - $dayOfWeek;
                $endDay = min($startDay + $daysUntilSunday, $daysInMonth);

                $weeks[] = sprintf(
                    'W%d (%s %d–%s %d)',
                    $weekNum,
                    substr($monthName, 0, 3),
                    $startDay,
                    substr($monthName, 0, 3),
                    $endDay
                );

                $startDay = $endDay + 1;
                $weekNum++;
            }

            $data[] = [
                'monthLabel' => "$monthName $year",
                'weeks' => $weeks
            ];
        }
    }

    echo json_encode($data);
    exit;
}

if ($method == 'fetch_section_list') {
    $query = "SELECT DISTINCT section FROM m_line_no WHERE section IS NOT NULL AND section <> '' ORDER BY section ASC";
    $stmt = $conn->query($query);

    $data = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row['section'];
    }

    echo json_encode($data);
    exit;
}

if ($method == 'fetch_line_list') {
    $sections = $_POST['section'] ?? [];

    if (!empty($sections)) {
        // Convert to array if sent as string
        if (!is_array($sections)) {
            $sections = [$sections];
        }

        // Prepare placeholders for multiple sections
        $placeholders = implode(',', array_fill(0, count($sections), '?'));

        $query = "SELECT DISTINCT line_no 
                  FROM m_line_no 
                  WHERE section IN ($placeholders)
                    AND line_no IS NOT NULL 
                    AND line_no <> '' 
                  ORDER BY line_no ASC";

        $stmt = $conn->prepare($query);
        $stmt->execute($sections);
    } else {
        $query = "SELECT DISTINCT line_no 
                  FROM m_line_no 
                  WHERE line_no IS NOT NULL 
                    AND line_no <> '' 
                  ORDER BY line_no ASC";
        $stmt = $conn->query($query);
    }

    $data = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row['line_no'];
    }

    echo json_encode($data);
    exit;
}

if ($method == 'fetch_process_list') {
    $defect_category = $_POST['defect_category'] ?? [];

    if (!is_array($defect_category)) {
        $defect_category = [$defect_category];
    }

    // Mapping of frontend label → real DB value
    $mapping = [
        'Insufficient taping (w/ dim req)' => 'Insufficient taping (with dimension requirement)'
        // add more mappings here if needed
    ];

    // Replace short values with real DB values
    $defect_category = array_map(function ($item) use ($mapping) {
        return $mapping[$item] ?? $item;
    }, $defect_category);

    if (empty($defect_category)) {
        $query = "SELECT DISTINCT process 
                  FROM t_minor_defect_f 
                  WHERE process IS NOT NULL AND process <> '' 
                  ORDER BY process ASC";
        $stmt = $conn->query($query);
    } else {
        $placeholders = implode(',', array_fill(0, count($defect_category), '?'));
        $query = "SELECT DISTINCT process 
                  FROM t_minor_defect_f 
                  WHERE defect_category IN ($placeholders)
                  AND process IS NOT NULL AND process <> ''
                  ORDER BY process ASC";
        $stmt = $conn->prepare($query);
        $stmt->execute($defect_category);
    }

    $data = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row['process'];
    }

    echo json_encode($data);
    exit;
}

if ($method == 'fetch_line_category_list') {
    $query = "SELECT DISTINCT line_category FROM t_minor_defect_f WHERE line_category IS NOT NULL AND line_category <> '' ORDER BY line_category ASC";
    $stmt = $conn->query($query);

    $data = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row['line_category'];
    }

    echo json_encode($data);
    exit;
}

if ($method == 'fetch_overall_month_week_chart') {
    $years = $_POST['years'] ?? [];
    $months = $_POST['months'] ?? [];
    $weeks = $_POST['weeks'] ?? [];
    $defect_category = $_POST['defect_category'] ?? [];

    if (!is_array($years)) $years = [$years];
    if (!is_array($months)) $months = [$months];
    if (!is_array($weeks)) $weeks = [$weeks];
    if (!is_array($defect_category)) $defect_category = [$defect_category];

    $response = [];

    foreach ($years as $year) {
        foreach ($months as $month) {
            $year = (int)$year;
            $month = (int)$month;

            $monthStart = sprintf('%04d-%02d-01', $year, $month);
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $monthEnd = sprintf('%04d-%02d-%02d', $year, $month, $daysInMonth);

            $weeksFilter = [];

            // Use selected weeks if any
            if (!empty($weeks)) {
                foreach ($weeks as $w) {
                    if (preg_match('/(\w{3})\s?(\d+)[–-](\d+)/', $w, $matches)) {
                        $startDay = $matches[2];
                        $endDay = $matches[3];
                        $weeksFilter[] = [
                            'start' => sprintf('%04d-%02d-%02d', $year, $month, $startDay),
                            'end'   => sprintf('%04d-%02d-%02d', $year, $month, $endDay)
                        ];
                    }
                }
            } else {
                // Otherwise, compute real weekly ranges (Mon–Sun)
                $current = new DateTime($monthStart);
                $monthEndDate = new DateTime($monthEnd);

                while ($current <= $monthEndDate) {
                    $start = clone $current;
                    $end = clone $start;
                    $end->modify('next Sunday');

                    if ($end > $monthEndDate) {
                        $end = clone $monthEndDate;
                    }

                    $weeksFilter[] = [
                        'start' => $start->format('Y-m-d'),
                        'end'   => $end->format('Y-m-d')
                    ];

                    $current = (clone $end)->modify('+1 day');
                }
            }

            // Fetch record count per week (with defect_category filter)
            $monthData = [
                'year' => $year,
                'month' => date('F', strtotime($monthStart)),
                'weeks' => []
            ];

            foreach ($weeksFilter as $index => $wf) {
                $baseQuery = "
                    SELECT COUNT(*) AS total_records
                    FROM t_minor_defect_f
                    WHERE date_detected BETWEEN ? AND ?
                ";

                // Apply defect_category filter if selected
                $params = [$wf['start'], $wf['end']];
                if (!empty($defect_category)) {
                    $placeholders = implode(',', array_fill(0, count($defect_category), '?'));
                    $baseQuery .= " AND defect_category IN ($placeholders)";
                    $params = array_merge($params, $defect_category);
                }

                $stmt = $conn->prepare($baseQuery);
                $stmt->execute($params);
                $count = (int)$stmt->fetchColumn();

                $monthData['weeks'][] = [
                    'week_label' => 'W' . ($index + 1),
                    'week_range' => date('M j', strtotime($wf['start'])) . '–' . date('j', strtotime($wf['end'])),
                    'total_records' => $count
                ];
            }

            $response[] = $monthData;
        }
    }

    echo json_encode($response);
    exit;
}

if ($method == 'fetch_harness_type_breakdown_chart') {
    $defect_category = $_POST['defect_category'] ?? [];
    $years = $_POST['years'] ?? [];
    $months = $_POST['months'] ?? [];

    // Ensure they are arrays
    if (!is_array($defect_category)) $defect_category = [$defect_category];
    if (!is_array($years)) $years = [$years];
    if (!is_array($months)) $months = [$months];

    $conditions = [];
    $params = [];

    // Filter by defect category (if selected)
    if (!empty($defect_category)) {
        $placeholders = implode(',', array_fill(0, count($defect_category), '?'));
        $conditions[] = "defect_category IN ($placeholders)";
        $params = array_merge($params, $defect_category);
    }

    // Filter by year
    if (!empty($years)) {
        $yearPlaceholders = implode(',', array_fill(0, count($years), '?'));
        $conditions[] = "YEAR(date_added) IN ($yearPlaceholders)";
        $params = array_merge($params, $years);
    }

    // Filter by month
    if (!empty($months)) {
        $monthPlaceholders = implode(',', array_fill(0, count($months), '?'));
        $conditions[] = "MONTH(date_added) IN ($monthPlaceholders)";
        $params = array_merge($params, $months);
    }

    // Combine all conditions
    $where = '';
    if (!empty($conditions)) {
        $where = 'WHERE ' . implode(' AND ', $conditions);
    }

    $query = "SELECT harness_type, COUNT(*) AS total
              FROM t_minor_defect_f
              $where
              GROUP BY harness_type
              ORDER BY total DESC";

    $stmt = $conn->prepare($query);
    $stmt->execute($params);

    $data = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[] = [
            'name' => $row['harness_type'] ?: 'Unknown',
            'y' => (int)$row['total']
        ];
    }

    echo json_encode($data);
    exit;
}

if ($method == 'fetch_overall_record_per_section_chart') {
    $years = $_POST['years'] ?? [];
    $months = $_POST['months'] ?? [];
    $defect_category = $_POST['defect_category'] ?? [];

    if (!is_array($years)) $years = [$years];
    if (!is_array($months)) $months = [$months];
    if (!is_array($defect_category)) $defect_category = [$defect_category];

    $response = [];

    // --- Get all sections and map line numbers
    $stmt = $conn->query("SELECT section, line_no FROM m_line_no WHERE section IS NOT NULL AND section <> ''");
    $sectionLinesMap = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $sectionLinesMap[$row['section']][] = $row['line_no'];
    }

    foreach ($years as $year) {
        foreach ($months as $month) {
            $year = (int)$year;
            $month = (int)$month;

            $monthStart = sprintf('%04d-%02d-01', $year, $month);
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $monthEnd = sprintf('%04d-%02d-%02d', $year, $month, $daysInMonth);

            // --- Build week ranges
            $weeksFilter = [];
            $current = new DateTime($monthStart);
            $monthEndDate = new DateTime($monthEnd);
            $weekCount = 1;
            while ($current <= $monthEndDate) {
                $start = clone $current;
                $end = clone $start;
                $end->modify('next Sunday');
                if ($end > $monthEndDate) $end = clone $monthEndDate;

                $weeksFilter[] = [
                    'label' => 'W' . $weekCount,
                    'start' => $start->format('Y-m-d'),
                    'end'   => $end->format('Y-m-d')
                ];

                $current = (clone $end)->modify('+1 day');
                $weekCount++;
            }

            $weeksData = array_map(fn($w) => [
                'week_label' => $w['label'],
                'week_range' => date('M j', strtotime($w['start'])) . '–' . date('j', strtotime($w['end']))
            ], $weeksFilter);

            // --- Prepare a single query to fetch all defects in the month
            $query = "SELECT line_no, date_detected, defect_category 
                      FROM t_minor_defect_f 
                      WHERE date_detected BETWEEN ? AND ?";
            $params = [$monthStart, $monthEnd];

            if (!empty($defect_category)) {
                $catPlaceholders = implode(',', array_fill(0, count($defect_category), '?'));
                $query .= " AND defect_category IN ($catPlaceholders)";
                $params = array_merge($params, $defect_category);
            }

            $stmt2 = $conn->prepare($query);
            $stmt2->execute($params);
            $allDefects = $stmt2->fetchAll(PDO::FETCH_ASSOC);

            // --- Initialize section-week counts
            $sectionsData = [];
            foreach ($sectionLinesMap as $section => $lineNos) {
                $weeklyRecords = array_map(fn($w) => ['week_label' => $w['label'], 'total_records' => 0], $weeksFilter);

                foreach ($allDefects as $defect) {
                    if (!in_array($defect['line_no'], $lineNos)) continue;

                    foreach ($weeksFilter as $idx => $wf) {
                        if ($defect['date_detected'] >= $wf['start'] && $defect['date_detected'] <= $wf['end']) {
                            $weeklyRecords[$idx]['total_records']++;
                            break;
                        }
                    }
                }

                $sectionsData[] = [
                    'section' => $section,
                    'weekly_records' => $weeklyRecords
                ];
            }

            $response[] = [
                'month' => strtoupper(date('M', mktime(0, 0, 0, $month, 1))),
                'weeks' => $weeksData,
                'sections' => $sectionsData
            ];
        }
    }

    echo json_encode($response);
    exit;
}

if ($method == 'fetch_overall_top_ten_lines_chart') {

    $years = $_POST['years'] ?? [];
    $months = $_POST['months'] ?? [];
    $defect_category = $_POST['defect_category'] ?? [];

    if (!is_array($years)) $years = [$years];
    if (!is_array($months)) $months = [$months];
    if (!is_array($defect_category)) $defect_category = [$defect_category];

    $response = [];

    foreach ($years as $year) {
        foreach ($months as $month) {
            $year = (int)$year;
            $month = (int)$month;

            $monthStart = sprintf('%04d-%02d-01', $year, $month);
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $monthEnd = sprintf('%04d-%02d-%02d', $year, $month, $daysInMonth);

            // --- Build dynamic week ranges for the month
            $weeksFilter = [];
            $current = new DateTime($monthStart);
            $monthEndDate = new DateTime($monthEnd);
            $weekCount = 1;

            while ($current <= $monthEndDate) {
                $start = clone $current;
                $end = clone $start;
                $end->modify('next Sunday');
                if ($end > $monthEndDate) $end = clone $monthEndDate;

                $weeksFilter[] = [
                    'label' => 'W' . $weekCount,
                    'start' => $start->format('Y-m-d'),
                    'end'   => $end->format('Y-m-d')
                ];

                $current = (clone $end)->modify('+1 day');
                $weekCount++;
            }

            // Format week labels for frontend
            $weeksData = array_map(fn($w) => [
                'week_label' => $w['label'],
                'week_range' => date('M j', strtotime($w['start'])) . '–' . date('j', strtotime($w['end']))
            ], $weeksFilter);

            // --- Build SQL query dynamically using CASE per week
            $weekCases = [];
            foreach ($weeksFilter as $idx => $w) {
                $weekCases[] = "SUM(CASE WHEN date_detected BETWEEN ? AND ? THEN 1 ELSE 0 END) AS week" . ($idx + 1);
            }
            $weekCasesSql = implode(",\n", $weekCases);

            $sql = "
                WITH FilteredDefects AS (
                    SELECT line_no, date_detected
                    FROM t_minor_defect_f
                    WHERE date_detected BETWEEN ? AND ?
                    " . (!empty($defect_category) 
                        ? "AND defect_category IN (" . implode(',', array_fill(0, count($defect_category), '?')) . ")"
                        : "") . "
                ),
                LineTotals AS (
                    SELECT line_no, COUNT(*) AS total_defects
                    FROM FilteredDefects
                    GROUP BY line_no
                ),
                TopLines AS (
                    SELECT TOP 10 line_no
                    FROM LineTotals
                    ORDER BY total_defects DESC
                ),
                WeeklyBreakdown AS (
                    SELECT 
                        f.line_no,
                        $weekCasesSql
                    FROM FilteredDefects f
                    INNER JOIN TopLines t ON f.line_no = t.line_no
                    GROUP BY f.line_no
                )
                SELECT *
                FROM WeeklyBreakdown
                ORDER BY line_no;
            ";

            // --- Build parameters for SQL
            $params = [$monthStart, $monthEnd];
            if (!empty($defect_category)) $params = array_merge($params, $defect_category);

            // Add start/end for each week CASE
            foreach ($weeksFilter as $w) {
                $params[] = $w['start'];
                $params[] = $w['end'];
            }

            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // --- Format results for frontend
            $linesData = [];
            foreach ($results as $row) {
                $weeklyRecords = [];
                foreach ($weeksFilter as $idx => $week) {
                    $weekKey = 'week' . ($idx + 1);
                    $weeklyRecords[] = [
                        'week_label' => $week['label'],
                        'total_records' => isset($row[$weekKey]) ? (int)$row[$weekKey] : 0
                    ];
                }
                $linesData[] = [
                    'line_no' => $row['line_no'],
                    'weekly_records' => $weeklyRecords
                ];
            }

            $response[] = [
                'month' => strtoupper(date('M', mktime(0, 0, 0, $month, 1))),
                'weeks' => $weeksData,
                'lines' => $linesData
            ];
        }
    }

    echo json_encode($response);
    exit;
}


