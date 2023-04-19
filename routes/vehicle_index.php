<?php
$vehicles = $GLOBALS['database']->select('customer_vehicle',[
'[>]vehicle'=>['vehicle_id'=>'vehicle_id'],
],'*',['customer_id'=>$_SESSION['customer_id']]);
echo $GLOBALS['twig']->render('vehicle_index.twig',[
    'vehicles'=>$vehicles,
    'customer'=>$_SESSION['customer'],
]);
?>