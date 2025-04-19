<?php
error_reporting(0);
ini_set('display_errors', 0);
ini_set('zlib.output_compression', 1);
ini_set('zlib.output_compression_level', 9);
ob_start('ob_gzhandler');
header('Content-Type: application/json');

$query = isset($_GET['query']) ? $_GET['query'] : '';

if (empty($query)) {
    $error_response = [
        'telegram'  => 'https://t.me/ondexsystems',
        'author'    => 'Ondex Systems',
        'api_ismi'  => 'Leak Sorgu',
        'message'   => 'Geçerli bir veri giriniz'
    ];
    header('Content-Type: application/json');
    echo json_encode($error_response);
    exit;
}

$api_url = 'https://detector.tools/api/v1/pass-leak/?query=' . urlencode($query);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);

if(curl_errno($ch)) {
    echo 'cURL Error: ' . curl_error($ch);
    exit;
}
curl_close($ch);

$response_data = json_decode($response, true);

if (isset($response_data['success']) && $response_data['success'] === true) {
    $translated_response = [
        'success'   => true,
        'telegram'  => 'https://t.me/ondexsystems',
        'author'    => 'Ondex Systems',
        'api_ismi'  => 'Leak Sorgu',
        'veri'      => []
    ];

    foreach ($response_data['leak'] as $leak) {
        $translated_response['veri'][] = [
            'giriş' => $leak['login'],
            'sifre' => $leak['password']
        ];
    }

    echo json_encode($translated_response);
} else {
    $error_response = [
        'telegram'  => 'https://t.me/ondexsystems',
        'author'    => 'Ondex Systems',
        'api_ismi'  => 'Leak Sorgu',
        'message'   => 'Veri bulunamadı'
    ];
    echo json_encode($error_response);
}
ob_end_flush();
?>
