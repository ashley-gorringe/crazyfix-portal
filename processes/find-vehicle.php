<?php

$registration = $_POST['reg'];

$response->status = 'error';
$response->message = 'Not yet implemented: '.$registration;
echo json_encode($response);
exit;

?>