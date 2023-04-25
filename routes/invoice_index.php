<?php
$services = $GLOBALS['database']->select('service','service_id',[
    'AND'=>[
        'customer_id'=>$_SESSION['customer_id'],
        'complete_date[!]'=>null,
    ],
    'ORDER'=>['start_date'=>'DESC'],
]);

$invoices = [];

foreach ($services as $key => $service_id) {
    $invoice_id = $GLOBALS['database']->get('invoice','invoice_id',[
        'service_id'=>$service_id,
    ]);
    $invoice = $GLOBALS['database']->get('service_invoice','*',[
        'invoice_id'=>$invoice_id,
    ]);
    $invoice['total_payable'] = convertPenceToPounds(subtractDiscount($invoice['total_payable'],$invoice['discount_percentage']));
    $invoice['issue_date'] = date('jS \o\f M Y',strtotime($invoice['issue_date']));
    $invoice['due_date'] = date('jS \o\f M Y',strtotime($invoice['due_date']));

    if(!$invoice['paid_date']){
        $invoice['status'] = 'Due';
    }else{
        $invoice['status'] = 'Paid';
    }

    //echo var_dump($invoice);
    array_push($invoices,$invoice);
}
echo $GLOBALS['twig']->render('invoice_index.twig',[
    'customer'=>$_SESSION['customer'],
    'invoices'=>$invoices,
]);
?>