<?php
header('Content-Type: application/json');
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_pengingat = $_POST['id_pengingat'] ?? '';

    if (empty($id_pengingat)) {
        echo json_encode(["success" => false, "message" => "ID pengingat tidak ditemukan."]);
        exit;
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM pengingat WHERE id_pengingat = :id_pengingat");
        $stmt->bindParam(':id_pengingat', $id_pengingat, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo json_encode(["success" => true, "message" => "Pengingat berhasil dihapus."]);
        } else {
            echo json_encode(["success" => false, "message" => "Pengingat tidak ditemukan atau sudah dihapus."]);
        }
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Gagal menghapus pengingat: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Metode tidak diizinkan."]);
}
?>
