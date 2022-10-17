<?php

define("KEY_TOKEN", "APR.wqc-354*");
define("MONEDA", "$");

session_start();

$num_cart = 0;
if(isset($_SESSION['carrito']['computadores'])){
    $num_cart = count($_SESSION['carrito']['computadores']);
}

?>