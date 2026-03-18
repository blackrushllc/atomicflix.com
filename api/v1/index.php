<?php
/**
 * AtomicFlix Mock API Router
 * This handles all API requests and returns a positive response.
 */

// Set response header to JSON
header('Content-Type: application/json');

// Include database connection
require_once __DIR__ . '/db.php';

// Handle CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Get the requested URI to see what's being called
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];

// Extract the path from the URI
$apiPath = parse_url($requestUri, PHP_URL_PATH);
$apiPath = preg_replace('/^\/api\/v1\//', '', $apiPath);

// Get the input data (for POST/PUT)
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Construct the positive response
// For now, it doesn't actually do anything, it just returns success.
$response = [
    'status' => 'success',
    'message' => "AtomicFlix API response: Successfully handled $requestMethod request to $apiPath",
    'data' => [
        'method' => $requestMethod,
        'path' => $apiPath,
        'received' => $data ?: $_REQUEST,
        'timestamp' => date('Y-m-d H:i:s'),
        'id' => rand(1000, 9999), // Mock ID if needed
    ]
];

// Return the response
echo json_encode($response, JSON_PRETTY_PRINT);
?>
