<?php

error_reporting(0);
ini_set('display_errors', 0);

header("Content-Type: application/json");

if (isset($_GET['url']) && !empty($_GET['url'])) {
    $input_url = $_GET['url'];

    $curl = curl_init();

    $request_url = "https://siterelic.com/siterelic-api/metascraping";

    $payload = json_encode([
        "url" => $input_url
    ]);

    curl_setopt_array($curl, [
        CURLOPT_URL => $request_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Accept: application/json"
        ]
    ]);

    $response = curl_exec($curl);

    if (curl_errno($curl)) {
        $output = [
            "success" => false,
            'telegram'  => 'https://t.me/ondexsystems',
            'author'    => 'Ondex Systems',
            'api_ismi'  => 'Meta Scraping',
            "error" => curl_error($curl)
        ];
    } else {
        $output = [
            "success" => true,
            'telegram'  => 'https://t.me/ondexsystems',
            'author'    => 'Ondex Systems',
            'api_ismi'  => 'Meta Scraping',
            "data" => json_decode($response, true)
        ];
    }

    curl_close($curl);
} else {
    $output = [
        "success" => false,
        'telegram'  => 'https://t.me/ondexsystems',
        'author'    => 'Ondex Systems',
        'api_ismi'  => 'Meta Scraping',
        "error" => "url parametresi gerekli."
    ];
}

echo json_encode($output, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
