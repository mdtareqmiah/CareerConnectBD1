<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInterviewInvitationRequest;
use App\Http\Requests\UpdateInterviewInvitationRequest;
use App\Models\InterviewInvitation;
use App\Models\Job;
use App\Models\User;
use App\Services\MailService;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InterviewInvitationController extends Controller
{
    public function __construct(
        private readonly MailService $mailService,
        private readonly NotificationService $notificationService,
    ) {
    }

    public function index(Request $request): View
    {
        $query = InterviewInvitation::query()->with(['candidate', 'job', 'employer']);

        if ($request->user()?->role?->slug === 'employer') {
            $query->forEmployer($request->user());
        } elseif ($request->user()?->role?->slug === 'job-seeker') {
            $query->forCandidate($request->user());
        }

        if ($request->filled('search')) {
            $query->whereHas('job', fn ($q) => $q->where('title', 'like', "%{$request->search}%"));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $invitations = $query->latest()->paginate(10)->appends($request->query());

        return view('interview-invitations.index', compact('invitations'));
    }

    public function create(): View
    {
        $candidates = User::whereHas('role', fn ($q) => $q->where('slug', 'job-seeker'))->get();
        $jobs = Job::where('status', 'published')->get();

        return view('interview-invitations.create', compact('candidates', 'jobs'));
    }

    public function store(StoreInterviewInvitationRequest $request): RedirectResponse
    {
        $invitation = InterviewInvitation::create([
            'candidate_id' => $request->candidate_id,
            'job_id' => $request->job_id,
            'employer_id' => $request->user()->id,
            'subject' => $request->subject,
            'interview_at' => $request->interview_at,
            'meeting_link' => $request->meeting_link,
            'location' => $request->location,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        $candidate = User::findOrFail($request->candidate_id);
        $job = Job::findOrFail($request->job_id);

        $this->mailService->send('interview_invitation', $candidate->email, [
            'subject' => $request->subject,
            'job_title' => $job->title,
            'interview_at' => $invitation->interview_at->format('Y-m-d H:i'),
            'location' => $request->location,
            'meeting_link' => $request->meeting_link,
            'notes' => $request->notes,
            'url' => route('job-seeker.interview-invitations.show', $invitation),
        ], $request->user());

        $this->notificationService->notifyInterviewInvitation($candidate, [
            'title' => 'Interview invitation',
            'message' => 'You received a new interview invitation for '.$job->title,
            'link' => route('job-seeker.interview-invitations.show', $invitation),
            'interview_invitation_id' => $invitation->id,
            'job_id' => $job->id,
            'job_title' => $job->title,
            'employer_id' => $request->user()->id,
            'employer_name' => $request->user()->name,
            'company' => $request->user()->company?->company_name,
            'interview_at' => $invitation->interview_at?->toISOString(),
        ]);

        return redirect()->route('employer.interview-invitations.index')->with('success', 'Interview invitation sent.');
    }

    public function show(InterviewInvitation $invitation): View
    {
        $invitation->load(['candidate', 'job', 'employer']);

        return view('interview-invitations.show', compact('invitation'));
    }

    public function update(UpdateInterviewInvitationRequest $request, InterviewInvitation $invitation): RedirectResponse
    {
        $invitation->update([
            'subject' => $request->subject,
            'interview_at' => $request->interview_at,
            'meeting_link' => $request->meeting_link,
            'location' => $request->location,
            'notes' => $request->notes,
            'status' => $request->input('status', $invitation->status),
        ]);

        return redirect()->back()->with('success', 'Invitation updated.');
    }

    public function respond(Request $request, InterviewInvitation $invitation): RedirectResponse
    {
        $this->authorize('respond', $invitation);

        $response = $request->input('response', 'accepted');

        if (! in_array($response, ['accepted', 'declined'], true)) {
            $response = 'accepted';
        }

        $previousStatus = $invitation->status;

        $invitation->update([
            'status' => $response,
            'responded_at' => now(),
        ]);

        if ($previousStatus !== $response) {
            $employer = $invitation->employer()->with('company')->first();
            $candidateName = $invitation->candidate()->value('name') ?? 'Candidate';
            $jobTitle = $invitation->job()->value('title') ?? 'the interview job';

            if ($employer) {
                $this->notificationService->notifySystem($employer, [
                'title' => 'Interview response received',
                    'message' => $candidateName.' '.match ($response) {
                    'accepted' => 'accepted the interview invitation.',
                    'declined' => 'declined the interview invitation.',
                    default => 'responded to the interview invitation.',
                },
                'link' => route('employer.interview-invitations.show', $invitation),
                'interview_invitation_id' => $invitation->id,
                'job_id' => $invitation->job_id,
                'job_title' => $jobTitle,
                'company' => $employer->company?->company_name,
                'candidate_id' => $invitation->candidate_id,
                'candidate_name' => $candidateName,
                'response' => $response,
                ]);
            }
        }

        return redirect()->route('job-seeker.interview-invitations.index')->with('success', 'Response recorded.');
    }

    public function destroy(InterviewInvitation $invitation): RedirectResponse
    {
        $invitation->delete();

        return redirect()->back()->with('success', 'Invitation cancelled.');
    }
}
