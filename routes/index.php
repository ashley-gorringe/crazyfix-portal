<?php
$time = date("H");
if($time < "12"){
    $time = 'morning';
}elseif($time >= "12" && $time < "17"){
    $time = 'afternoon';
}elseif($time >= "17"){
    $time = 'evening';
}

echo $GLOBALS['twig']->render('index.twig',[
    'customer'=>$_SESSION['customer'],
    'time'=>$time,
]);

?>