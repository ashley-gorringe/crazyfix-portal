<?php

echo $GLOBALS['twig']->render('vehicle_index.twig',[
    'customer'=>$_SESSION['customer'],
]);
?>