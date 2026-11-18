<?php
header('Content-Type: application/json');

$limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 4;
$skip = isset($_GET['skip']) ? (int) $_GET['skip'] : 0;

$host = 'https://dummyjson.com';
$endpoint = '/products';
$query = http_build_query([
    'limit' => $limit,
    'skip' => $skip
]);

$apiUrl = $host . $endpoint . '?' . $query;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-AJAX-Client');

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

if ($response === false || $httpCode !== 200) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Failed to fetch products from API',
        'details' => $curlError,
        'http_code' => $httpCode
    ]);
    exit;
}

echo $response;
