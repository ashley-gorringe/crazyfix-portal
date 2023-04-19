<?php
$submitValid = true;
$errorFields = array();

if(empty($_POST['email'])){
    $errorFields[] = array(
        'field_name'=>'email',
        'error_message'=>'This is required.',
    );
    $submitValid = false;
}else{
    if(validEmail($_POST['email'])){
        $email = $_POST['email'];
    }else{
        $errorFields[] = array(
            'field_name'=>'email',
            'error_message'=>'This is invalid. Please enter a correct email address.',
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
    $count = $database->count('customer',[
        'email_address'=>$_POST['email'],
    ]);

    if($count < 1){
        $response->status = 'success';
	    $response->successRedirect = '/sign-up?email='.urlencode($_POST['email']);
	    echo json_encode($response);
	    exit;
    }else{
        $response->status = 'success';
	    $response->successCallback = 'signInForm';
	    $response->successCallbackParams = $_POST['email'];
	    echo json_encode($response);
	    exit;
    }
}
?>