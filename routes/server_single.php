<?php
$server = $GLOBALS['database']->get('server','*',['server_slug'=>$slug]);
$server['site_count'] = $GLOBALS['database']->count('site',['server_id'=>$server['server_id']]);

echo $GLOBALS['twig']->render('server_single.twig',[
    'user'=>$_SESSION['user'],
    'team'=>$_SESSION['team'],
    'server'=>$server,
]);
?>