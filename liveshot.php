<?php
error_reporting(0);
ini_set('display_errors', 0);
ini_set('zlib.output_compression', 1);
ini_set('zlib.output_compression_level', 9);
ob_start('ob_gzhandler');
header('Content-Type: application/json');

if (!isset($_GET['url'])) {
    echo json_encode([
        'status'    => 'error',
        'telegram'  => 'https://t.me/ondexsystems',
        'author'    => 'Ondex Systems',
        'api_ismi'  => 'Live Shot',
        'message'   => 'URL parametresi eksik.'
    ]);
    exit;
}

$url = trim($_GET['url']);

if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
    $url = "https://" . $url;
}

$url = filter_var($url, FILTER_SANITIZE_URL);

if (!filter_var($url, FILTER_VALIDATE_URL)) {
    echo json_encode([
        'status'    => 'error',
        'telegram'  => 'https://t.me/ondexsystems',
        'author'    => 'Ondex Systems',
        'api_ismi'  => 'Live Shot',
        'message'   => 'Geçersiz URL.'
    ]);
    exit;
}

$screenshot_url = "https://image.thum.io/get/width/1024/" . $url;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $screenshot_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 20); 
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.102 Safari/537.36');

$imageData = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
if(curl_errno($ch)){
    $error_msg = curl_error($ch);
}
curl_close($ch);

if ($http_code !== 200 || !$imageData) {
    echo json_encode([
        'status'    => 'error',
        'telegram'  => 'https://t.me/ondexsystems',
        'author'    => 'Ondex Systems',
        'api_ismi'  => 'Live Shot',
        'message'   => 'Ekran görüntüsü alınamadı. Lütfen tekrar deneyin.'
    ]);
    exit;
}

$base64Image = base64_encode($imageData);

echo json_encode([
    'status'    => 'success',
    'telegram'  => 'https://t.me/ondexsystems',
    'author'    => 'Ondex Systems',
    'api_ismi'  => 'Live Shot',
    'image'     => $base64Image
]);

ob_end_flush();
?>
