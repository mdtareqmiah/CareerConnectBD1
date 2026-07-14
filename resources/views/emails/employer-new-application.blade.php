@component('mail::message')
# New application received

A candidate has applied for {{ $data['job_title'] ?? 'your job listing' }}.

Please review the application details and respond promptly.

@component('mail::button', ['url' => $data['url'] ?? url('/employer/applications')])
Review Applications
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
