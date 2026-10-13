<?php
session_start();
$requestedRoute = trim((string)($_GET['url'] ?? 'Login/Index'), '/');
$routeParts = explode('/', $requestedRoute);
$requestedController = $routeParts[0] ?? 'Login';
$requestedAction = $routeParts[1] ?? 'Index';
$publicLoginActions = $requestedController === 'Login'
    && in_array($requestedAction, ['Index', 'Authentication', 'Logout'], true);

if (empty($_SESSION['user_login']) && !$publicLoginActions) {
    header('Location: /Baitaplon/Admin');
    exit;
}
require_once "./bridge.php";
$myApp = new App();
?>
