<?php

return [
    // Profile / management
    'title' => 'Two-Factor Authentication',
    'intro' => 'Add an extra layer of security: after your password, logging in also requires a one-time code from an authenticator app on your phone.',
    'status_disabled' => 'Two-factor authentication is currently disabled.',
    'status_enabled' => 'Two-factor authentication is enabled on your account.',
    'enable_button' => 'Enable 2FA',
    'disable_button' => 'Disable 2FA',
    'disable_password_label' => 'Confirm your password to disable',

    // Enrolment
    'setup_title' => 'Set up your authenticator app',
    'setup_steps' => 'Scan the QR code with Google Authenticator, Authy, or Microsoft Authenticator — or enter the key manually — then type the 6-digit code to confirm.',
    'manual_key' => 'Or enter this key manually',
    'confirm_label' => '6-digit code from the app',
    'confirm_button' => 'Confirm & enable',
    'cancel_setup' => 'Cancel setup',

    // Recovery codes
    'recovery_title' => 'Recovery codes',
    'recovery_intro' => 'Store these one-time codes somewhere safe. Each lets you sign in once if you lose access to your authenticator app.',
    'regenerate_button' => 'Regenerate recovery codes',

    // Login challenge
    'challenge_title' => 'Two-Factor Verification',
    'challenge_subtitle' => 'Enter the 6-digit code from your authenticator app to continue.',
    'code_label' => 'Authentication code',
    'recovery_label' => 'Recovery code',
    'verify_button' => 'Verify',
    'use_recovery' => 'Use a recovery code instead',
    'use_code' => 'Use an authenticator code instead',
    'back_to_login' => 'Back to login',

    // Messages
    'enabled' => 'Two-factor authentication has been enabled.',
    'disabled' => 'Two-factor authentication has been disabled.',
    'invalid_code' => 'The provided code is invalid.',

    // Admin reset (recovering a locked-out user)
    'admin_reset_hint' => 'If this user has lost both their authenticator device and recovery codes, reset their two-factor setup so they can log in with just their password and enrol again.',
    'admin_reset_button' => 'Reset two-factor for this user',
    'admin_reset_confirm' => 'Reset two-factor authentication for this user? They will be able to log in with only their password until they set it up again.',
    'admin_reset_done' => "The user's two-factor authentication has been reset.",
];
