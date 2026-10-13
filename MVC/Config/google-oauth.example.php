<?php
// Copy this file to google-oauth.local.php and fill in credentials from Google Cloud.
// Keep google-oauth.local.php private; it is excluded from Git.
return [
    'client_id' => 'YOUR_GOOGLE_CLIENT_ID.apps.googleusercontent.com',
    'client_secret' => 'YOUR_GOOGLE_CLIENT_SECRET',
    'redirect_uri' => 'http://localhost/Baitaplon/store.php?url=CustomerAuth/GoogleCallback'
];
