@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h4 mb-1">System Settings</h1>
        <p class="text-muted mb-0">Configure core platform settings</p>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.settings.update') }}" class="row g-3">
            @csrf
            @method('PATCH')

            <div class="col-md-6">
                <label class="form-label">Site Name</label>
                <input type="text" name="site_name" value="{{ old('site_name', $settings['site_name']) }}" class="form-control @error('site_name') is-invalid @enderror" required>
                @error('site_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Site Email</label>
                <input type="email" name="site_email" value="{{ old('site_email', $settings['site_email']) }}" class="form-control @error('site_email') is-invalid @enderror" required>
                @error('site_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Contact Email</label>
                <input type="email" name="contact_email" value="{{ old('contact_email', $settings['contact_email']) }}" class="form-control @error('contact_email') is-invalid @enderror" required>
                @error('contact_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Support Email</label>
                <input type="email" name="support_email" value="{{ old('support_email', $settings['support_email']) }}" class="form-control @error('support_email') is-invalid @enderror" required>
                @error('support_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Default Timezone</label>
                <select name="default_timezone" class="form-select @error('default_timezone') is-invalid @enderror" required>
                    @foreach(timezone_identifiers_list() as $timezone)
                        <option value="{{ $timezone }}" @selected(old('default_timezone', $settings['default_timezone']) === $timezone)>{{ $timezone }}</option>
                    @endforeach
                </select>
                @error('default_timezone')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Pagination Size</label>
                <input type="number" min="5" max="100" name="pagination_size" value="{{ old('pagination_size', $settings['pagination_size']) }}" class="form-control @error('pagination_size') is-invalid @enderror" required>
                @error('pagination_size')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="maintenance_mode" name="maintenance_mode" value="1" @checked((string) old('maintenance_mode', $settings['maintenance_mode']) === '1')>
                    <label class="form-check-label" for="maintenance_mode">Maintenance Mode</label>
                </div>
            </div>

            <div class="col-12 d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Save Settings</button>
            </div>
        </form>
    </div>
</div>
@endsection
