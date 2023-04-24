<?php
$submitValid = true;
$errorFields = array();

if(empty($_POST['service_id'])){
    $response->status = 'error';
	$response->message = 'There are problems with your submission. Please check the form and try again.';
	echo json_encode($response);
	exit;
}else{
    $service_id = $_POST['service_id'];
}

if(empty($_POST['description'])){
    $errorFields[] = array(
        'field_name'=>'description',
        'error_message'=>'This is required.',
    );
    $submitValid = false;
}else{
    if(validBasicText($_POST['description'])){
        $description = $_POST['description'];
    }else{
        $errorFields[] = array(
            'field_name'=>'description',
            'error_message'=>'Only letters, numbers, spaces, and hyphens are allowed.',
        );
        $submitValid = false;
    }
}

if(empty($_POST['hours'])){
    $errorFields[] = array(
        'field_name'=>'hours',
        'error_message'=>'This is required.',
    );
    $submitValid = false;
}else{
    if(is_numeric($_POST['hours'])){
        $hours = $_POST['hours'];
    }else{
        $errorFields[] = array(
            'field_name'=>'hours',
            'error_message'=>'This is invalid. Please enter a valid number.',
        );
        $submitValid = false;
    }
}

//Check that $quantity is at least 1
if($hours < 1){
    $errorFields[] = array(
        'field_name'=>'hours',
        'error_message'=>'This is invalid. Please enter a valid number.',
    );
    $submitValid = false;
}

if(!$submitValid){
	$response->status = 'error';
	//$response->message = 'There are problems with your submission. Please check the form and try again.';
	$response->errorFields = $errorFields;
	echo json_encode($response);
	exit;
}else{
    $database->insert('labour',[
        'service_id'=>$service_id,
        'description'=>$description,
        'hours'=>$hours,
    ]);
    $response->status = 'success';
    $response->successRedirect = '/admin/services/'.$service_id;
    echo json_encode($response);
    exit;
}
?>