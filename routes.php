<?php
use Steampixel\Route;

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
	require_once dirname($_SERVER['DOCUMENT_ROOT']).'/routes/account_security.php';
});

Route::add('/bookings', function() {
	checkAuth();
	require_once dirname($_SERVER['DOCUMENT_ROOT']).'/routes/booking_index.php';
});

Route::add('/vehicles', function() {
	checkAuth();
	require_once dirname($_SERVER['DOCUMENT_ROOT']).'/routes/vehicle_index.php';
});

Route::add('/invoices', function() {
	checkAuth();
	require_once dirname($_SERVER['DOCUMENT_ROOT']).'/routes/invoice_index.php';
});
Route::add('/invoices/([0-9a-zA-Z]*)', function($invoice_id) {
	require_once dirname($_SERVER['DOCUMENT_ROOT']).'/routes/invoice_single.php';
});

Route::add('/sign-out', function() {
	resetAuth();
});

Route::add('/admin', function() {
	header('Location: /admin/services');
});
Route::add('/admin/services', function() {
	require_once dirname($_SERVER['DOCUMENT_ROOT']).'/routes/admin_service_index.php';
});
Route::add('/admin/services/([0-9a-zA-Z]*)', function($service_id) {
	require_once dirname($_SERVER['DOCUMENT_ROOT']).'/routes/admin_service_single.php';
});

Route::pathNotFound(function() {
  header('HTTP/1.0 404 Not Found');
  echo $GLOBALS['twig']->render('404.twig');
});

Route::run('/');
?>
