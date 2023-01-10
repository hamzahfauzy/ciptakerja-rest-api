<?php

class Snap
{
    function createSignature($timestamp)
    {
        $endpointUrl = config('snap_api_base_url') . '/v1/api/v1.0/utilities/signature-auth';
        $request = simple_curl($endpointUrl,'POST',null,[
            "content-type: application/json",
            "Private_Key: ".config('snap_api_private_key'),
            "X-CLIENT-KEY: ".config('snap_api_client_key'),
            "X-TIMESTAMP: ".$timestamp
        ]);

        $header   = explode(' ',$request['headers'][0]);
        $header_code = $header[1];
        $response = json_decode($request['content']);

        if($header_code != 200)
        {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'state' => 'signature',
                'data' => $response
            ]);
            die();
        }

        return $response;
    }
    
    function getBearer($timestamp, $signature)
    {
        $endpointUrl = config('snap_api_base_url') . '/v1/api/v1.0/access-token/b2b';
        $request = simple_curl($endpointUrl,'POST','{"grantType":"client_credentials","additionalInfo":{}}',[
            "Content-Type: application/json",
            "X-CLIENT-KEY: ".config('snap_api_client_key'),
            "X-SIGNATURE: ".$signature,
            "X-TIMESTAMP: ".$timestamp
        ]);

        $header   = explode(' ',$request['headers'][0]);
        $header_code = $header[1];
        $response = json_decode($request['content']);

        if($header_code != 200)
        {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'state' => 'bearer',
                'data' => $response
            ]);
            die();
        }

        return $response;
    }
    
    function createServiceToken($timestamp, $accessToken, $body, $method, $endpointUrl)
    {
        $apiEndpointUrl = config('snap_api_base_url') . '/v1/api/v1.0/utilities/signature-service';
        $request = simple_curl($apiEndpointUrl,'POST',$body,[
            "Content-Type: application/json",
            "AccessToken: ".$accessToken,
            "EndpoinUrl: ".$endpointUrl,
            "HttpMethod: ".$method,
            "X-CLIENT-SECRET: ".config('snap_api_client_secret'),
            "X-TIMESTAMP: ".$timestamp
        ]);

        $header   = explode(' ',$request['headers'][0]);
        $header_code = $header[1];
        $response = json_decode($request['content']);

        if($header_code != 200)
        {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'state' => 'service token',
                'body' => $body,
                'data' => $response
            ]);
            die();
        }

        return $response;
    }

    function createVa($body)
    {
        $datetime = new DateTime();
        $timestamp    = $datetime->format(DateTime::ATOM);
        $endpointUrl  = '/api/v1.0/transfer-va/create-va';
        $signature    = $this->createSignature($timestamp);
        if(!$signature)
        {
            return [
                'success' => false,
                'data' => 'signature error'
            ];
        }
        $signature = $signature->signature;

        $bearer       = $this->getBearer($timestamp, $signature);
        if(!$bearer)
        {
            return [
                'success' => false,
                'data' => 'bearer error'
            ];
        }
        $bearer = $bearer->accessToken;

        $serviceToken = $this->createServiceToken($timestamp, $bearer, $body, 'POST', $endpointUrl);
        if(!$serviceToken)
        {
            return [
                'success' => false,
                'data' => 'service token'
            ];
        }
        $serviceToken = $serviceToken->signature;

        $apiEndpointUrl = config('snap_api_base_url') . '/v1' . $endpointUrl;
        $request = simple_curl($apiEndpointUrl,'POST',$body,[
            "Authorization: Bearer ".$bearer,
            "CHANNEL-ID: ".config('snap_va_channel_id'),
            "Content-Type: application/json",
            "X-EXTERNAL-ID: ".config('snap_external_id'),
            "X-PARTNER-ID: ".config('snap_api_client_key'),
            "X-SIGNATURE: ".$serviceToken,
            "X-TIMESTAMP: ".$timestamp
        ]);

        $header   = explode(' ',$request['headers'][0]);
        $header_code = $header[1];
        $response = json_decode($request['content']);

        if($header_code != 200)
        {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'data' => $response
            ]);
            die();
        }

        return [
            'success' => true,
            'data' => $response
        ];
    }
}