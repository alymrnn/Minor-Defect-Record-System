<?php
// ==MSSQL SERVER CONNECTION
// TRIAL
// date_default_timezone_set('Asia/Manila');

// $servername = '172.25.114.171\SQLEXPRESS';
// $username = 'SA';
// $password = 'SystemGroup2018';
// $database = 'minor_defect_record';

// date_default_timezone_set('Asia/Manila');
// $server_date_time = date('Y-m-d H:i:s');
// $server_date_only = date('Y-m-d');
// $server_date_month = date('M');
// $server_date_day = date('d');
// $server_date_month_time = date('Y-m-01 H:i:s');
// $server_time = date('H:i:s');

// try {
//     $conn = new PDO("sqlsrv:Server=$servername;Database=$database", $username, $password);
//     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// } catch (PDOException $e) {
//     echo 'NO CONNECTION: ' . $e->getMessage();
// }

// LIVE SERVER CONNECTION
date_default_timezone_set('Asia/Manila');

$servername = '172.25.116.188';
$username = 'SA';
$password = 'SystemGroup@2022';
$database = 'minor_defect_record';

date_default_timezone_set('Asia/Manila');
$server_date_time = date('Y-m-d H:i:s');
$server_date_only = date('Y-m-d');
$server_date_month = date('M');
$server_date_day = date('d');
$server_date_month_time = date('Y-m-01 H:i:s');
$server_time = date('H:i:s');

try {
    $conn = new PDO("sqlsrv:Server=$servername;Database=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'NO CONNECTION: ' . $e->getMessage();
}
?>