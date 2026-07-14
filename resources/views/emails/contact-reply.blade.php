@component('mail::message')
# Reply to your message

{{ $data['message'] ?? 'We have replied to your inquiry.' }}

@component('mail::button', ['url' => $data['url'] ?? url('/')])
View Message
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
