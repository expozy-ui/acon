<?php

//Origin
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

$allowed_origins = [
	'https://expozy.com',
    'https://admin.expozy.com',
    'https://devadmin.expozy.com'
];

if (in_array($origin, $allowed_origins)) {
    header("Access-Control-Allow-Origin: $origin");
}

header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");


if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit;
}
//End Origin

define("_VALID_PHP", '1');
require_once '../core/autoload.php';



//get token
if(!isset($_POST['token'])){
	die('Token Error');
}

$token = $_POST['token'];
$route = $_POST['route']??'';



//include required files
define( "_VALID_PHP", true);
$BASEPATH = str_replace("pages/login.php", "", realpath(__FILE__));
define("BASEPATH", $BASEPATH);

require_once '../core/config.php';


$headers = array(
    'Content-Type: application/json',
	'authentication: basic '.SAAS_KEY,
);


//verify token
$res = $user->loginByToken($token);

echo"<script>
	localStorage.clear();
	localStorage.setItem('token', '{$token}');
	document.location.href='/".$route."';	
	</script>";

?>



