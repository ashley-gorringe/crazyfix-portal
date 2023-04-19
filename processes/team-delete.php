<?php
$GLOBALS['database']->delete('team',['team_id'=>$_SESSION['team_id']]);
$response->status = 'success';
echo json_encode($response);
exit;
?>