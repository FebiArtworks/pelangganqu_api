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

try {
    $stmt = $pdo->prepare("DELETE FROM catatan_rekaman WHERE id_rekam = :id_rekam");
    $stmt->execute([
        ':id_rekam' => $_POST['id_rekam']
    ]);

    if ($stmt->rowCount() > 0) {
        echo json_encode([
            "success" => true,
            "message" => "Catatan berhasil dihapus"
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Catatan tidak ditemukan atau sudah dihapus"
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Gagal menghapus catatan: " . $e->getMessage()
    ]);
}
?>
