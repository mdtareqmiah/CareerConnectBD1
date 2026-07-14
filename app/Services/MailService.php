<?php

namespace App\Services;

use App\Mail\WelcomeEmail;
use App\Mail\VerifyEmail;
use App\Mail\PasswordResetEmail;
use App\Mail\JobApplicationSubmittedMail;
use App\Mail\EmployerNewApplicationMail;
use App\Mail\ApplicationStatusUpdatedMail;
use App\Mail\ResumeReviewedMail;
use App\Mail\InterviewInvitationMail;
use App\Mail\AdminAnnouncementMail;
use App\Mail\ContactReplyMail;
use App\Models\EmailLog;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class MailService
{
    public function send(string $type, string $recipient, array $data = [], ?User $triggeredBy = null): EmailLog
    {
        $mailClass = $this->resolveMailClass($type);
        $subject = $data['subject'] ?? $this->defaultSubject($type);

        $log = EmailLog::create([
            'recipient' => $recipient,
            'subject' => $subject,
            'type' => $type,
            'status' => 'queued',
            'triggered_by' => $triggeredBy?->id,
        ]);

        Mail::to($recipient)->queue(new $mailClass($data, $subject));

        $log->update(['status' => 'sent', 'sent_at' => now()]);

        return $log;
    }

    public function resendFailed(EmailLog $log): EmailLog
    {
        $mailClass = $this->resolveMailClass($log->type);
        $data = ['subject' => $log->subject];

        $log->update(['status' => 'queued', 'failure_message' => null]);
        Mail::to($log->recipient)->queue(new $mailClass($data, $log->subject));
        $log->update(['status' => 'sent', 'sent_at' => now()]);

        return $log;
    }

    protected function resolveMailClass(string $type): string
    {
        return match ($type) {
            'welcome' => WelcomeEmail::class,
            'verify_email' => VerifyEmail::class,
            'password_reset' => PasswordResetEmail::class,
            'job_application_submitted' => JobApplicationSubmittedMail::class,
            'employer_new_application' => EmployerNewApplicationMail::class,
            'application_status_updated' => ApplicationStatusUpdatedMail::class,
            'resume_reviewed' => ResumeReviewedMail::class,
            'interview_invitation' => InterviewInvitationMail::class,
            'admin_announcement' => AdminAnnouncementMail::class,
            'contact_reply' => ContactReplyMail::class,
            'contact_acknowledgement' => ContactReplyMail::class,
            'support_ticket_reply' => ContactReplyMail::class,
            'support_ticket_closed' => ContactReplyMail::class,
            'feedback_reply' => ContactReplyMail::class,
            'admin_reply' => ContactReplyMail::class,
            default => WelcomeEmail::class,
        };
    }

    protected function defaultSubject(string $type): string
    {
        return match ($type) {
            'welcome' => 'Welcome to CareerConnectBD',
            'verify_email' => 'Verify your email address',
            'password_reset' => 'Reset your password',
            'job_application_submitted' => 'Your job application has been received',
            'employer_new_application' => 'You received a new application',
            'application_status_updated' => 'Application status updated',
            'resume_reviewed' => 'Your resume has been reviewed',
            'interview_invitation' => 'Interview invitation',
            'admin_announcement' => 'New announcement',
            'contact_reply' => 'Reply to your message',
            'contact_acknowledgement' => 'We received your message',
            'support_ticket_reply' => 'Support ticket reply',
            'support_ticket_closed' => 'Support ticket closed',
            'feedback_reply' => 'Feedback status update',
            'admin_reply' => 'CareerConnectBD support reply',
            default => 'CareerConnectBD update',
        };
    }
}
