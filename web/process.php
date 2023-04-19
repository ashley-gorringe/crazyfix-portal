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
		case 'welcome':
			include dirname($_SERVER['DOCUMENT_ROOT']).'/processes/welcome.php';
			break;
        case 'sign-up':
            include dirname($_SERVER['DOCUMENT_ROOT']).'/processes/sign-up.php';
            break;
		case 'sign-in':
			include dirname($_SERVER['DOCUMENT_ROOT']).'/processes/sign-in.php';
			break;
		case 'sign-out':
			include dirname($_SERVER['DOCUMENT_ROOT']).'/processes/sign-out.php';
			break;
		case 'session-close':
			include dirname($_SERVER['DOCUMENT_ROOT']).'/processes/session-close.php';
			break;
		case 'session-close-all':
			include dirname($_SERVER['DOCUMENT_ROOT']).'/processes/session-close-all.php';
			break;

		case 'account-general':
			include dirname($_SERVER['DOCUMENT_ROOT']).'/processes/account-general.php';
			break;
		case 'account-security':
			include dirname($_SERVER['DOCUMENT_ROOT']).'/processes/account-security.php';
			break;
		case 'account-delete':
			include dirname($_SERVER['DOCUMENT_ROOT']).'/processes/account-delete.php';
			break;
		
		case 'settings-general':
			checkLevelProcess(1);
			include dirname($_SERVER['DOCUMENT_ROOT']).'/processes/settings-general.php';
			break;
		case 'team-delete':
			checkLevelProcess(0);
			include dirname($_SERVER['DOCUMENT_ROOT']).'/processes/team-delete.php';
			break;

		case 'member-level':
			checkLevelProcess(1);
			include dirname($_SERVER['DOCUMENT_ROOT']).'/processes/member-level.php';
			break;
		case 'member-transfer':
			checkLevelProcess(0);
			include dirname($_SERVER['DOCUMENT_ROOT']).'/processes/member-transfer.php';
			break;
		case 'member-remove':
			checkLevelProcess(1);
			include dirname($_SERVER['DOCUMENT_ROOT']).'/processes/member-remove.php';
			break;

		case 'server-new':
			checkLevelProcess(1);
			include dirname($_SERVER['DOCUMENT_ROOT']).'/processes/server-new.php';
			break;
		case 'server-delete':
			checkLevelProcess(1);
			include dirname($_SERVER['DOCUMENT_ROOT']).'/processes/server-delete.php';
			break;
		case 'server-edit':
			checkLevelProcess(1);
			include dirname($_SERVER['DOCUMENT_ROOT']).'/processes/server-edit.php';
			break;

		case 'site-new':
			checkLevelProcess(2);
			include dirname($_SERVER['DOCUMENT_ROOT']).'/processes/site-new.php';
			break;
		case 'site-delete':
			checkLevelProcess(2);
			include dirname($_SERVER['DOCUMENT_ROOT']).'/processes/site-delete.php';
			break;

		case 'domain-new':
			checkLevelProcess(2);
			include dirname($_SERVER['DOCUMENT_ROOT']).'/processes/domain-new.php';
			break;
		case 'domain-remove':
			checkLevelProcess(2);
			include dirname($_SERVER['DOCUMENT_ROOT']).'/processes/domain-remove.php';
			break;

		case 'refresh-dns-site':
			checkLevelProcess(2);
			include dirname($_SERVER['DOCUMENT_ROOT']).'/processes/refresh-dns-site.php';
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
