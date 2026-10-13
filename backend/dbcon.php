<?php

$host = $_SERVER['HTTP_HOST'] ?? ($_SERVER['SERVER_NAME'] ?? '');
$isLocal = (strpos($host, 'localhost') === 0) || (strpos($host, '127.0.0.1') === 0);

if ($isLocal) {
    $dbHost = 'localhost';
    $dbUser = 'root';
    $dbPass = '';
    $dbName = 'chess_library';
} else {
    $dbHost = 'sql102.infinityfree.com';
    $dbUser = 'if0_43156422';
    $dbPass = 'sdbyf27n';
    $dbName = 'if0_43156422_chess_library';
}

mysqli_report(MYSQLI_REPORT_OFF);

$conn = @new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($conn->connect_error) {
    http_response_code(500);
    exit("Database connection failed.");
}

$conn->set_charset("utf8mb4");
