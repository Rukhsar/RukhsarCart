<?php

//Include the common file
require_once('common.php');

//Template values
$tpl->set('subtotal', $cart->subtotal());
$tpl->set('shipping_cost', $cart->shipping_cost());
$tpl->set('tax_rate', $cart->tax_rate());
$tpl->set('total', $cart->total());

//Display the template
$tpl->display('view_cart');
?>
