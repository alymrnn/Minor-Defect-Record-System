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
    $sections = $_POST['sections'] ?? [];

    if (!is_array($years)) $years = [$years];
    if (!is_array($months)) $months = [$months];
    if (!is_array($weeks)) $weeks = [$weeks];
    if (!is_array($sections)) $sections = [$sections];

    $response = [];

    foreach ($years as $year) {
        foreach ($months as $month) {
            $year = (int)$year;
            $month = (int)$month;

            $monthStart = sprintf('%04d-%02d-01', $year, $month);
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $monthEnd = sprintf('%04d-%02d-%02d', $year, $month, $daysInMonth);

            $weeksFilter = [];

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
                $current = new DateTime($monthStart);
                $monthEndDate = new DateTime($monthEnd);

                while ($current <= $monthEndDate) {
                    $start = clone $current;
                    $end = clone $start;
                    $end->modify('next Sunday');
                    if ($end > $monthEndDate) $end = clone $monthEndDate;

                    $weeksFilter[] = [
                        'start' => $start->format('Y-m-d'),
                        'end'   => $end->format('Y-m-d')
                    ];

                    $current = (clone $end)->modify('+1 day');
                }
            }

            $monthData = ['year' => $year, 'month' => date('F', strtotime($monthStart)), 'weeks' => []];

            foreach ($weeksFilter as $index => $wf) {

                // Base query with join to m_line_process
                $query = "
                    SELECT COUNT(*) AS total_records
                    FROM t_minor_defect_f f
                    LEFT JOIN m_line_no l ON f.line_no = l.line_no
                    WHERE f.date_detected BETWEEN ? AND ?
                ";

                $params = [$wf['start'], $wf['end']];

                // Apply section filter if sections are selected
                if (!empty($sections)) {
                    // Create placeholders for IN clause
                    $placeholders = implode(',', array_fill(0, count($sections), '?'));
                    $query .= " AND l.section IN ($placeholders)";
                    $params = array_merge($params, $sections);
                }

                $stmt = $conn->prepare($query);
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
    $years = $_POST['years'] ?? [];
    $months = $_POST['months'] ?? [];
    $defect_category = $_POST['defect_category'] ?? [];
    $sections = $_POST['sections'] ?? [];

    if (!is_array($years)) $years = [$years];
    if (!is_array($months)) $months = [$months];
    if (!is_array($defect_category)) $defect_category = [$defect_category];
    if (!is_array($sections)) $sections = [$sections];

    $response = [];

    // --- Get all sections and map line numbers
    $stmt = $conn->query("SELECT section, line_no FROM m_line_no WHERE section IS NOT NULL AND section <> ''");
    $sectionLinesMap = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $sectionLinesMap[$row['section']][] = $row['line_no'];
    }

    // --- Build date ranges for all selected months/years
    $dateRanges = [];
    foreach ($years as $year) {
        foreach ($months as $month) {
            $monthStart = sprintf('%04d-%02d-01', $year, $month);
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $monthEnd = sprintf('%04d-%02d-%02d', $year, $month, $daysInMonth);
            $dateRanges[] = ['start' => $monthStart, 'end' => $monthEnd];
        }
    }

    $countB = 0;
    $countS = 0;

    // --- Loop through each date range
    foreach ($dateRanges as $dr) {
        $query = "
            SELECT line_no, harness_type, defect_category
            FROM t_minor_defect_f
            WHERE date_detected BETWEEN ? AND ?
        ";
        $params = [$dr['start'], $dr['end']];

        if (!empty($defect_category)) {
            $catPlaceholders = implode(',', array_fill(0, count($defect_category), '?'));
            $query .= " AND defect_category IN ($catPlaceholders)";
            $params = array_merge($params, $defect_category);
        }

        $stmt2 = $conn->prepare($query);
        $stmt2->execute($params);
        $defects = $stmt2->fetchAll(PDO::FETCH_ASSOC);

        foreach ($defects as $defect) {
            $lineNo = $defect['line_no'];
            $hType = strtoupper(trim($defect['harness_type']));

            // Determine which section this line belongs to
            $belongsToSelectedSection = false;

            foreach ($sectionLinesMap as $section => $lineNos) {
                if (!empty($sections) && !in_array($section, $sections)) continue;

                if (in_array($lineNo, $lineNos)) {
                    $belongsToSelectedSection = true;
                    break;
                }
            }

            if ($belongsToSelectedSection) {
                if ($hType === 'B') {
                    $countB++;
                } elseif ($hType === 'S') {
                    $countS++;
                }
            }
        }
    }

    // --- Build response for Highcharts pie chart
    $response = [
        ['name' => 'B', 'y' => $countB],
        ['name' => 'S', 'y' => $countS]
    ];

    echo json_encode($response);
    exit;
}

if ($method == 'fetch_overall_record_per_section_chart') {
    $years = $_POST['years'] ?? [];
    $months = $_POST['months'] ?? [];
    $defect_category = $_POST['defect_category'] ?? [];
    $sections = $_POST['sections'] ?? [];

    if (!is_array($years)) $years = [$years];
    if (!is_array($months)) $months = [$months];
    if (!is_array($defect_category)) $defect_category = [$defect_category];
    if (!is_array($sections)) $sections = [$sections];

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

            // --- Prepare query to fetch all defects in the month
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

            // Filter: Only process selected sections (if any)
            $filteredSections = !empty($sections)
                ? array_filter($sectionLinesMap, fn($key) => in_array($key, $sections), ARRAY_FILTER_USE_KEY)
                : $sectionLinesMap;

            foreach ($filteredSections as $section => $lineNos) {
                $weeklyRecords = array_map(fn($w) => [
                    'week_label' => $w['label'],
                    'total_records' => 0
                ], $weeksFilter);

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
    $sections = $_POST['sections'] ?? [];

    if (!is_array($years)) $years = [$years];
    if (!is_array($months)) $months = [$months];
    if (!is_array($defect_category)) $defect_category = [$defect_category];
    if (!is_array($sections)) $sections = [$sections];

    $response = [];

    foreach ($years as $year) {
        foreach ($months as $month) {
            $year = (int)$year;
            $month = (int)$month;

            $monthStart = sprintf('%04d-%02d-01', $year, $month);
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $monthEnd = sprintf('%04d-%02d-%02d', $year, $month, $daysInMonth);

            // --- Build dynamic week ranges
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

            // --- SQL CASE for each week (safe date conversion)
            $weekCases = [];
            foreach ($weeksFilter as $idx => $w) {
                $weekCases[] = "
                    SUM(CASE 
                        WHEN f.date_detected BETWEEN CONVERT(date, ?, 23) AND CONVERT(date, ?, 23) 
                        THEN 1 ELSE 0 
                    END) AS week" . ($idx + 1);
            }
            $weekCasesSql = implode(",\n", $weekCases);

            // --- Build Section Filter (using m_line_no table)
            $sectionFilterSql = '';
            $sectionParams = [];

            if (!empty($sections)) {
                $sectionPlaceholders = implode(',', array_fill(0, count($sections), '?'));
                $sectionFilterSql = "
                    AND f.line_no IN (
                        SELECT line_no FROM m_line_no WHERE section IN ($sectionPlaceholders)
                    )";
                $sectionParams = $sections;
            }

            // --- Build Defect Category Filter
            $categoryFilterSql = '';
            $categoryParams = [];

            if (!empty($defect_category)) {
                $catPlaceholders = implode(',', array_fill(0, count($defect_category), '?'));
                $categoryFilterSql = "AND defect_category IN ($catPlaceholders)";
                $categoryParams = $defect_category;
            }

            // --- Final SQL Query
            $sql = "
                WITH FilteredDefects AS (
                    SELECT line_no, car_model, date_detected
                    FROM t_minor_defect_f f
                    WHERE date_detected BETWEEN CONVERT(date, ?, 23) AND CONVERT(date, ?, 23)
                    $categoryFilterSql
                    $sectionFilterSql
                ),
                LineCarTotals AS (
                    SELECT line_no, car_model, COUNT(*) AS total_defects
                    FROM FilteredDefects
                    GROUP BY line_no, car_model
                ),
                TopLineCars AS (
                    SELECT TOP 10 line_no, car_model
                    FROM LineCarTotals
                    ORDER BY total_defects DESC
                ),
                WeeklyBreakdown AS (
                    SELECT 
                        f.line_no,
                        f.car_model,
                        $weekCasesSql
                    FROM FilteredDefects f
                    INNER JOIN TopLineCars t 
                        ON f.line_no = t.line_no AND f.car_model = t.car_model
                    GROUP BY f.line_no, f.car_model
                )
                SELECT *
                FROM WeeklyBreakdown
                ORDER BY line_no, car_model;
            ";

            // --- Build Parameter List in Correct Order
            $params = [$monthStart, $monthEnd]; // first main date range
            $params = array_merge($params, $categoryParams); // then category filters
            $params = array_merge($params, $sectionParams);  // then section filters

            // add week ranges last
            foreach ($weeksFilter as $w) {
                $params[] = $w['start'];
                $params[] = $w['end'];
            }

            // --- Execute Query
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // --- Format data for frontend
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
                    'car_model' => $row['car_model'],
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

if ($method == 'fetch_summary_per_detection_chart') {
    $years = $_POST['years'] ?? [];
    $months = $_POST['months'] ?? [];
    $defect_category = $_POST['defect_category'] ?? [];
    $sections = $_POST['sections'] ?? [];

    if (!is_array($years)) $years = [$years];
    if (!is_array($months)) $months = [$months];
    if (!is_array($defect_category)) $defect_category = [$defect_category];
    if (!is_array($sections)) $sections = [$sections];

    $response = [];

    // Build date range filters
    $dateConditions = [];
    $params = [];

    foreach ($years as $year) {
        foreach ($months as $month) {
            $monthStart = sprintf('%04d-%02d-01', (int)$year, (int)$month);
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, (int)$month, (int)$year);
            $monthEnd = sprintf('%04d-%02d-%02d', (int)$year, (int)$month, $daysInMonth);

            $dateConditions[] = "(TRY_CONVERT(date, f.date_detected, 23) BETWEEN CONVERT(date, ?, 23) AND CONVERT(date, ?, 23))";
            $params[] = $monthStart;
            $params[] = $monthEnd;
        }
    }

    // --- Base query with join to m_line_no for section filtering
    $sql = "
        SELECT TOP 7 
            f.process, 
            COUNT(*) AS total_records
        FROM t_minor_defect_f f
        LEFT JOIN m_line_no l ON f.line_no = l.line_no
        WHERE " . implode(' OR ', $dateConditions) . "
    ";

    // --- Apply defect category filter
    if (!empty($defect_category)) {
        $placeholders = implode(',', array_fill(0, count($defect_category), '?'));
        $sql .= " AND f.defect_category IN ($placeholders)";
        $params = array_merge($params, $defect_category);
    }

    // --- Apply section filter (from m_line_no)
    if (!empty($sections)) {
        $sectionPlaceholders = implode(',', array_fill(0, count($sections), '?'));
        $sql .= " AND l.section IN ($sectionPlaceholders)";
        $params = array_merge($params, $sections);
    }

    // --- Group and sort
    $sql .= " GROUP BY f.process ORDER BY total_records DESC";

    // --- Debugging (optional)
    // echo '<pre>'; print_r($params); echo '</pre>'; echo $sql; exit;

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($results);
    exit;
}

if ($method == 'fetch_defect_category_breakdown_chart') {
    $years = $_POST['years'] ?? [];
    $months = $_POST['months'] ?? [];
    $weeks = $_POST['weeks'] ?? [];
    $defect_category = $_POST['defect_category'] ?? [];
    $sections = $_POST['sections'] ?? [];

    // Ensure all inputs are arrays
    if (!is_array($years)) $years = [$years];
    if (!is_array($months)) $months = [$months];
    if (!is_array($weeks)) $weeks = [$weeks];
    if (!is_array($defect_category)) $defect_category = [$defect_category];
    if (!is_array($sections)) $sections = [$sections];

    $response = [];

    foreach ($years as $year) {
        foreach ($months as $month) {
            $year = (int)$year;
            $month = (int)$month;

            $monthStart = sprintf('%04d-%02d-01', $year, $month);
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $monthEnd = sprintf('%04d-%02d-%02d', $year, $month, $daysInMonth);

            // Base query now includes JOIN to m_line_no to get section
            $baseQuery = "
                SELECT t.date_detected, COUNT(*) AS total_records
                FROM t_minor_defect_f AS t
                LEFT JOIN m_line_no AS m ON t.line_no = m.line_no
                WHERE t.date_detected BETWEEN ? AND ?
            ";
            $params = [$monthStart, $monthEnd];

            // Add defect category filter if selected
            if (!empty($defect_category)) {
                $placeholders = implode(',', array_fill(0, count($defect_category), '?'));
                $baseQuery .= " AND t.defect_category IN ($placeholders)";
                $params = array_merge($params, $defect_category);
            }

            // Add section filter if selected (from m_line_no)
            if (!empty($sections)) {
                $placeholders = implode(',', array_fill(0, count($sections), '?'));
                $baseQuery .= " AND m.section IN ($placeholders)";
                $params = array_merge($params, $sections);
            }

            $baseQuery .= " GROUP BY t.date_detected ORDER BY t.date_detected ASC";

            $stmt = $conn->prepare($baseQuery);
            $stmt->execute($params);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Generate weekly ranges
            $weeksFilter = [];
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
                $current = new DateTime($monthStart);
                $monthEndDate = new DateTime($monthEnd);
                while ($current <= $monthEndDate) {
                    $start = clone $current;
                    $end = clone $start;
                    $end->modify('next Sunday');
                    if ($end > $monthEndDate) $end = clone $monthEndDate;
                    $weeksFilter[] = [
                        'start' => $start->format('Y-m-d'),
                        'end'   => $end->format('Y-m-d')
                    ];
                    $current = (clone $end)->modify('+1 day');
                }
            }

            // Aggregate by week
            $monthData = [
                'year' => $year,
                'month' => date('F', strtotime($monthStart)),
                'weeks' => []
            ];

            foreach ($weeksFilter as $index => $wf) {
                $weekCount = 0;
                foreach ($results as $row) {
                    if ($row['date_detected'] >= $wf['start'] && $row['date_detected'] <= $wf['end']) {
                        $weekCount += $row['total_records'];
                    }
                }

                $monthData['weeks'][] = [
                    'week_label' => 'W' . ($index + 1),
                    'week_range' => date('M j', strtotime($wf['start'])) . '–' . date('j', strtotime($wf['end'])),
                    'total_records' => $weekCount
                ];
            }

            $response[] = $monthData;
        }
    }

    echo json_encode($response);
    exit;
}

if ($method == 'fetch_sub_defect_details_breakdown_chart') {
    $years = $_POST['years'] ?? [];
    $months = $_POST['months'] ?? [];
    $weeks = $_POST['weeks'] ?? [];
    $defect_category = $_POST['defect_category'] ?? '';
    $sections = $_POST['sections'] ?? [];

    if (!is_array($years)) $years = [$years];
    if (!is_array($months)) $months = [$months];
    if (!is_array($weeks)) $weeks = [$weeks];
    if (!is_array($sections)) $sections = [$sections];

    $response = [];

    foreach ($years as $year) {
        foreach ($months as $month) {
            $year = (int)$year;
            $month = (int)$month;

            $monthStart = sprintf('%04d-%02d-01', $year, $month);
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $monthEnd = sprintf('%04d-%02d-%02d', $year, $month, $daysInMonth);

            // Base query now includes JOIN to m_line_no to filter by section
            $query = "
                SELECT 
                    t.defect_details,
                    t.date_detected,
                    COUNT(*) AS total_records
                FROM t_minor_defect_f AS t
                LEFT JOIN m_line_no AS m ON t.line_no = m.line_no
                WHERE 
                    t.date_detected BETWEEN ? AND ?
                    AND t.defect_category = ?
            ";

            $params = [$monthStart, $monthEnd, $defect_category];

            // Add section filter if selected
            if (!empty($sections)) {
                $placeholders = implode(',', array_fill(0, count($sections), '?'));
                $query .= " AND m.section IN ($placeholders)";
                $params = array_merge($params, $sections);
            }

            $query .= " GROUP BY t.defect_details, t.date_detected ORDER BY t.date_detected ASC";

            $stmt = $conn->prepare($query);
            $stmt->execute($params);
            $allResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Precompute weekly ranges
            $weeksFilter = [];
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
                $current = new DateTime($monthStart);
                $monthEndDate = new DateTime($monthEnd);
                while ($current <= $monthEndDate) {
                    $start = clone $current;
                    $end = clone $start;
                    $end->modify('next Sunday');
                    if ($end > $monthEndDate) $end = clone $monthEndDate;
                    $weeksFilter[] = [
                        'start' => $start->format('Y-m-d'),
                        'end'   => $end->format('Y-m-d')
                    ];
                    $current = (clone $end)->modify('+1 day');
                }
            }

            // Aggregate data per week
            $monthData = [
                'year' => $year,
                'month' => date('F', strtotime($monthStart)),
                'weeks' => []
            ];

            foreach ($weeksFilter as $index => $wf) {
                $weekData = [];

                foreach ($allResults as $row) {
                    $date = $row['date_detected'];
                    if ($date >= $wf['start'] && $date <= $wf['end']) {
                        $def = $row['defect_details'];
                        if (!isset($weekData[$def])) $weekData[$def] = 0;
                        $weekData[$def] += $row['total_records'];
                    }
                }

                $weekDetails = [];
                foreach ($weekData as $defect => $count) {
                    $weekDetails[] = [
                        'defect_details' => $defect,
                        'count' => $count
                    ];
                }

                usort($weekDetails, fn($a, $b) => $b['count'] <=> $a['count']); // Sort descending

                $monthData['weeks'][] = [
                    'week_label' => 'W' . ($index + 1),
                    'week_range' => date('M j', strtotime($wf['start'])) . '–' . date('j', strtotime($wf['end'])),
                    'details' => $weekDetails
                ];
            }

            $response[] = $monthData;
        }
    }

    echo json_encode($response);
    exit;
}

if ($method == 'fetch_sequence_no_breakdown_chart') {
    $years = $_POST['years'] ?? [];
    $months = $_POST['months'] ?? [];
    $defect_category = $_POST['defect_category'] ?? [];
    $sections = $_POST['sections'] ?? []; // Section filter added

    if (!is_array($years)) $years = [$years];
    if (!is_array($months)) $months = [$months];
    if (!is_array($defect_category)) $defect_category = [$defect_category];
    if (!is_array($sections)) $sections = [$sections];

    $yearList = implode(',', array_map('intval', $years));
    $monthList = implode(',', array_map('intval', $months));

    $query = "
        SELECT TOP 5 
            RTRIM(LTRIM(CONCAT(f.process, '_', f.sequence_no))) AS process_sequence,
            COUNT(*) AS total_records
        FROM t_minor_defect_f AS f
        LEFT JOIN m_line_no AS l ON f.line_no = l.line_no 
        WHERE YEAR(f.date_detected) IN ($yearList)
          AND MONTH(f.date_detected) IN ($monthList)
    ";

    $params = [];

    // Add defect category filter
    if (!empty($defect_category)) {
        $placeholders = implode(',', array_fill(0, count($defect_category), '?'));
        $query .= " AND f.defect_category IN ($placeholders)";
        $params = array_merge($params, $defect_category);
    }

    // Add section filter
    if (!empty($sections)) {
        $placeholders = implode(',', array_fill(0, count($sections), '?'));
        $query .= " AND l.section IN ($placeholders)";
        $params = array_merge($params, $sections);
    }

    $query .= "
        GROUP BY f.process, f.sequence_no
        ORDER BY total_records DESC
    ";

    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Always ensure JSON output is an array
    if (!is_array($results)) {
        $results = [$results];
    }

    // Clean up and cast data types
    foreach ($results as &$row) {
        $row['process_sequence'] = trim($row['process_sequence']);
        $row['total_records'] = (int)$row['total_records'];
    }

    echo json_encode($results);
    exit;
}

if ($method == 'fetch_connector_no_breakdown_chart') {
    $years = $_POST['years'] ?? [];
    $months = $_POST['months'] ?? [];
    $defect_category = $_POST['defect_category'] ?? [];
    $sections = $_POST['sections'] ?? [];

    if (!is_array($years)) $years = [$years];
    if (!is_array($months)) $months = [$months];
    if (!is_array($defect_category)) $defect_category = [$defect_category];
    if (!is_array($sections)) $sections = [$sections];

    $yearList = implode(',', array_map('intval', $years));
    $monthList = implode(',', array_map('intval', $months));

    $query = "
        SELECT TOP 5 
            RTRIM(LTRIM(CONCAT(f.process, '_', f.connector_no))) AS process_connector,
            COUNT(*) AS total_records
        FROM t_minor_defect_f AS f
        LEFT JOIN m_line_no AS l ON f.line_no = l.line_no
        WHERE YEAR(f.date_detected) IN ($yearList)
          AND MONTH(f.date_detected) IN ($monthList)
    ";

    $params = [];

    // Filter by defect category if provided
    if (!empty($defect_category)) {
        $placeholders = implode(',', array_fill(0, count($defect_category), '?'));
        $query .= " AND f.defect_category IN ($placeholders)";
        $params = array_merge($params, $defect_category);
    }

    // Add section filter logic
    if (!empty($sections)) {
        $placeholders = implode(',', array_fill(0, count($sections), '?'));
        $query .= " AND l.section IN ($placeholders)";
        $params = array_merge($params, $sections);
    }

    $query .= "
        GROUP BY f.process, f.connector_no
        ORDER BY total_records DESC
    ";

    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!is_array($results)) {
        $results = [$results];
    }

    foreach ($results as &$row) {
        $row['process_connector'] = trim($row['process_connector']);
        $row['total_records'] = (int)$row['total_records'];
    }

    echo json_encode($results);
    exit;
}

if ($method == 'fetch_line_category_month_week_chart') {
    $years = $_POST['years'] ?? [];
    $months = $_POST['months'] ?? [];
    $defect_category = $_POST['defect_category'] ?? [];
    $sections = $_POST['sections'] ?? []; 

    if (!is_array($years)) $years = [$years];
    if (!is_array($months)) $months = [$months];
    if (!is_array($defect_category)) $defect_category = [$defect_category];
    if (!is_array($sections)) $sections = [$sections];

    $response = [];

    foreach ($years as $year) {
        foreach ($months as $month) {
            $year = (int)$year;
            $month = (int)$month;

            $monthStart = new DateTime("$year-$month-01");
            $monthEnd = (clone $monthStart)->modify('last day of this month');

            // Build week boundaries
            $weeks = [];
            $current = clone $monthStart;
            while ($current <= $monthEnd) {
                $start = clone $current;
                $end = (clone $start)->modify('next sunday');
                if ($end > $monthEnd) $end = clone $monthEnd;

                $weeks[] = [
                    'start' => $start->format('Y-m-d'),
                    'end'   => $end->format('Y-m-d')
                ];

                $current = (clone $end)->modify('+1 day');
            }

            // UPDATED QUERY with LEFT JOIN for section
            $query = "
                SELECT 
                    ISNULL(t.line_category, 'Unknown') AS line_category,
                    t.date_detected,
                    ISNULL(m.section, 'Unknown') AS section
                FROM t_minor_defect_f AS t
                LEFT JOIN m_line_no AS m 
                    ON t.line_no = m.line_no
                WHERE YEAR(t.date_detected) = ? 
                  AND MONTH(t.date_detected) = ?
            ";

            $params = [$year, $month];

            // Defect category filter
            if (!empty($defect_category)) {
                $placeholders = implode(',', array_fill(0, count($defect_category), '?'));
                $query .= " AND t.defect_category IN ($placeholders)";
                $params = array_merge($params, $defect_category);
            }

            // Section filter
            if (!empty($sections)) {
                $placeholders = implode(',', array_fill(0, count($sections), '?'));
                $query .= " AND m.section IN ($placeholders)";
                $params = array_merge($params, $sections);
            }

            $stmt = $conn->prepare($query);
            $stmt->execute($params);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Preprocess counts by line_category + week index
            $counts = [];
            foreach ($rows as $r) {
                $lc = $r['line_category'] ?: 'Unknown';
                $d = new DateTime($r['date_detected']);

                foreach ($weeks as $i => $wf) {
                    if ($d >= new DateTime($wf['start']) && $d <= new DateTime($wf['end'])) {
                        $counts[$lc]['W' . ($i + 1)] = ($counts[$lc]['W' . ($i + 1)] ?? 0) + 1;
                        break;
                    }
                }
            }

            // Build structured response
            $monthData = [
                'year' => $year,
                'month' => strtoupper(date('M', strtotime("$year-$month-01"))),
                'weeks' => [],
                'line_categories' => []
            ];

            // Week labels for chart x-axis
            $monthData['weeks'] = array_map(function ($wf, $i) {
                return [
                    'week_label' => 'W' . ($i + 1),
                    'week_range' => date('M j', strtotime($wf['start'])) . '–' . date('j', strtotime($wf['end']))
                ];
            }, $weeks, array_keys($weeks));

            // Convert counts to chart format
            foreach ($counts as $lc => $weekData) {
                $weeklyRecords = [];
                foreach ($weeks as $i => $wf) {
                    $label = 'W' . ($i + 1);
                    $weeklyRecords[] = [
                        'week_label' => $label,
                        'week_range' => date('M j', strtotime($wf['start'])) . '–' . date('j', strtotime($wf['end'])),
                        'total_records' => $weekData[$label] ?? 0
                    ];
                }
                $monthData['line_categories'][] = [
                    'line_category' => $lc,
                    'weekly_records' => $weeklyRecords
                ];
            }

            // Handle empty months
            if (empty($counts)) {
                $monthData['line_categories'][] = [
                    'line_category' => 'Unknown',
                    'weekly_records' => array_map(function ($wf, $i) {
                        return [
                            'week_label' => 'W' . ($i + 1),
                            'week_range' => date('M j', strtotime($wf['start'])) . '–' . date('j', strtotime($wf['end'])),
                            'total_records' => 0
                        ];
                    }, $weeks, array_keys($weeks))
                ];
            }

            $response[] = $monthData;
        }
    }

    echo json_encode($response);
    exit;
}