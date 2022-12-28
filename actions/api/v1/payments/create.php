<?php

if(request() != 'POST')
{
    http_response_code(405);
    echo json_encode(['success'=>false,'message'=>'Method not allowed']);
    die();
}

// validation here
Validation::run([
    'username' => [
        'required'
    ],
    'amount' => [
        'required','number'
    ],
], $_POST, 'json');

$username = $_POST['username'];
$amount   = $_POST['amount'];
$api_url  = config('jagel_api_url') . 'balance/adjust';
$params   = [
    'apikey' => app('jagel_api_key'),
    'type'   => 'username',
    'value'  => $username,
    'amount'  => $amount,
    'note'  => 'Saldo via Bank BTN',
];

$request  = simple_curl($api_url, 'POST', http_build_query($params));

$header   = explode(' ',$request['headers'][0]);
$header_code = $header[1];

http_response_code($header_code);

echo $request['content'];
die();