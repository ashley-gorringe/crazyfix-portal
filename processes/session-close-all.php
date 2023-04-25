<?php
$GLOBALS['database']->delete('login_token',['customer_id'=>$_SESSION['customer_id']]);
$response->status = 'success';
echo json_encode($response);
exit;
?>
