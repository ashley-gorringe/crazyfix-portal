<?php
$sessions = $GLOBALS['database']->select('login_token','*',[
    'customer_id'=>$_SESSION['customer_id'],
    'ORDER'=>['created_datetime'=>'DESC'],
]);
foreach ($sessions as $key => $session){
    if($session['login_token'] === $_SESSION['customer_token']){
        $sessions[$key]['current_device'] = true;
    }else{
        $sessions[$key]['current_device'] = false;
    }
    $sessions[$key]['created_datetime'] = date('jS \o\f M Y - G:i:s',strtotime($session['created_datetime']));
    $sessions[$key]['latest_activity'] = timeago(strtotime($session['latest_activity']));
}
echo $GLOBALS['twig']->render('account_security.twig',['customer'=>$_SESSION['customer'],'sessions'=>$sessions]);
?>