from pathlib import Path

path = Path('resources/views/employer/applications/show.blade.php')
text = path.read_text(encoding='utf-8')
start = text.index('<<<<<<< HEAD')
end = text.rindex('>>>>>>> develop') + len('>>>>>>> develop')
replacement = '''            <div class="position-sticky" style="top: 1rem;">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h3 class="h6 mb-3">Update Application Status</h3>
                        <form method="POST" action="{{ route('employer.applications.update_status', $jobApplication) }}">
                            @csrf
                            @method('PATCH')

                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select id="status" name="status" class="form-select">
                                    @foreach(\App\Models\JobApplication::statusOptions() as $status => $label)
                                        <option value="{{ $status }}" {{ $jobApplication->status === $status ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Save Status</button>
                        </form>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h3 class="h6 mb-3">Job Information</h3>
                        <div class="mb-3">
                            <div class="fw-semibold">{{ $jobApplication->job->title }}</div>
                            <div class="small text-muted">{{ $jobApplication->job->company->company_name }}</div>
                        </div>
                        <div class="mb-3">
                            <div class="text-muted small">Applied Date</div>
                            <div>{{ optional($jobApplication->applied_at)->format('M d, Y') ?? $jobApplication->created_at->format('M d, Y') }}</div>
                        </div>
                        <div class="mb-3">
                            <div class="text-muted small">Current Status</div>
                            <div>{!! $jobApplication->statusBadge() !!}</div>
                        </div>
                        <div class="mb-3">
                            <div class="text-muted small">Cover Letter</div>
                            <div class="text-break">{{ $jobApplication->cover_letter ?: 'No cover letter provided.' }}</div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h3 class="h6 mb-3">Application Timeline</h3>
                        <div class="timeline">
                            @foreach($timeline as $event)
                                <div class="d-flex mb-3">
                                    <div class="me-3 mt-1">
                                        <span class="badge rounded-circle bg-primary" style="width: 12px; height: 12px;"></span>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $event['label'] }}</div>
                                        <div class="small text-muted">{{ $event['time'] }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h3 class="h6 mb-3">Quick Actions</h3>
                        <div class="d-grid gap-2">
                            @if($jobApplication->resume && $jobApplication->resume->file_type === 'pdf')
                                <a href="{{ route('employer.applications.resume.preview', $jobApplication) }}" target="_blank" class="btn btn-outline-primary">Preview Resume</a>
                            @endif
                            @if($jobApplication->resume)
                                <a href="{{ route('employer.applications.resume.download', $jobApplication) }}" class="btn btn-primary">Download Resume</a>
                            @endif
                            <a href="{{ route('jobs.show', $jobApplication->job) }}" class="btn btn-outline-secondary">View Public Job</a>
                            <a href="{{ route('company.show', $jobApplication->job->company) }}" class="btn btn-outline-secondary">View Company</a>
                            <a href="{{ route('employer.applications.index') }}" class="btn btn-secondary">Back to Applications</a>
                        </div>
                    </div>
                </div>
            </div>'''
path.write_text(text[:start] + replacement + text[end:], encoding='utf-8')
print('Replaced conflict region in show.blade.php')
