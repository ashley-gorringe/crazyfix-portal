<?php
header('Content-Type: application/json');
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$servers = $GLOBALS['database']->select('server','*',[
    'ORDER'=>[
        'server_name'=>'ASC',
    ],
    'team_id'=>$_SESSION['team_id']
]);
foreach ($servers as $key => $server) {
    $servers[$key]['site_count'] = $GLOBALS['database']->count('site',['server_id'=>$server['server_id']]);
}

echo json_encode($servers);
exit;
?>