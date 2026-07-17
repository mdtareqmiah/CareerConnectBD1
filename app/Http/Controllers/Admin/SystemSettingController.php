<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSystemSettingsRequest;
use App\Services\AdminSettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SystemSettingController extends Controller
{
    public function __construct(private readonly AdminSettingService $adminSettingService)
    {
    }

    public function index(): View
    {
        $settings = $this->adminSettingService->getSettings();

        return view('admin.settings.index', compact('settings'));
    }

    public function update(UpdateSystemSettingsRequest $request): RedirectResponse
    {
        $this->adminSettingService->updateSettings($request->validated());

        return redirect()->route('admin.settings.index')->with('success', 'Settings updated successfully.');
    }
}
