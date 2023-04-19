<?php
$GLOBALS['database']->delete('user_token',['user_id'=>$_SESSION['user_id']]);
$response->status = 'success';
echo json_encode($response);
exit;
?>