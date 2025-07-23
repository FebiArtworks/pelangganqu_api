<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require_once "../config/db.php";

try {
    $stmt = $pdo->query("SELECT id_lokasi, nama_kota, nama_provinsi, latitude, longitude, timezone FROM lokasi_preset ORDER BY nama_kota ASC");
    $lokasi = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "data" => $lokasi
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Gagal mengambil data lokasi: " . $e->getMessage()
    ]);
}
?>
