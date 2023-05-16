<?php

$registration = $_POST['reg'];


$url = 'https://driver-vehicle-licensing.api.gov.uk/vehicle-enquiry/v1/vehicles';
$headers = [
    'x-api-key: v540xKCqCV4hBAv363yIa5Sd72HPLo223uhu1pkR',
    'Content-Type: application/json'
];
$data = [
    'registrationNumber' => $registration 
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$curl_response = curl_exec($ch);

if ($curl_response === false) {
    $response->status = 'error';
    $response->message = 'Error: ' . curl_error($ch);
    curl_close($ch);
    echo json_encode($response);
    exit;
} else {
    $curl_data = json_decode($curl_response);

    $response->status = 'success';
    $response->vehicle_make = $curl_data->make;
    curl_close($ch);
    echo json_encode($response);
    exit;
}

?>