<?php

$host = $_SERVER['HTTP_HOST'] ?? ($_SERVER['SERVER_NAME'] ?? '');
$isLocal = str_starts_with($host, 'localhost') || str_starts_with($host, '127.0.0.1');

if ($isLocal) {
    $dbHost = 'localhost';
    $dbUser = 'root';
    $dbPass = '';
    $dbName = 'chess_library';
} else {
    $dbHost = 'sql102.infinityfree.com';
    $dbUser = 'if0_43156422';
    $dbPass = 'hlpAvlsK6P9';
    $dbName = 'if0_43156422_chess_library';
}

$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($conn->connect_error) {
    http_response_code(500);
    exit("Database connection failed.");
}

$conn->set_charset("utf8mb4");
