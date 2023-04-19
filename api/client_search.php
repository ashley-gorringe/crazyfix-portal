<?php
header('Content-Type: application/json');
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$query = $_GET['query'];

$clients = $GLOBALS['database']->select('client','*',[
    'AND'=>[
        'team_id'=>$_SESSION['team_id'],
        'client_name[~]'=>$query,
    ],
]);
$response = (object)[];
$response->status = 'success';
$response->query = $query;
$response->clients = $clients;
echo json_encode($response);
exit;
?>