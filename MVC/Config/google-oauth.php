<?php
$localPath = __DIR__ . '/google-oauth.local.php';
$local = is_file($localPath) ? require $localPath : [];
if (!is_array($local)) $local = [];
$appBaseUrl = rtrim((string)(getenv('APP_BASE_URL') ?: ''), '/');
if ($appBaseUrl === '' && !empty($_SERVER['HTTP_HOST'])) {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $appBaseUrl = $scheme . '://' . $_SERVER['HTTP_HOST'] . '/Baitaplon';
}
$defaultRedirectUri = ($appBaseUrl !== '' ? $appBaseUrl : 'http://localhost/Baitaplon') . '/store.php?url=CustomerAuth/GoogleCallback';
return [
    'client_id' => getenv('WINMART_GOOGLE_CLIENT_ID') ?: (string)($local['client_id'] ?? ''),
    'client_secret' => getenv('WINMART_GOOGLE_CLIENT_SECRET') ?: (string)($local['client_secret'] ?? ''),
    'redirect_uri' => getenv('WINMART_GOOGLE_REDIRECT_URI') ?: (string)($local['redirect_uri'] ?? $defaultRedirectUri)
];
