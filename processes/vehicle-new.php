<?php
$submitValid = true;
$errorFields = array();

if(empty($_POST['registration'])){
    $errorFields[] = array(
        'field_name'=>'registration',
        'error_message'=>'This is required.',
    );
    $submitValid = false;
}else{
    if(validUKVehicleReg($_POST['registration'])){
        $registration = $_POST['registration'];
    }else{
        $errorFields[] = array(
            'field_name'=>'registration',
            'error_message'=>"This is invalid. Please enter a valid UK vehicle registration. Don't include spaces.",
        );
        $submitValid = false;
    }
}

if(empty($_POST['manufacturer'])){
    $errorFields[] = array(
        'field_name'=>'manufacturer',
        'error_message'=>'This is required.',
    );
    $submitValid = false;
}else{
    if(validAlphNum($_POST['manufacturer'])){
        $manufacturer = $_POST['manufacturer'];
    }else{
        $errorFields[] = array(
            'field_name'=>'manufacturer',
            'error_message'=>'This is invalid. Please enter a valid Manufacturer name.',
        );
        $submitValid = false;
    }
}

if(empty($_POST['model'])){
    $errorFields[] = array(
        'field_name'=>'model',
        'error_message'=>'This is required.',
    );
    $submitValid = false;
}else{
    if(validAlphNum($_POST['model'])){
        $model = $_POST['model'];
    }else{
        $errorFields[] = array(
            'field_name'=>'model',
            'error_message'=>'This is invalid. Please enter a valid Model name.',
        );
        $submitValid = false;
    }
}

if(!$submitValid){
	$response->status = 'error';
	//$response->message = 'There are problems with your submission. Please check the form and try again.';
	$response->errorFields = $errorFields;
	echo json_encode($response);
	exit;
}else{
    $database->insert('vehicle',[
        'registration'=>$registration,
        'make'=>$manufacturer,
        'model'=>$model,
    ]);
    $vehicle_id = $database->id();

    $database->insert('customer_vehicle',[
        'customer_id'=>$_SESSION['customer_id'],
        'vehicle_id'=>$vehicle_id,
    ]);

    $response->status = 'success';
	$response->successRedirect = '/vehicles/';
	echo json_encode($response);
	exit;
}
?>