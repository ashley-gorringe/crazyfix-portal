<?php

$service = $GLOBALS['database']->get('service','*',[
    'service_id'=>$service_id,
    'ORDER'=>['start_date'=>'DESC'],
]);
$service['start_date'] = date('jS \o\f M Y',strtotime($service['start_date']));
$service['complete_date'] = date('jS \o\f M Y',strtotime($service['complete_date']));

$customer = $GLOBALS['database']->get('customer','*',[
    'customer_id'=>$service['customer_id'],
]);

$vehicle = $GLOBALS['database']->get('vehicle','*',[
    'vehicle_id'=>$service['vehicle_id'],
]);

echo $GLOBALS['twig']->render('admin_service_single.twig',[
    'customer'=>$_SESSION['customer'],
    'service'=>$service,
    'customer'=>$customer,
    'vehicle'=>$vehicle,
]);
?>