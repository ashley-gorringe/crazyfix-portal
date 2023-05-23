<?php

if(empty($_POST['reg'])){
    $errorFields[] = array(
        'field_name'=>'registration',
        'error_message'=>'This is required.',
    );
    $submitValid = false;
    $response->status = 'error';
	$response->errorFields = $errorFields;
	echo json_encode($response);
	exit;
}else{
    if(validUKVehicleReg($_POST['reg'])){
        $registration = $_POST['reg'];
    }else{
        $errorFields[] = array(
            'field_name'=>'registration',
            'error_message'=>"This is invalid. Please enter a valid UK vehicle registration. Don't include spaces.",
        );
        $submitValid = false;
        $response->status = 'error';
        $response->errorFields = $errorFields;
        echo json_encode($response);
        exit;
    }
}


$url = 'https://driver-vehicle-licensing.api.gov.uk/vehicle-enquiry/v1/vehicles';
$headers = [
    'x-api-key: '.$_ENV['DVLA_API_KEY'],
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