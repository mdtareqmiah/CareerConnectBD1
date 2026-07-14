@component('mail::message')
# Reset your password

Use the link below to reset your password.

@component('mail::button', ['url' => $data['url'] ?? url('/')])
Reset Password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
