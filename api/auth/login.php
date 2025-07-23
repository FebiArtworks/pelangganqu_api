<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

require_once "../../config/db.php";
require_once "../../config/config.php";
require_once "../../helpers/helper.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $required_fields = ['username', 'password'];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            echo json_encode([
                "success" => false,
                "message" => "Field '$field' wajib diisi."
            ]);
            exit();
        }
    }

    $username = sanitize_input($_POST['username']);
    $password = sanitize_input($_POST['password']);

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            echo json_encode([
                "success" => false,
                "message" => "Username tidak ditemukan."
            ]);
            exit();
        }

        if (!verify_password($password, $user['password'])) {
            echo json_encode([
                "success" => false,
                "message" => "Password salah."
            ]);
            exit();
        }

        // Update last_login
        $update_stmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id_user = ?");
        $update_stmt->execute([$user['id_user']]);

        echo json_encode([
            "success" => true,
            "message" => "Login berhasil.",
            "data" => [
                "id_user" => $user['id_user'],
                "nama_lengkap" => $user['nama_lengkap'],
                "username" => $user['username'],
                "usia" => $user['usia'],
                "alamat_lengkap" => $user['alamat_lengkap'],
                "latitude" => $user['latitude'],
                "longitude" => $user['longitude'],
                "city" => $user['city'],
                "lokasi" => $user['city'],
                "province" => $user['province'],
                "country" => $user['country'],
                "timezone" => $user['timezone'],
                "is_active" => $user['is_active'],
                "last_login" => $user['last_login']
            ]
        ]);
        exit();
    } catch (PDOException $e) {
        echo json_encode([
            "success" => false,
            "message" => "Terjadi kesalahan: " . $e->getMessage()
        ]);
        exit();
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Metode tidak diizinkan."
    ]);
    exit();
}
?>
