<?php

$vehicles = $GLOBALS['database']->select('customer_vehicle',[
    '[>]vehicle'=>['vehicle_id'=>'vehicle_id'],
],'*',[
    'customer_id'=>$_SESSION['customer_id'],
]);

$bookings = $GLOBALS['database']->select('service',[
    '[>]vehicle'=>['service.vehicle_id'=>'vehicle_id'],
],'*',[
    'AND'=>[
        'customer_id'=>$_SESSION['customer_id'],
        'complete_date'=>null,
    ],
    'ORDER'=>['start_date'=>'DESC'],
]);
foreach ($bookings as $key => $booking) {
    $bookings[$key]['start_date'] = date('jS \o\f M Y',strtotime($booking['start_date']));
}

echo $GLOBALS['twig']->render('booking_index.twig',[
    'customer'=>$_SESSION['customer'],
    'bookings'=>$bookings,
    'vehicles'=>$vehicles,
]);
?>