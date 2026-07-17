<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Services\AdminApplicationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ApplicationManagementController extends Controller
{
    public function __construct(private readonly AdminApplicationService $adminApplicationService)
    {
    }

    public function index(Request $request): View
    {
        $filters = [
            'search' => $request->string('search')->toString(),
            'status' => $request->string('status')->toString(),
            'sort' => $request->string('sort')->toString(),
        ];

        $data = $this->adminApplicationService->getListingData($filters);

        return view('admin.applications.index', [
            'applications' => $data['applications'],
            'stats' => $data['stats'],
            'filters' => $filters,
        ]);
    }

    public function show(JobApplication $jobApplication): View
    {
        $application = $this->adminApplicationService->getApplicationDetails($jobApplication);

        return view('admin.applications.show', compact('application'));
    }

    public function previewResume(JobApplication $jobApplication)
    {
        $resume = $jobApplication->resume;

        if (! $resume || ! $resume->file_path || ! Storage::disk('public')->exists($resume->file_path)) {
            abort(404);
        }

        return response()->file(
            Storage::disk('public')->path($resume->file_path),
            ['Content-Disposition' => 'inline; filename="' . ($resume->file_name ?: basename($resume->file_path)) . '"']
        );
    }

    public function downloadResume(JobApplication $jobApplication)
    {
        $resume = $jobApplication->resume;

        if (! $resume || ! $resume->file_path || ! Storage::disk('public')->exists($resume->file_path)) {
            abort(404);
        }

        return response()->download(
            Storage::disk('public')->path($resume->file_path),
            $resume->file_name ?: basename($resume->file_path)
        );
    }
}
