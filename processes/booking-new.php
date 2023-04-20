<?php
$submitValid = true;
$errorFields = array();

if(empty($_POST['vehicle'])){
    $errorFields[] = array(
        'field_name'=>'vehicle',
        'error_message'=>'This is required.',
    );
    $submitValid = false;
}else{
    $vehicleCount = $database->count('customer_vehicle',[
        'AND'=>[
            'customer_id'=>$_SESSION['customer_id'],
            'vehicle_id'=>$_POST['vehicle'],
        ],
    ]);
    if($vehicleCount > 0){
        $vehicle_id = $_POST['vehicle'];
    }else{
        $response->status = 'error';
        $response->message = 'There are problems with your submission. Please check the form and try again.';
        echo json_encode($response);
        exit;
    }
}

if(empty($_POST['service_date'])){
    $errorFields[] = array(
        'field_name'=>'service_date',
        'error_message'=>'This is required.',
    );
    $submitValid = false;
}else{
    $service_date = $_POST['service_date'];
}

if(!$submitValid){
	$response->status = 'error';
	//$response->message = 'There are problems with your submission. Please check the form and try again.';
	$response->errorFields = $errorFields;
	echo json_encode($response);
	exit;
}else{
    $database->insert('service',[
        'start_date'=>$service_date,
        'customer_id'=>$_SESSION['customer_id'],
        'vehicle_id'=>$vehicle_id,
    ]);
    $response->status = 'success';
    $response->successRedirect = '/bookings/';
    echo json_encode($response);
    exit;
}
?>