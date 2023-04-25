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

if(empty($_POST['complete_date'])){
    $errorFields[] = array(
        'field_name'=>'complete_date',
        'error_message'=>'This is required.',
    );
    $submitValid = false;
}else{
    $complete_date = $_POST['complete_date'];
}

if(empty($_POST['discount_percentage'])){
    $errorFields[] = array(
        'field_name'=>'discount_percentage',
        'error_message'=>'This is required.',
    );
    $submitValid = false;
}else{
    //check that quantity is a number
    if(is_numeric($_POST['discount_percentage'])){
        $discount_percentage = $_POST['discount_percentage'];
    }else{
        $errorFields[] = array(
            'field_name'=>'discount_percentage',
            'error_message'=>'This is invalid. Please enter a valid percentage.',
        );
        $submitValid = false;
    }
}

//Check that discount percentage is between 0 and 100
if($discount_percentage < 0 || $discount_percentage > 100){
    $errorFields[] = array(
        'field_name'=>'discount_percentage',
        'error_message'=>'This is invalid. Please enter a valid percentage.',
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

    $due_date = date('Y-m-d', strtotime($complete_date. ' + 14 days'));

    $database->insert('invoice',[
        'service_id'=>$service_id,
        'issue_date'=>$complete_date,
        'due_date'=>$due_date,
        'discount_percentage'=>$discount_percentage,
    ]);
    $database->update('service',[
        'complete_date'=>$complete_date,
    ],[
        'service_id'=>$service_id,
    ]);
    $response->status = 'success';
    $response->successRedirect = '/admin/services/'.$service_id;
    echo json_encode($response);
    exit;
}
?>