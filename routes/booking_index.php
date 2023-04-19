<?php

echo $GLOBALS['twig']->render('booking_index.twig',[
    'customer'=>$_SESSION['customer'],
]);
?>