@component('mail::message')
# Interview Invitation

Hello,

You have been invited to an interview for {{ $data['job_title'] ?? 'your application' }}.

- Interview Date: {{ $data['interview_at'] ?? 'TBD' }}
- Location: {{ $data['location'] ?? 'TBD' }}
- Meeting Link: {{ $data['meeting_link'] ?? 'TBD' }}

{{ $data['notes'] ?? '' }}

@component('mail::button', ['url' => $data['url'] ?? url('/job-seeker/interview-invitations')])
View Invitation
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
