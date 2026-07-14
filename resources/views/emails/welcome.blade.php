@component('mail::message')
# Welcome to CareerConnectBD

Thank you for joining our platform. We are excited to help you find the right opportunities.

@component('mail::button', ['url' => url('/')])
Get Started
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
