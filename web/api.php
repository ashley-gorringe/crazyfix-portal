<?php
//Universal process/response file. This file sets up a JSON response to give data back to all AJAX interactions that the visitor/user can make from the site frontend.

require_once dirname($_SERVER['DOCUMENT_ROOT']).'/execute.php';
header('Content-Type: application/json');
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$response = (object)[];

if(empty($_POST['action'])){
	$response->status = 'error';
    $response->message = 'Critical Error: No process action provided.';
    echo json_encode($response);
    exit;
}else{
	switch($_POST['action']){
		case 'server-list':
			include dirname($_SERVER['DOCUMENT_ROOT']).'/api/server-list.php';
			break;

		default:
		$response->status = 'error';
		$response->message = 'Action is not valid.';
		$response->post = $_POST;
		echo json_encode($response);
		exit;
		break;
	}
}
?>
