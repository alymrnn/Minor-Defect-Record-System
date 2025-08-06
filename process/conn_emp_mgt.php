<?php
$servername = '172.25.116.188';
$username = 'SA';
$password = 'SystemGroup@2022';
$database = 'emp_mgt_db';

try {
    $dsn = "sqlsrv:Server=$servername;Database=$database";
    $conn_emp_mgt = new PDO($dsn, $username, $password);
    $conn_emp_mgt->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'NO CONNECTION: ' . $e->getMessage();
}
