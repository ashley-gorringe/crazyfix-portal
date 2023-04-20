<?php

$services = $GLOBALS['database']->select('service',[
    '[>]vehicle'=>['service.vehicle_id'=>'vehicle_id'],
    '[>]customer'=>['service.customer_id'=>'customer_id'],
],'*',[
    'ORDER'=>['start_date'=>'DESC'],
]);
foreach ($services as $key => $service) {
    $services[$key]['start_date'] = date('jS \o\f M Y',strtotime($service['start_date']));
    $services[$key]['complete_date'] = date('jS \o\f M Y',strtotime($service['complete_date']));
}

echo $GLOBALS['twig']->render('admin_services_index.twig',[
    'customer'=>$_SESSION['customer'],
    'services'=>$services,
]);
?>