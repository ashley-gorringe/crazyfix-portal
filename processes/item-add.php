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

if(empty($_POST['item'])){
    $errorFields[] = array(
        'field_name'=>'item',
        'error_message'=>'This is required.',
    );
    $submitValid = false;
}else{
    $item_count = $database->count('item',[
        'item_id'=>$_POST['item'],
    ]);
    if($item_count < 1){
        $errorFields[] = array(
            'field_name'=>'item',
            'error_message'=>'This is invalid. Please select a valid item.',
        );
        $submitValid = false;
    }else{
        $item_id = $_POST['item'];
    }
}

if(empty($_POST['quantity'])){
    $errorFields[] = array(
        'field_name'=>'quantity',
        'error_message'=>'This is required.',
    );
    $submitValid = false;
}else{
    //check that quantity is a number
    if(is_numeric($_POST['quantity'])){
        $quantity = $_POST['quantity'];
    }else{
        $errorFields[] = array(
            'field_name'=>'quantity',
            'error_message'=>'This is invalid. Please enter a valid quantity.',
        );
        $submitValid = false;
    }
}

//Check that $quantity is at least 1
if($quantity < 1){
    $errorFields[] = array(
        'field_name'=>'quantity',
        'error_message'=>'This is invalid. Please enter a valid quantity.',
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
    //Check if item is already added to service
    $item_count = $database->count('service_item',[
        'service_id'=>$service_id,
        'item_id'=>$item_id,
    ]);
    if($item_count > 0){
        //Get existing item
        $existing_item = $database->get('service_item','*',[
            'service_id'=>$service_id,
            'item_id'=>$item_id,
        ]);
        //Update existing item
        $database->update('service_item',[
            'quantity[+]'=>$quantity,
        ],[
            'service_item_id'=>$existing_item['service_item_id'],
        ]);
        $response->status = 'success';
        $response->successRedirect = '/admin/services/'.$service_id;
        echo json_encode($response);
        exit;
    }else{
        //Add new item
        $database->insert('service_item',[
            'service_id'=>$service_id,
            'item_id'=>$item_id,
            'quantity'=>$quantity,
        ]);
        $response->status = 'success';
        $response->successRedirect = '/admin/services/'.$service_id;
        echo json_encode($response);
        exit;
    }
}
?>