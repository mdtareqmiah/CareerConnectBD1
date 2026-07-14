<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePublicContactRequest;
use App\Models\ContactMessage;
use App\Services\ContactService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function __construct(private readonly ContactService $contactService)
    {
    }

    public function create(): View
    {
        return view('contact.create', [
            'categories' => ContactMessage::CATEGORIES,
        ]);
    }

    public function store(StorePublicContactRequest $request): RedirectResponse
    {
        $this->contactService->create($request->validated());

        return redirect()->route('contact.create')->with('success', 'Your message has been submitted successfully.');
    }
}
