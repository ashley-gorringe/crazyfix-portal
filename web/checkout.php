<?php
//Sets up Stripe Checkout integration.
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/execute.php';

\Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET']);

header('Content-Type: application/json');

$YOUR_DOMAIN = $_ENV['APP_URL'];

$invoice_id = $_GET['invoice_id'];
$service_id = $GLOBALS['database']->get('invoice','service_id',[
    'invoice_id'=>$invoice_id,
]);
$service_items = $GLOBALS['database']->select('service_item',[
    '[>]item'=>['item_id'=>'item_id'],
],'*',[
    'service_id'=>$service_id,
]);
$service_labour = $GLOBALS['database']->select('labour','*',[
    'service_id'=>$service_id,
]);

$line_items = array();
foreach ($service_items as $item) {
	$line_items[]= array(
		'price'=>$item['stripe_price_id'],
		'quantity'=>$item['quantity']
	);
}

$total_hours = $GLOBALS['database']->sum('labour','hours',[
    'service_id'=>$service_id,
]);

$line_items[]= array(
    'price'=>'price_1N3d0yC8tQOBWP0Z2Ao9OUIZ',
    'quantity'=>$total_hours,
);

$checkout_session = \Stripe\Checkout\Session::create([
    'line_items' => $line_items,
    'mode' => 'payment',
      'success_url' => $YOUR_DOMAIN . 'invoices/1',
      'cancel_url' => $YOUR_DOMAIN . 'invoices/1',
  ]);
  
  header("HTTP/1.1 303 See Other");
  header("Location: " . $checkout_session->url);

?>