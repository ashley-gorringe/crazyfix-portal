<?php
session_unset();
session_destroy();
setcookie('login_token', NULL, time() - (86400 * 365), "/");
$response->status = 'success';
echo json_encode($response);
exit;
?>