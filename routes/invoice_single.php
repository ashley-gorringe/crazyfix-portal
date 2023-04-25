<?php

$invoice = $GLOBALS['database']->get('service_invoice','*',[
    'invoice_id'=>$invoice_id,
]);
$invoice['total_items'] = convertPenceToPounds($invoice['total_items']);
$invoice['total_labour'] = convertPenceToPounds($invoice['total_labour']);
$invoice['total_vat'] = convertPenceToPounds($invoice['total_vat']);

//$invoice['total_payable'] = convertPenceToPounds(subtractDiscount($invoice['total_payable'],$invoice['discount_percentage']));
$invoice['discount_amount'] = convertPenceToPounds(getPercentage($invoice['total_payable'],$invoice['discount_percentage']));
$invoice['total_payable'] = convertPenceToPounds(subtractDiscount($invoice['total_payable'],$invoice['discount_percentage']));

$invoice['issue_date'] = date('jS \o\f M Y',strtotime($invoice['issue_date']));
$invoice['due_date'] = date('jS \o\f M Y',strtotime($invoice['due_date']));
if($invoice['paid_date']){
    $invoice['paid_date'] = date('jS \o\f M Y',strtotime($invoice['paid_date']));
}

$invoice_items = $GLOBALS['database']->select('service_invoice_items','*',[
    'invoice_id'=>$invoice_id,
]);
foreach($invoice_items as $key=>$item){
    $invoice_items[$key]['item_sub_total'] = convertPenceToPounds($item['item_sub_total']);
}

$invoice_labour = $GLOBALS['database']->select('service_invoice_labour','*',[
    'invoice_id'=>$invoice_id,
]);

echo $GLOBALS['twig']->render('invoice_single.twig',[
    'customer'=>$_SESSION['customer'],
    'invoice'=>$invoice,
    'invoice_items'=>$invoice_items,
    'invoice_labour'=>$invoice_labour,
]);
?>