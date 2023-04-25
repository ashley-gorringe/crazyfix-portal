<?php

$service = $GLOBALS['database']->get('service','*',[
    'service_id'=>$service_id,
    'ORDER'=>['start_date'=>'DESC'],
]);
$service['start_date'] = date('jS \o\f M Y',strtotime($service['start_date']));


$customer = $GLOBALS['database']->get('customer','*',[
    'customer_id'=>$service['customer_id'],
]);

$vehicle = $GLOBALS['database']->get('vehicle','*',[
    'vehicle_id'=>$service['vehicle_id'],
]);

$items = $GLOBALS['database']->select('item',['item_id','name','price']);
foreach($items as $key=>$item){
    $items[$key]['price'] = convertPenceToPounds($item['price']);
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

if($service['complete_date']){
    $service['complete_date'] = date('jS \o\f M Y',strtotime($service['complete_date']));
    $invoice_id = $GLOBALS['database']->get('invoice','invoice_id',[
        'service_id'=>$service_id,
    ]);

    $invoice = $GLOBALS['database']->get('service_invoice','*',[
        'invoice_id'=>$invoice_id,
    ]);
    $invoice['total_items'] = convertPenceToPounds($invoice['total_items']);
    $invoice['total_labour'] = convertPenceToPounds($invoice['total_labour']);
    $invoice['total_vat'] = convertPenceToPounds($invoice['total_vat']);

    //$invoice['total_payable'] = convertPenceToPounds(subtractDiscount($invoice['total_payable'],$invoice['discount_percentage']));
    $invoice['discount_amount'] = convertPenceToPounds(getPercentage($invoice['total_payable'],$invoice['discount_percentage']));
    $invoice['total_payable'] = convertPenceToPounds(subtractDiscount($invoice['total_payable'],$invoice['discount_percentage']));

    $invoice_items = $GLOBALS['database']->select('service_invoice_items','*',[
        'invoice_id'=>$invoice_id,
    ]);
    foreach($invoice_items as $key=>$item){
        $invoice_items[$key]['item_sub_total'] = convertPenceToPounds($item['item_sub_total']);
    }

    $invoice_labour = $GLOBALS['database']->select('service_invoice_labour','*',[
        'invoice_id'=>$invoice_id,
    ]);

    echo $GLOBALS['twig']->render('admin_service_single_invoice.twig',[
        'invoice'=>$invoice,
        'invoice_items'=>$invoice_items,
        'invoice_labour'=>$invoice_labour,
    ]);
}else{
    echo $GLOBALS['twig']->render('admin_service_single.twig',[
        'customer'=>$_SESSION['customer'],
        'service'=>$service,
        'customer'=>$customer,
        'vehicle'=>$vehicle,
        'service_items'=>$service_items,
        'service_labour'=>$service_labour,
        'items'=>$items,
        'today_date'=>date('Y-m-d'),
    ]);
}
?>