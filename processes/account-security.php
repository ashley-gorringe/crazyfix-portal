<?php
$submitValid = true;
$errorFields = array();

if(empty($_POST['password'])){
    $errorFields[] = array(
        'field_name'=>'password',
        'error_message'=>'This is required.',
    );
    $submitValid = false;
}
if(empty($_POST['re_password'])){
    $errorFields[] = array(
        'field_name'=>'re_password',
        'error_message'=>'This is required.',
    );
    $submitValid = false;
}else{
    if($_POST['password'] !== $_POST['re_password']){
        $errorFields[] = array(
            'field_name'=>'re_password',
            'error_message'=>"This doesn't match your password.",
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

    $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $database->update('customer',[
        'password'=>$password_hash,
    ],[
        'customer_id'=>$_SESSION['cusomter_id'],
    ]);

    $response->status = 'success';
	$response->successRedirect = '/account/security';
	echo json_encode($response);
	exit;
}
?>