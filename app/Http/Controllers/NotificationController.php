<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function __construct(private readonly NotificationService $notificationService)
    {
    }

    public function index(Request $request): View
    {
        $this->authorize('viewAny', DatabaseNotification::class);

        $filter = $request->query('filter', 'all');
        $notifications = $this->notificationService->getForUser($request->user(), $filter);

        return view('notifications.index', compact('notifications', 'filter'));
    }

    public function read(Request $request, DatabaseNotification $notification): RedirectResponse
    {
        $this->authorize('update', $notification);

        $this->notificationService->markAsRead($notification);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Notification marked as read.');
    }

    public function readAll(Request $request): RedirectResponse
    {
        $this->authorize('viewAny', DatabaseNotification::class);

        $this->notificationService->markAllAsRead($request->user());

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'All notifications marked as read.');
    }

    public function destroy(Request $request, DatabaseNotification $notification): RedirectResponse
    {
        $this->authorize('delete', $notification);

        $this->notificationService->delete($notification);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Notification deleted.');
    }
}
