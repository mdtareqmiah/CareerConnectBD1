@component('mail::message')
# Application received

Your application for {{ $data['job_title'] ?? 'the selected role' }} has been received.

We will review it shortly and contact you with the next steps.

@component('mail::button', ['url' => $data['url'] ?? url('/job-seeker/applications')])
View Application
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
