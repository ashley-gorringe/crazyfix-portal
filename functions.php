<?php

function convertDecimalToMD5($int){
    return md5((string)$int);
}


function timeago($time){
    $origin = new DateTime(date("Y-m-d H:i:s",$time));
    //$target = new DateTime('now', $TIME_ZONE);
    $target = new DateTime('now');
    $interval = $origin->diff($target);
    if($interval->y!=0){
        $text = $interval->y." year".($interval->y!=1?"s":"")." ago";
    } elseif($interval->m!=0){
        $text = $interval->m." month".($interval->m!=1?"s":"")." ago";
    } elseif($interval->d!=0){
        $text = $interval->d." day".($interval->d!=1?"s":"")." ago";
    } elseif($interval->h!=0){
        $text = $interval->h." hour".($interval->h!=1?"s":"")." ago";
    } else {
        $text = "Just now";
    }
    return $text;
}

function insertDBSlug($type){
	$last_id = $GLOBALS['database']->id();
	switch($type){
		case 'server':
			$slug_value = md5('server-'.$last_id);
			$table = 'server';
			$column_slug = 'server_slug';
			$column_id = 'server_id';
			break;
		case 'client':
			$slug_value = md5('client-'.$last_id);
			$table = 'client';
			$column_slug = 'client_slug';
			$column_id = 'client_id';
			break;
		case 'site':
			$slug_value = md5('site-'.$last_id);
			$table = 'site';
			$column_slug = 'site_slug';
			$column_id = 'site_id';
			break;
		case 'domain':
			$slug_value = md5('domain-'.$last_id);
			$table = 'domain';
			$column_slug = 'domain_slug';
			$column_id = 'domain_id';
			break;
	}
	$GLOBALS['database']->update($table,[$column_slug=>$slug_value,],[$column_id=>$last_id,]);

	return $slug_value;
}


function resetAuth(){
	session_unset();
    session_destroy();
	setcookie('login_token', NULL, time() - (86400 * 365), "/");
	header('Location: /welcome');
}
function checkAuth(){
	if($_SESSION['login_token']){
		//Session Token is present
		$token_count = $GLOBALS['database']->count('login_token',[
			'token'=>$_SESSION['login_token'],
		]);
		if($token_count > 0){//Check if user token exists
			//Token exists
			$_SESSION['customer_id'] = $GLOBALS['database']->get('login_token','customer_id',['token'=>$_SESSION['login_token']]);
			$_SESSION['customer'] = $GLOBALS['database']->get('customer','*',['customer_id'=>$_SESSION['customer_id']]);

			$GLOBALS['database']->update('login_token',[
				'latest_activity'=>date('Y-m-d H:i:s'),
			],[
				'token'=>$_SESSION['login_token'],
			]);

			return true;
		}else{
			//Token does NOT exist
			resetAuth();
		}
		
	}else{
		//Session Token is NOT present
		resetAuth();
	}
}
function checkSignedIn(){
	if($_SESSION['login_token']){
		//Session Token is present
		$token_count = $GLOBALS['database']->count('login_token',[
			'token'=>$_SESSION['login_token'],
		]);
		if($token_count > 0){//Check if user token exists
			//Token exists
			header('Location: /');
		}
		
	}
}
function checkLevelRoute($level){
	
}
function checkLevelProcess($level){
	
}

function validEmail($string){
	if(filter_var($string, FILTER_VALIDATE_EMAIL)){
  		return true;
	}else{
		return false;
	}
}
function validAlphNum($string){
	if(preg_match('/^[a-z0-9 \-]+$/i', $string)){
  		return true;
	}else{
		return false;
	}
}
function validAlphabet($string){
	if(preg_match('/^[a-z \-]+$/i', $string)){
  		return true;
	}else{
		return false;
	}
}
function validBasicText($string){
	if(preg_match('/[^a-z0-9 \-]/i',$string)){
  		return false;
	}else{
		return true;
	}
}
function validIPAddress($string){
	if(filter_var($string, FILTER_VALIDATE_IP)){
		return true;
	}else{
		return false;
	}
}
function validPhone($string){
	if(preg_match('/^0+(\d){10}$/', $string)){
		return true;
	}else{
		return false;
	}
}
function validUKPostcode($string){
	if(preg_match('/^([A-Z]{1,2}[0-9]{1,2}[A-Z]? [0-9][A-Z]{2})$/', $string)){
		return true;
	}else{
		return false;
	}
}

function validateURL($string){
	$regex = '_^(?:(?:https?|ftp)://)(?:\S+(?::\S*)?@|\d{1,3}(?:\.\d{1,3}){3}|(?:[a-z\d\.-]+\.)+(?:[a-z]{2,}))(?::\d+)?(?:[/?#]\S*)?$_iuS';
	
	if(preg_match($regex, $string)){
		return true;
	}else{
		return false;
	}
}
/*
function validateDomain($domainName){
    return (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $domainName) //valid chars check
            && preg_match("/^.{1,253}$/", $domainName) //overall length check
            && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $domainName)   ); //length of each label
}
*/

// Function to validate domain name. Also makes sure that at least one dot is present in the domain name.
function validateDomain($domainName){
	if (strpos($domainName, '.') !== false){
		// Dot is present in the domain
		return (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $domainName) //valid chars check
            && preg_match("/^.{1,253}$/", $domainName) //overall length check
            && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $domainName)   ); //length of each label
	}else{
		return false;
	}
}

?>
