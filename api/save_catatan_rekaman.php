<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');
require '../config/db.php';

// Validasi parameter wajib
if (
    empty($_POST['judul_catatan']) ||
    empty($_POST['transkrip_text']) ||
    empty($_POST['tanggal_rekaman']) ||
    !isset($_POST['durasi_detik'])
) {
    echo json_encode([
        "success" => false,
        "message" => "Parameter tidak lengkap"
    ]);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO catatan_rekaman (judul_catatan, transkrip_text, file_path, tanggal_rekaman, durasi_detik) 
    VALUES (:judul_catatan, :transkrip_text, :file_path, :tanggal_rekaman, :durasi_detik)");

    $stmt->execute([
        ':judul_catatan' => $_POST['judul_catatan'],
        ':transkrip_text' => $_POST['transkrip_text'],
        ':file_path' => isset($_POST['file_path']) ? $_POST['file_path'] : '',
        ':tanggal_rekaman' => $_POST['tanggal_rekaman'],
        ':durasi_detik' => $_POST['durasi_detik'],
    ]);

    echo json_encode([
        "success" => true,
        "message" => "Data berhasil disimpan"
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Gagal menyimpan data: " . $e->getMessage()
    ]);
}
?>
