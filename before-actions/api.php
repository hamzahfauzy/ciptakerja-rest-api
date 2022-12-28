<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json");

if(startWith($route, 'api/v1/'))
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