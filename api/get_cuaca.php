<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once "../config/config.php";
require_once "../helpers/helper.php";

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['lat']) || !isset($_GET['lon'])) {
        echo json_encode([
            "success" => false,
            "message" => "Parameter lat dan lon wajib diisi."
        ]);
        exit();
    }

    $lat = sanitize_input($_GET['lat']);
    $lon = sanitize_input($_GET['lon']);
    $apiKey = "7f021b5f7a56acea1eb600c2cf8b17db"; // ganti dengan API_KEY milikmu

    $url = "https://api.openweathermap.org/data/2.5/weather?lat=$lat&lon=$lon&appid=$apiKey&units=metric&lang=id";

    $response = file_get_contents($url);
    if ($response === FALSE) {
        echo json_encode([
            "success" => false,
            "message" => "Gagal mengambil data cuaca."
        ]);
        exit();
    }

    $weatherData = json_decode($response, true);

    $hasil = [
        "success" => true,
        "data" => [
            "lokasi" => $weatherData['name'],
            "cuaca" => $weatherData['weather'][0]['description'],
            "icon" => $weatherData['weather'][0]['icon'],
            "suhu" => $weatherData['main']['temp']
        ]
    ];

    echo json_encode($hasil);
    exit();
} else {
    echo json_encode([
        "success" => false,
        "message" => "Metode tidak diizinkan."
    ]);
    exit();
}
?>
