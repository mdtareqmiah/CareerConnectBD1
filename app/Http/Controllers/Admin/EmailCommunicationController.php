<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailLog;
use App\Services\MailService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailCommunicationController extends Controller
{
    public function __construct(private readonly MailService $mailService)
    {
    }

    public function index(Request $request): View
    {
        $query = EmailLog::query()->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(fn ($q) => $q->where('recipient', 'like', "%{$search}%")
                ->orWhere('subject', 'like', "%{$search}%")
                ->orWhere('type', 'like', "%{$search}%"));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $logs = $query->paginate(15)->appends($request->query());

        return view('admin.email-communication.index', compact('logs'));
    }

    public function resend(EmailLog $emailLog)
    {
        $this->mailService->resendFailed($emailLog);

        return redirect()->back()->with('success', 'Email queued for resend.');
    }
}
