<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

require_once "../config/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Ambil semua pengingat, urutkan dari terbaru
        $sql = "SELECT * FROM pengingat ORDER BY schedule_datetime DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            "success" => true,
            "message" => "Data pengingat berhasil diambil.",
            "data" => $data
        ]);
    } catch (PDOException $e) {
        echo json_encode([
            "success" => false,
            "message" => "Terjadi kesalahan: " . $e->getMessage()
        ]);
    }
    exit();
} else {
    echo json_encode([
        "success" => false,
        "message" => "Metode tidak diizinkan. Gunakan GET."
    ]);
    exit();
}
?>
