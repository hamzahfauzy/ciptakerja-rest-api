<?php

if(request() != 'GET')
{
    http_response_code(405);
    echo json_encode(['success'=>false,'message'=>'Method not allowed']);
    die();
}

// validation here
Validation::run([
    'username' => [
        'required'
    ]
], $_GET, 'json');

$username = $_GET['username'];
$api_url  = config('jagel_api_url') . 'user';
$params   = [
    'apikey' => app('jagel_api_key'),
    'type'   => 'username',
    'value'  => $username
];

$api_url  = $api_url .'?'. http_build_query($params);
$request  = simple_curl($api_url);

$header   = explode(' ',$request['headers'][0]);
$header_code = $header[1];

http_response_code($header_code);

echo $request['content'];
die();