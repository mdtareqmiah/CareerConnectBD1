@component('mail::message')
# Verify your email address

Please verify your email to activate your account.

@component('mail::button', ['url' => $data['url'] ?? url('/')])
Verify Email
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
