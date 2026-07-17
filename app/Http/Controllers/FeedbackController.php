<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFeedbackRequest;
use App\Models\Feedback;
use App\Services\FeedbackService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FeedbackController extends Controller
{
    public function __construct(private readonly FeedbackService $feedbackService)
    {
    }

    public function index(): View
    {
        $this->authorize('viewAny', Feedback::class);

        return view('feedback.index', [
            'feedbackItems' => $this->feedbackService->paginateForUser(auth()->user()),
            'types' => Feedback::TYPES,
            'statuses' => Feedback::STATUSES,
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Feedback::class);

        return view('feedback.create', [
            'types' => Feedback::TYPES,
        ]);
    }

    public function store(StoreFeedbackRequest $request): RedirectResponse
    {
        $this->authorize('create', Feedback::class);

        $feedback = $this->feedbackService->create($request->user(), $request->validated());

        return redirect()->route('feedback.show', $feedback)->with('success', 'Feedback submitted successfully.');
    }

    public function show(Feedback $feedback): View
    {
        $this->authorize('view', $feedback);

        return view('feedback.show', [
            'feedback' => $feedback,
        ]);
    }
}
