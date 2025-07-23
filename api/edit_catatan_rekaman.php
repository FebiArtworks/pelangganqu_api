<?php
header('Content-Type: application/json');
require_once '../config/db.php';

if (!isset($_POST['id_rekam']) || empty($_POST['id_rekam'])) {
    echo json_encode([
        "success" => false,
        "message" => "ID rekaman tidak ditemukan"
    ]);
    exit;
}

if (!isset($_POST['judul_catatan'])) {
    echo json_encode([
        "success" => false,
        "message" => "Judul catatan tidak ditemukan"
    ]);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE catatan_rekaman SET 
        judul_catatan = :judul_catatan
        WHERE id_rekam = :id_rekam");

    $stmt->execute([
        ':judul_catatan' => $_POST['judul_catatan'],
        ':id_rekam' => $_POST['id_rekam']
    ]);

    echo json_encode([
        "success" => true,
        "message" => "Judul catatan berhasil diperbarui"
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Gagal memperbarui judul: " . $e->getMessage()
    ]);
}
?>
