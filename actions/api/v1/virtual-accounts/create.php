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
    
    // $conn->query("INSERT INTO topup (userid,pesanan_nomor,pesanan_total,hp,va,status)VALUES('$userid','$pesanan_nomor','$pesanan_total','$hp','$va','MENUNGGU')");
    // $id = $conn->insert_id;
    // $data = $conn->query("SELECT * FROM topup WHERE id = $id");
    // $data = $data->fetch_object();
    // '{
    //     "partnerServiceId":"", Derivative of X-PARTNER-ID , similar to company code, 8 digit left padding space. partnerServiceId + customerNo or virtualAccountNo
    //     "customerNo":"", Unique number (up to 20 digits). partnerServiceId + customerNo or virtualAccountNo
    //     "virtualAccountNo":"", partnerServiceId (8 digit left padding space) + customerNo (up to 20 digits). partnerServiceId + customerNo or virtualAccountNo
    //     "virtualAccountName":"Jokul Doe", Customer name
    //     "virtualAccountPhone":"6281828384858", Customer phone
    //     "trxId":"abcdefgh1234", Transaction ID in Partner system
    //     "totalAmount":{
    //        "value":"12345678.00",
    //        "currency":"IDR"
    //     },
    //     "billDetails":[
    //        {
    //           "billCode":"01",
    //           "billNo":"123456789012345678",
    //           "billName":"Bill A for Jan",
    //           "billShortName":"Bill A",
    //           "billDescription":{
    //              "english":"Maintenance",
    //              "indonesia":"Pemeliharaan"
    //           },
    //           "billSubCompany":"00001",
    //           "billAmount":{
    //              "value":"12345678.00",
    //              "currency":"IDR"
    //           },
    //           "additionalInfo":{
             
    //           }
    //        }
    //     ],
    //     "freeTexts":[
    //        {
    //           "english":"Free text",
    //           "indonesia":"Tulisan bebas"
    //        }
    //     ],
    //     "virtualAccountTrxType":"1",
    //     "feeAmount":{
    //        "value":"12345678.00",
    //        "currency":"IDR"
    //     },
    //     "expiredDate":"2020-12-31T23:59:59-07:00",
    //     "additionalInfo":{
    //        "deviceId":"12345679237",
    //        "channel":"mobilephone"
    //     }
    // }';
    
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