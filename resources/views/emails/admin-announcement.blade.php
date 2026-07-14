@component('mail::message')
# New announcement

{{ $data['message'] ?? 'A new announcement is available.' }}

@component('mail::button', ['url' => $data['url'] ?? url('/notifications')])
View Details
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
