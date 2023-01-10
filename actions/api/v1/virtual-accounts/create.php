<?php

if(request() != 'POST')
{
    http_response_code(405);
    echo json_encode(['success'=>false,'message'=>'Method not allowed']);
    die();
}

if(!isset($_POST['token']))
{
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'message' => 'Api key is required'
    ]);
    die();
}

if(trim($_POST['token']) != trim(app('cipta_kerja_api_key')))
{
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'message' => 'Api key is not valid'
    ]);
    die();
}

$conn  = conn();
$db    = new Database($conn);

$pesanan_nomor = $_POST['pesanan_nomor'];
$pesanan_total = $_POST['pesanan_total'];
$hp            = $_POST['hp'];
$userid        = $_POST['userid'];
$nama          = $_POST['nama'];
$email         = $_POST['email'];

$checker = $db->single('topup',[
    'pesanan_nomor' => $pesanan_nomor,
    'userid'        => $userid
]);

if(!empty($checker))
{
    echo json_encode([
        'status'=>'success',
        'data'  => $checker
    ]);
    die();
}
else
{
    $va = generateVa();
    $body = '{
        "partnerServiceId":"'.config('snap_partner_service_id').'",
        "customerNo":"'.substr($userid,19).'",
        "virtualAccountNo":"'.config('snap_partner_service_id').$va.'",
        "virtualAccountName":"'.$nama.'",
        "virtualAccountEmail":"'.$nama.'",
        "virtualAccountPhone":"'.$hp.'",
        "trxId":"'.$pesanan_nomor.'",
        "totalAmount":{
           "value":"'.$pesanan_total.'",
           "currency":"IDR"
        }
    }';

    $snap = new Snap;
    $virtualAccount = $snap->createVa($body);
    if($virtualAccount['success'])
    {
        $vaData = $virtualAccount['data']->virtualAccountData;
        $data = $db->insert('topup',[
            'userid' => $userid,
            'pesanan_nomor' => $pesanan_nomor,
            'pesanan_total' => $pesanan_total,
            'hp' => $hp,
            'payment_type' => 'VIRTUAL ACCOUNT',
            'payment_ref' => json_encode($vaData),
            'va' => $va,
            'status' => 'PENDING',
        ]);
    
        echo json_encode([
            'status'=>'success',
            'data'  => $data
        ]);
    }
    else
    {
        http_response_code(400);

        echo json_encode([
            'status'=> 'failed',
            'data'=> $virtualAccount,
        ]);
    }

    die();
}