@component('mail::message')
# Application status updated

The status of your application for {{ $data['job_title'] ?? 'the selected role' }} is now {{ $data['status'] ?? 'updated' }}.

@component('mail::button', ['url' => $data['url'] ?? url('/job-seeker/applications')])
View Update
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
