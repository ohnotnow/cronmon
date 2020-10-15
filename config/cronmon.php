<?php

return [
    'email_prefix' => '[CRONMON]',
    'alert_interval' => 60,
    'keep_pings' => 100,
    'fallback_delay' => 24, // hours
    'admin_username' => env('CRONMON_ADMIN_USERNAME'),
    'admin_username_file' => env('CRONMON_ADMIN_USERNAME_FILE'),
    'admin_email' => env('CRONMON_ADMIN_EMAIL'),
    'admin_email_file' => env('CRONMON_ADMIN_EMAIL_FILE'),
    'admin_password' => env('CRONMON_ADMIN_PASSWORD'),
    'admin_password_file' => env('CRONMON_ADMIN_PASSWORD_FILE'),
];
