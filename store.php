<?php
session_start();
if (!isset($_SESSION['shop_cart']) || !is_array($_SESSION['shop_cart'])) $_SESSION['shop_cart']=[];
if (empty($_SESSION['shop_csrf'])) $_SESSION['shop_csrf']=bin2hex(random_bytes(32));
$url=isset($_GET['url'])?trim((string)$_GET['url'],'/'):'Storefront/Get_data';
$controllerName=explode('/',$url)[0];
$publicControllers=['Storefront','Cart','CustomerAuth','CustomerOrders'];
if (!in_array($controllerName,$publicControllers,true)) { http_response_code(404); exit('Page not found.'); }
$_GET['url']=$url;
include_once './MVC/bridge.php';
new app();
?>
