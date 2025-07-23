<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");

// Sambungkan ke database (menggunakan PDO)
require_once '../config/db.php';

// Inisialisasi variabel
$title = null;
$body = null;
$schedule_datetime = null;
$is_completed = 0;
$created_at = date('Y-m-d H:i:s');

// Cek jika $_POST kosong, baca JSON input
if (empty($_POST)) {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    $title = isset($data['title']) ? $data['title'] : null;
    $body = isset($data['body']) ? $data['body'] : null;
    $schedule_datetime = isset($data['schedule_datetime']) ? $data['schedule_datetime'] : null;
    $is_completed = isset($data['is_completed']) ? $data['is_completed'] : 0;
} else {
    $title = isset($_POST['title']) ? $_POST['title'] : null;
    $body = isset($_POST['body']) ? $_POST['body'] : null;
    $schedule_datetime = isset($_POST['schedule_datetime']) ? $_POST['schedule_datetime'] : null;
    $is_completed = isset($_POST['is_completed']) ? $_POST['is_completed'] : 0;
}

// Validasi data
if ($title && $body && $schedule_datetime) {
    try {
        $sql = "INSERT INTO pengingat (title, body, schedule_datetime, is_completed, created_at) 
                VALUES (:title, :body, :schedule_datetime, :is_completed, :created_at)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':body', $body);
        $stmt->bindParam(':schedule_datetime', $schedule_datetime);
        $stmt->bindParam(':is_completed', $is_completed, PDO::PARAM_INT);
        $stmt->bindParam(':created_at', $created_at);

        $stmt->execute();

        echo json_encode([
            "status" => "success",
            "message" => "Pengingat berhasil disimpan"
        ]);
    } catch (PDOException $e) {
        echo json_encode([
            "status" => "error",
            "message" => "Gagal menyimpan pengingat: " . $e->getMessage()
        ]);
    }
} else {
   echo json_encode([
    "status" => "error",
    "message" => "Data tidak lengkap.",
    "debug" => [
        "title" => $title,
        "body" => $body,
        "schedule_datetime" => $schedule_datetime,
    ]
]);
}
?>
