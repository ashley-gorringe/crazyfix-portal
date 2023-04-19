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
if(empty($_POST['password'])){
    $errorFields[] = array(
        'field_name'=>'password',
        'error_message'=>'This is required.',
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
    $email_count = $database->count('customer',[
        'email_address'=>$email,
    ]);

    if($email_count < 1){
        $response->status = 'error';
        $errorFields[] = array(
            'field_name'=>'email',
            'error_message'=>'This Email Address may be incorrect.',
        );
        $errorFields[] = array(
            'field_name'=>'password',
            'error_message'=>'This Password may be incorrect.',
        );
        $response->errorFields = $errorFields;
        echo json_encode($response);
        exit;
    }

    $customer = $database->get('customer','*',['email_address'=>$email]);

    if(password_verify($_POST['password'],$customer['password'])){
        $device_info = $ua->browser().' - '.$ua->platform();
        //Passwords match
        $login_token = bin2hex(random_bytes(30));
        $database->insert('login_token',[
            'customer_id'=>$customer['customer_id'],
            'token'=>$login_token,
            'device_info'=>$device_info,
        ]);

        $_SESSION['login_token'] = $login_token;
        setcookie('login_token', $login_token, time() + (86400 * 365), "/");

        $response->status = 'success';
        $response->successRedirect = '/';
        echo json_encode($response);
        exit;
    }else{
        $response->status = 'error';
        $errorFields[] = array(
            'field_name'=>'email',
            'error_message'=>'This Email Address may be incorrect.',
        );
        $errorFields[] = array(
            'field_name'=>'password',
            'error_message'=>'This Password may be incorrect.',
        );
        $response->errorFields = $errorFields;
        echo json_encode($response);
        exit;
    }
}
?>