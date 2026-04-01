@component('mail::message')
# Reset Your Password

Hi {{ $userName }},

We received a request to reset the password for your account. Click the button below to reset your password.

@component('mail::button', ['url' => $resetLink])
Reset Password
@endcomponent

This password reset link will expire in 60 minutes.

If you didn't request a password reset, please ignore this email.

Thanks,<br>
{{ config('app.name') }}
@endcomponent