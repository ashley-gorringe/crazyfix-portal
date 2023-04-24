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

$items = $GLOBALS['database']->select('item',['item_id','name','price']);
foreach($items as $key=>$item){
    $items[$key]['price'] = $item['price'] / 100;
}

$service_items = $GLOBALS['database']->select('service_item',[
    '[>]item'=>['item_id'=>'item_id'],
],'*',[
    'service_id'=>$service_id,
]);
foreach($service_items as $key=>$item){
    $service_items[$key]['sub_total'] = $item['sub_total'] / 100;
}

$service_labour = $GLOBALS['database']->select('labour','*',[
    'service_id'=>$service_id,
]);

echo $GLOBALS['twig']->render('admin_service_single.twig',[
    'customer'=>$_SESSION['customer'],
    'service'=>$service,
    'customer'=>$customer,
    'vehicle'=>$vehicle,
    'service_items'=>$service_items,
    'service_labour'=>$service_labour,
    'items'=>$items,
]);
?>