<?php
header('Content-Type: application/json');

$lat = isset($_GET['lat']) ? $_GET['lat'] : '-6.200000';
$lon = isset($_GET['lon']) ? $_GET['lon'] : '106.816666';
$method = 32; // Singapore (lebih mendekati Kemenag)

$date = date('d-m-Y'); // hari ini

$url = "https://api.aladhan.com/v1/timings/$date?latitude=$lat&longitude=$lon&method=$method&timezonestring=Asia/Jakarta&school=1";

// Ambil data dari Aladhan API
$response = file_get_contents($url);

if ($response === FALSE) {
    echo json_encode([
        'success' => false,
        'message' => 'Gagal mengambil data jadwal sholat.'
    ]);
    exit;
}

$data = json_decode($response, true);

if ($data['code'] == 200) {
    $timings = $data['data']['timings'];
    $dateHijri = $data['data']['date']['hijri']['date'] . " H";
    $dateGregorian = $data['data']['date']['gregorian']['date'];

    $jadwal = [
        'imsak' => substr($timings['Imsak'], 0, 5),
        'subuh' => substr($timings['Fajr'], 0, 5),
        'zuhur' => substr($timings['Dhuhr'], 0, 5),
        'ashar' => substr($timings['Asr'], 0, 5),
        'maghrib' => substr($timings['Maghrib'], 0, 5),
        'isya' => substr($timings['Isha'], 0, 5),
    ];

    echo json_encode([
        'success' => true,
        'data' => [
            'lokasi' => $data['data']['meta']['timezone'],
            'tanggal_hijriah' => $dateHijri,
            'tanggal_masehi' => $dateGregorian,
            'jadwal' => $jadwal,
        ]
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Data tidak tersedia.'
    ]);
}
?>
