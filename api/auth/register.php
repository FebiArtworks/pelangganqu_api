<?php
// api/auth/register.php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require_once "../../config/db.php";
require_once "../../config/config.php";
require_once "../../helpers/helper.php";

// Menggunakan $_POST karena Flutter kirim form-data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $required_fields = ['username', 'password', 'nama_lengkap', 'usia', 'alamat_lengkap'];
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
    $nama_lengkap = sanitize_input($_POST['nama_lengkap']);
    $usia = intval($_POST['usia']);
    $alamat_lengkap = sanitize_input($_POST['alamat_lengkap']);

    // Cek apakah Flutter mengirim lokasi manual
    $latitude = isset($_POST['latitude']) && $_POST['latitude'] != '' ? $_POST['latitude'] : null;
    $longitude = isset($_POST['longitude']) && $_POST['longitude'] != '' ? $_POST['longitude'] : null;
    $city = isset($_POST['city']) && $_POST['city'] != '' ? $_POST['city'] : null;
    $province = isset($_POST['province']) && $_POST['province'] != '' ? $_POST['province'] : null;
    $country = isset($_POST['country']) && $_POST['country'] != '' ? $_POST['country'] : "Indonesia";
    $timezone = isset($_POST['timezone']) && $_POST['timezone'] != '' ? $_POST['timezone'] : "Asia/Jakarta";

    // Jika Flutter tidak kirim lokasi, ambil default dari lokasi_preset (Jakarta)
    if (!$latitude || !$longitude || !$city || !$province) {
        $stmt = $pdo->prepare("SELECT * FROM lokasi_preset WHERE id_lokasi = 1 LIMIT 1");
        $stmt->execute();
        $preset = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($preset) {
            $latitude = $preset['latitude'];
            $longitude = $preset['longitude'];
            $city = $preset['nama_kota'];
            $province = $preset['nama_provinsi'];
            $country = "Indonesia";
            $timezone = $preset['timezone'];
        }
    }

    try {
        // Cek apakah username sudah terdaftar
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->rowCount() > 0) {
            echo json_encode([
                "success" => false,
                "message" => "Username sudah digunakan, silakan gunakan username lain."
            ]);
            exit();
        }

        // Hash password
        $hashed_password = hash_password($password);

        // Insert user ke tabel users
        $stmt = $pdo->prepare("INSERT INTO users 
        (nama_lengkap, usia, alamat_lengkap, username, password, latitude, longitude, city, province, country, timezone) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $nama_lengkap,
            $usia,
            $alamat_lengkap,
            $username,
            $hashed_password,
            $latitude,
            $longitude,
            $city,
            $province,
            $country,
            $timezone
        ]);

        $id_user = $pdo->lastInsertId();

        echo json_encode([
            "success" => true,
            "message" => "Registrasi berhasil.",
            "data" => [
                "id_user" => $id_user,
                "nama_lengkap" => $nama_lengkap,
                "username" => $username,
                "usia" => $usia,
                "alamat_lengkap" => $alamat_lengkap,
                "latitude" => $latitude,
                "longitude" => $longitude,
                "city" => $city,
                "province" => $province,
                "country" => $country,
                "timezone" => $timezone
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
