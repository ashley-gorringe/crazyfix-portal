<?php
$site_slug = $_POST['site_slug'];

if(empty($site_slug)){
    $response->status = 'error';
	$response->message = 'An error has occured. Please reload the page and try again.';
	echo json_encode($response);
	exit;
}

$count = $GLOBALS['database']->count('site',[
    'AND'=>[
        'site_slug'=>$site_slug,
        'team_id'=>$_SESSION['team_id'],
    ],
]);
if($count < 1){
    $response->status = 'error';
	$response->message = 'An error has occured. Please reload the page and try again.';
	echo json_encode($response);
	exit;
}else{
    $GLOBALS['database']->delete('site',['site_slug'=>$site_slug]);
    $response->status = 'success';
    echo json_encode($response);
    exit;
}
?>