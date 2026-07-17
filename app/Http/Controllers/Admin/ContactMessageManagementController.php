<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdminContactReplyRequest;
use App\Models\ContactMessage;
use App\Services\ContactService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContactMessageManagementController extends Controller
{
    public function __construct(private readonly ContactService $contactService)
    {
    }

    public function updateStatus(Request $request, ContactMessage $contactMessage): RedirectResponse
    {
        $this->authorize('update', $contactMessage);

        $status = (string) $request->validate([
            'status' => ['required', 'in:unread,in_review,replied,closed'],
        ])['status'];

        $this->contactService->updateStatus($contactMessage, $status, $request->user());

        return redirect()->back()->with('success', 'Contact message status updated.');
    }

    public function reply(StoreAdminContactReplyRequest $request, ContactMessage $contactMessage): RedirectResponse
    {
        $this->authorize('update', $contactMessage);

        $validated = $request->validated();

        $this->contactService->reply($contactMessage, $request->user(), $validated['subject'], $validated['message']);

        return redirect()->back()->with('success', 'Reply sent successfully.');
    }

    public function destroy(ContactMessage $contactMessage): RedirectResponse
    {
        $this->authorize('delete', $contactMessage);

        $this->contactService->delete($contactMessage);

        return redirect()->back()->with('success', 'Contact message deleted successfully.');
    }
}
