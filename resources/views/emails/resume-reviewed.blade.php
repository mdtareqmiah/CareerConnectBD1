@component('mail::message')
# Resume reviewed

Your resume has been reviewed by the team.

@component('mail::button', ['url' => $data['url'] ?? url('/job-seeker/resumes')])
View Resume
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
