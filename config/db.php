<?php

$host = "localhost";
$db_name = "virtual_asisten";
$username = "root";
$password = "";

// Menggunakan PDO untuk kemudahan dan keamanan
try {
    $pdo = new PDO("mysql:host={$host};dbname={$db_name};charset=utf8mb4", $username, $password);
    // Set error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $exception){
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Koneksi database gagal: " . $exception->getMessage()
    ]);
    exit();
}
?>