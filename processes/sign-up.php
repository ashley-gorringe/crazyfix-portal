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

if(empty($_POST['phone'])){
    $errorFields[] = array(
        'field_name'=>'phone',
        'error_message'=>'This is required.',
    );
    $submitValid = false;
}else{
    if(validPhone($_POST['phone'])){
        $phone = $_POST['phone'];
    }else{
        $errorFields[] = array(
            'field_name'=>'phone',
            'error_message'=>'This is invalid. Please enter a UK phone number beginning with 0',
        );
        $submitValid = false;
    }
}
if(empty($_POST['title'])){
    $errorFields[] = array(
        'field_name'=>'title',
        'error_message'=>'This is required.',
    );
    $submitValid = false;
}else{
    switch($_POST['title']){
        case 'mr':
            $title = 'Mr';
            break;
        case 'mrs':
            $title = 'Mrs';
            break;
        case 'miss':
            $title = 'Miss';
            break;
        case 'mx':
            $title = 'Mx';
            break;
        case 'dr':
            $title = 'Dr';
            break;
        case 'prof':
            $title = 'Prof';
            break;
        default:
            $errorFields[] = array(
                'field_name'=>'title',
                'error_message'=>'This is invalid. Please select a title.',
            );
            $submitValid = false;
            break;
    }
}
if(empty($_POST['first_name'])){
    $errorFields[] = array(
        'field_name'=>'first_name',
        'error_message'=>'This is required.',
    );
    $submitValid = false;
}else{
    if(validAlphabet($_POST['first_name'])){
        $first_name = $_POST['first_name'];
    }else{
        $errorFields[] = array(
            'field_name'=>'first_name',
            'error_message'=>'This is invalid. Please enter your first name.',
        );
        $submitValid = false;
    }
}

if(empty($_POST['last_name'])){
    $errorFields[] = array(
        'field_name'=>'last_name',
        'error_message'=>'This is required.',
    );
    $submitValid = false;
}else{
    if(validAlphabet($_POST['last_name'])){
        $last_name = $_POST['last_name'];
    }else{
        $errorFields[] = array(
            'field_name'=>'last_name',
            'error_message'=>'This is invalid. Please enter your last name.',
        );
        $submitValid = false;
    }
}

if(empty($_POST['address_line_1'])){
    $errorFields[] = array(
        'field_name'=>'address_line_1',
        'error_message'=>'This is required.',
    );
    $submitValid = false;
}else{
    if(validAlphNum($_POST['address_line_1'])){
        $address_line_1 = $_POST['address_line_1'];
    }else{
        $errorFields[] = array(
            'field_name'=>'address_line_1',
            'error_message'=>'This is invalid. Please enter a valid Address Line.',
        );
        $submitValid = false;
    }
}
if(!empty($_POST['address_line_2'])){
    if(validAlphNum($_POST['address_line_2'])){
        $address_line_2 = $_POST['address_line_2'];
    }else{
        $errorFields[] = array(
            'field_name'=>'address_line_2',
            'error_message'=>'This is invalid. Please enter a valid Address Line.',
        );
        $submitValid = false;
    }
}else{
    $address_line_2 = '';
}

if(empty($_POST['postcode'])){
    $errorFields[] = array(
        'field_name'=>'postcode',
        'error_message'=>'This is required.',
    );
    $submitValid = false;
}else{
    if(validUKPostcode($_POST['postcode'])){
        $postcode = $_POST['postcode'];
    }else{
        $errorFields[] = array(
            'field_name'=>'postcode',
            'error_message'=>'This is invalid. Please enter a valid UK Postcode.',
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
    $email_count = $database->count('customer',[
        'email_address'=>$email,
    ]);

    if($email_count > 0){
        $response->status = 'error';
        $errorFields[] = array(
            'field_name'=>'email',
            'error_message'=>'This email address is already in use.',
        );
        $response->errorFields = $errorFields;
        echo json_encode($response);
        exit;
    }

    $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $avatar_hash = hash('sha256', $email);

    $database->insert('customer',[
        'email_address'=>$email,
        'phone'=>$phone,
        'title'=>$title,
        'first_name'=>$first_name,
        'last_name'=>$last_name,
        'address_line_1'=>$address_line_1,
        'address_line_2'=>$address_line_2,
        'postcode'=>$postcode,
        'password'=>$password_hash,
        'avatar_hash'=>$avatar_hash,
    ]);
    $customer_id = $database->id();

    $login_token = bin2hex(random_bytes(30));
    $device_info = $ua->browser().' - '.$ua->platform();
    $database->insert('login_token',[
        'customer_id'=>$customer_id,
        'token'=>$login_token,
        'device_info'=>$device_info,
    ]);

    $_SESSION['login_token'] = $login_token;
    setcookie('login_token', $login_token, time() + (86400 * 365), "/");

    $response->status = 'success';
	$response->successRedirect = '/';
	echo json_encode($response);
	exit;
}
?>