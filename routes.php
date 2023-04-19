<?php
use Steampixel\Route;
/*
$GLOBALS['database']->insert('server',[
	'team_id'=>2,
	'server_name'=>'Test',
]);
insertDBSlug('server');
*/

/*
$domain = 'test earh aehaehaehe rah';
die(var_dump(validateDomain($domain)));
*/
//$RecordsNS = $DNS->getRecords('serverbook.app', 'NS')[0];
//$RecordsA = $DNS->getRecords('serverbook.app', 'A')[0];
//die(var_dump($RecordsNS->target()));
//die(var_dump($RecordsA->ip()));


//die(var_dump(getDNSRecords('splycd.co.uk')));

Route::add('/welcome', function() {
	checkSignedIn();
	echo $GLOBALS['twig']->render('welcome.twig');
});
Route::add('/sign-up', function() {
	checkSignedIn();
	$email = urldecode($_GET['email']);
	echo $GLOBALS['twig']->render('sign-up.twig',['email'=>$email]);
});

Route::add('/', function() {
	header('Location: /bookings');
});
Route::add('/account', function() {
	header('Location: /account/general');
});
Route::add('/account/general', function() {
	checkAuth();
	echo $GLOBALS['twig']->render('account_general.twig',['customer'=>$_SESSION['customer']]);
});
Route::add('/account/security', function() {
	checkAuth();
	$sessions = $GLOBALS['database']->select('user_token','*',[
		'user_id'=>$_SESSION['user_id'],
		'ORDER'=>['created_datetime'=>'DESC'],
	]);
	foreach ($sessions as $key => $session){
		if($session['token'] === $_SESSION['user_token']){
			$sessions[$key]['current_device'] = true;
		}else{
			$sessions[$key]['current_device'] = false;
		}
		$sessions[$key]['created_datetime'] = date('jS \o\f M Y - G:i:s',strtotime($session['created_datetime']));
		$sessions[$key]['latest_activity'] = timeago(strtotime($session['latest_activity']));
	}
	echo $GLOBALS['twig']->render('account_security.twig',['user'=>$_SESSION['user'],'team'=>$_SESSION['team'],'sessions'=>$sessions]);
});
Route::add('/account/notifications', function() {
	checkAuth();
	echo $GLOBALS['twig']->render('account_notifications.twig',['user'=>$_SESSION['user'],'team'=>$_SESSION['team']]);
});

Route::add('/bookings', function() {
	checkAuth();
	require_once dirname($_SERVER['DOCUMENT_ROOT']).'/routes/booking_index.php';
});
Route::add('/bookings/([0-9a-zA-Z]*)', function($slug) {
	checkAuth();
	require_once dirname($_SERVER['DOCUMENT_ROOT']).'/routes/booking_single.php';
});

Route::add('/vehicles', function() {
	checkAuth();
	require_once dirname($_SERVER['DOCUMENT_ROOT']).'/routes/vehicle_index.php';
});

Route::add('/sign-out', function() {
	resetAuth();
});


Route::add('/api/server-list.json', function() {
	checkAuth();
	require_once dirname($_SERVER['DOCUMENT_ROOT']).'/api/server_list.php';
});
Route::add('/api/site-list.json', function() {
	checkAuth();
	require_once dirname($_SERVER['DOCUMENT_ROOT']).'/api/site_list.php';
});
Route::add('/api/client-search.json', function() {
	checkAuth();
	require_once dirname($_SERVER['DOCUMENT_ROOT']).'/api/client_search.php';
});

Route::pathNotFound(function() {
  header('HTTP/1.0 404 Not Found');
  //echo $GLOBALS['twig']->render('404.twig');
});

Route::run('/');
?>
