<?php
define( "_VALID_PHP", true);

require_once( "../core/autoload.php");


$row = array('lang' => $lang);
if(isset($_GET["order_id"])){
	$row['order_id'] = (int)$_GET["order_id"];
}
if(isset($_GET["deposit_id"])){
	$row['deposit_id'] = (int)$_GET["deposit_id"];
}

$result = Api::data($row)->post()->payment_confirm();

if($result['redirect'] === 1){
    header('Location: '.$result['redirect']);
}

