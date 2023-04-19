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

if(empty($_POST['user_name'])){
    $errorFields[] = array(
        'field_name'=>'user_name',
        'error_message'=>'This is required.',
    );
    $submitValid = false;
}else{
    if(validAlphabet($_POST['user_name'])){
        $user_name = $_POST['user_name'];
    }else{
        $errorFields[] = array(
            'field_name'=>'user_name',
            'error_message'=>'This is invalid. Please enter a correct email address.',
        );
        $submitValid = false;
    }
}

if(empty($_POST['theme'])){
    $errorFields[] = array(
        'field_name'=>'theme',
        'error_message'=>'This is required.',
    );
    $submitValid = false;
}else{
    if($_POST['theme'] === 'system'){
        $theme = 0;
    }elseif($_POST['theme'] === 'light'){
        $theme = 1;
    }elseif($_POST['theme'] === 'dark'){
        $theme = 2;
    }else{
        $errorFields[] = array(
            'field_name'=>'theme',
            'error_message'=>'This is invalid. Please try again.',
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
    $database->update('user',[
        'name'=>$_POST['user_name'],
        'email'=>$_POST['email'],
        'theme'=>$theme,
    ],[
        'user_id'=>$_SESSION['user_id'],
    ]);

    $response->status = 'success';
	$response->successRedirect = '/account/general';
	echo json_encode($response);
	exit;
}
?>