<?php

// header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Headers: *");
// header("Access-Control-Allow-Methods: *");
// Specify domains from which requests are allowed
header('Access-Control-Allow-Origin: *');

// Specify which request methods are allowed
header('Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS');

// Additional headers which may be sent along with the CORS request
header('Access-Control-Allow-Headers: X-Requested-With,Authorization,Content-Type');

// Set the age to 1 day to improve speed/caching.
header('Access-Control-Max-Age: 86400');

header("Content-Type: application/json");

if (strtolower($_SERVER['REQUEST_METHOD']) == 'options') {
    exit();
}

if(startWith($route, 'api/v1/') && $route != 'api/v1/virtual-accounts/create')
{
    // check if token is sent
    $token = getBearerToken();
    if(!$token)
    {
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'message' => 'Api key is required'
        ]);
        die();
    }

    // check if token is match
    if(trim($token) != trim(app('cipta_kerja_api_key')))
    {
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'message' => 'Api key is not valid'
        ]);
        die();
    }
}

return true;