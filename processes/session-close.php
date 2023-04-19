<?php
$token = $_POST['token'];

if(empty($token)){
    $response->status = 'error';
	$response->message = 'An error has occured. Please reload the page and try again.';
	echo json_encode($response);
	exit;
}

$count = $GLOBALS['database']->count('user_token',[
    'AND'=>[
        'token'=>$token,
        'user_id'=>$_SESSION['user_id'],
    ],
]);
if($count < 1){
    $response->status = 'error';
	$response->message = 'An error has occured. Please reload the page and try again.';
	echo json_encode($response);
	exit;
}

if($token === $_SESSION['user_token']){
    $GLOBALS['database']->delete('user_token',['token'=>$token]);
    $response->status = 'success';
    $response->current = true;
    echo json_encode($response);
    exit;
}else{
    $GLOBALS['database']->delete('user_token',['token'=>$token]);
    $response->status = 'success';
    $response->current = false;
    echo json_encode($response);
    exit;
}
?>