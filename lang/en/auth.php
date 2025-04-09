<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'failed' => 'These credentials do not match our records.',
    'password' => 'The provided password is incorrect.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',
    'login' => [
        'title' => 'Login',
        'description' => 'Please login to continue.',
        'email' => 'Email',
        'password' => 'Password',
        'hint' => 'Password must be at least :min characters long.',
        'remember_me' => 'Remember Me',
        'forgot_password' => 'Forgot Your Password?',
        'login_button' => 'Login',
        'login_with' => 'Login with :provider',
        'or' => 'or',
        'no_account' => 'Don\'t have an account?',
        'register_here' => 'Register here',
        'login_failed' => 'Login failed. Please check your credentials and try again.',
        'login_success' => 'Login successful. Welcome back!',
    ],
    'register' => [
        'title' => 'Register',
        'description' => 'Please register to create a new account.',
        'name' => 'Name',
        'email' => 'Email',
        'password' => 'Password',
        'confirm_password' => 'Confirm Password',
        'register_button' => 'Register',
        'already_have_account' => 'Already have an account?',
        'login_here' => 'Login here',
        'registration_success' => 'Registration successful. Please check your email to verify your account.',
        'registration_failed' => 'Registration failed. Please try again.',
        'email_taken' => 'The email address is already taken.',
        'password_mismatch' => 'The passwords do not match.',
    ],
    'reset_password' => [
        'title' => 'Reset Password',
        'email' => 'Email Address',
        'send_reset_link' => 'Send Password Reset Link',
    ],
    'verify_email' => [
        'title' => 'Verify Your Email Address',
        'verification_link_sent' => 'A fresh verification link has been sent to your email address.',
        'check_email' => 'Before proceeding, please check your email for a verification link.',
        'if_not_received' => 'If you did not receive the email',
        'click_here' => 'click here to request another',
        'resend_verification' => 'Resend Verification Email',
        'verification_required' => 'Email verification is required to access this application.',
        'verification_success' => 'Email verification successful. You can now log in.',
        'verification_failed' => 'Email verification failed. Please try again.',
        'verification_link' => 'Verification Link',
        'verification_code' => 'Verification Code',
        'verify_button' => 'Verify Email Address',
        'verification_code_sent' => 'A verification code has been sent to your email address.',
        'enter_verification_code' => 'Please enter the verification code sent to your email address.',
    ],
    'password_reset' => [
        'title' => 'Reset Password',
        'email' => 'Email Address',
        'send_link' => 'Send Password Reset Link',
        'reset_password' => 'Reset Password',
    ],
    'two_factor' => [
        'title' => 'Two-Factor Authentication',
        'enable' => 'Enable Two-Factor Authentication',
        'disable' => 'Disable Two-Factor Authentication',
        'enabled' => 'Two-Factor Authentication is enabled.',
        'disabled' => 'Two-Factor Authentication is disabled.',
        'backup_codes' => 'Backup Codes',
        'backup_codes_info' => 'Store these codes in a safe place. They can be used to access your account if you lose your authentication device.',
    ],
    'social_login' => [
        'title' => 'Login with :provider',
        'login_button' => 'Login with :provider',
        'register_button' => 'Register with :provider',
    ],
    'email_verification' => [
        'title' => 'Email Verification',
        'verification_link_sent' => 'A new verification link has been sent to your email address.',
        'check_email' => 'Please check your email for a verification link.',
        'if_not_received' => 'If you did not receive the email',
        'click_here' => 'click here to request another',
    ],
    'password_confirmation' => [
        'title' => 'Confirm Password',
        'password' => 'Password',
        'confirm_button' => 'Confirm Password',
    ],
    'logout' => [
        'title' => 'Logout',
        'logout_button' => 'Logout',
    ],
    'account' => [
        'title' => 'Account',
        'profile' => 'Profile',
        'settings' => 'Settings',
        'security' => 'Security',
        'delete_account' => 'Delete Account',
    ],
    'delete_account' => [
        'title' => 'Delete Account',
        'confirm' => 'Are you sure you want to delete your account? This action cannot be undone.',
        'delete_button' => 'Delete Account',
    ],
    'password_strength' => [
        'weak' => 'Weak',
        'medium' => 'Medium',
        'strong' => 'Strong',
    ],
    'password_policy' => [
        'length' => 'Password must be at least :length characters long.',
        'uppercase' => 'Password must contain at least one uppercase letter.',
        'lowercase' => 'Password must contain at least one lowercase letter.',
        'number' => 'Password must contain at least one number.',
        'special_character' => 'Password must contain at least one special character.',
    ],
    'login_attempts' => [
        'title' => 'Login Attempts',
        'attempts' => 'You have made :attempts login attempts.',
        'last_attempt' => 'Your last login attempt was at :time.',
    ],
    'account_locked' => [
        'title' => 'Account Locked',
        'message' => 'Your account has been locked due to too many failed login attempts. Please try again later.',
    ],
    'password_reset_success' => [
        'title' => 'Password Reset Successful',
        'message' => 'Your password has been reset successfully. You can now log in with your new password.',
    ],
    'password_reset_failed' => [
        'title' => 'Password Reset Failed',
        'message' => 'There was an error resetting your password. Please try again.',
    ],
    'email_verification_success' => [
        'title' => 'Email Verification Successful',
        'message' => 'Your email address has been verified successfully. You can now log in.',
    ],
    'email_verification_failed' => [
        'title' => 'Email Verification Failed',
        'message' => 'There was an error verifying your email address. Please try again.',
    ],
    'two_factor_enabled' => [
        'title' => 'Two-Factor Authentication Enabled',
        'message' => 'Two-Factor Authentication has been enabled for your account.',
    ],
    'two_factor_disabled' => [
        'title' => 'Two-Factor Authentication Disabled',
        'message' => 'Two-Factor Authentication has been disabled for your account.',
    ],
];
