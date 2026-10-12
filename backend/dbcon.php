<?php

$conn = new mysqli("localhost", "root", "", "chess_library");

if ($conn->connect_error) {
    http_response_code(500);
    exit("Database connection failed.");
}

$conn->set_charset("utf8mb4");
