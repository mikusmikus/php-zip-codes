<?php
require_once __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;
use Dotenv\Dotenv;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Add required environment variables
$dotenv->required(['API_BASE_URL', 'API_TIMEOUT', 'SERVICE_TOKEN']);

header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$zipCode = $_POST['zip_code'] ?? '';

// Validate zip code format (5 digits)
if (empty($zipCode) || !preg_match('/^\d{5}$/', $zipCode)) {
    http_response_code(400);
    echo json_encode(['error' => 'Please enter a valid 5-digit zip code']);
    exit;
}

try {
    $client = new Client([
        'base_uri' => $_ENV['API_BASE_URL'],
        'timeout' => $_ENV['API_TIMEOUT']
    ]);

    // Submit zip code to API with ServiceToken from env
    $response = $client->get('/gapi/services/wordpress/zip-codes/validate/', [
        'query' => ['zipcode' => $zipCode],
        'headers' => [
            'Authorization' => 'ServiceToken ' . $_ENV['SERVICE_TOKEN']
        ]
    ]);

    if ($response->getStatusCode() === 200) {
        $responseData = json_decode($response->getBody(), true);


        $isValid = $responseData['valid'];
        
        if ($responseData['valid'] === true) {
            echo json_encode([
                'success' => true,
                'message' => 'Zip code is valid and eligible for discount!'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Sorry, this zip code is not eligible for discount.'
            ]);
        }
    } else {
        throw new Exception('Unexpected response from API');
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'An error occurred while validating the zip code']);
} 