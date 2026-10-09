<?php
// Chess Library System — configuration
return [
    'app_name'  => 'Chess Library System',
    'db_path'   => __DIR__ . '/data/library.sqlite',   // ideally move outside the web root

    // If true, new accounts must click an emailed link before signing in
    // (mirrors the Supabase "confirm your email" flow).
    'require_email_confirmation' => false,

    // Email. With mail_to_log = true, emails are written to data/mail.log
    // instead of being sent (handy for local development).
    'mail_from'   => 'no-reply@example.com',
    'mail_to_log' => true,

    // Google OAuth (https://console.cloud.google.com → Credentials → OAuth client ID).
    // Authorized redirect URI must be: https://YOUR-DOMAIN/google.php
    // Leave client_id empty to hide the Google button.
    'google' => [
        'client_id'     => '',
        'client_secret' => '',
    ],
];
