<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateFeedbackStatusRequest;
use App\Models\Feedback;
use App\Services\FeedbackService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FeedbackManagementController extends Controller
{
    public function __construct(private readonly FeedbackService $feedbackService)
    {
    }

    public function show(Feedback $feedback): View
    {
        $this->authorize('view', $feedback);

        return view('admin.communications.feedback-show', [
            'feedback' => $feedback->load(['user', 'assignedAdmin']),
            'statuses' => Feedback::STATUSES,
        ]);
    }

    public function updateStatus(UpdateFeedbackStatusRequest $request, Feedback $feedback): RedirectResponse
    {
        $this->authorize('update', $feedback);

        $this->feedbackService->updateStatus($feedback->load('user'), $request->validated()['status'], $request->user());

        return redirect()->back()->with('success', 'Feedback status updated successfully.');
    }

    public function destroy(Feedback $feedback): RedirectResponse
    {
        $this->authorize('delete', $feedback);

        $this->feedbackService->delete($feedback);

        return redirect()->route('admin.communications.index', ['tab' => 'feedback'])->with('success', 'Feedback deleted successfully.');
    }
}
