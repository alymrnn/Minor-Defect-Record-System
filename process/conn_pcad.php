<?php
$servername = '172.25.116.188';
$username = 'SA';
$password = 'SystemGroup@2022';
$database = 'pcad_db';

try {
    $conn_pcad = new PDO("sqlsrv:Server=$servername;Database=$database", $username, $password);
    $conn_pcad->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'NO CONNECTION: ' . $e->getMessage();
}