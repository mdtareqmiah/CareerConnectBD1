@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h4 mb-1">Edit Company</h1>
        <div class="text-muted">{{ $company->company_name }}</div>
    </div>
    <a href="{{ route('admin.companies.show', $company) }}" class="btn btn-outline-primary">Back</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.companies.update', $company) }}" method="POST" class="row g-3">
            @csrf
            @method('PATCH')

            <div class="col-md-6">
                <label class="form-label">Company Name</label>
                <input type="text" name="company_name" value="{{ old('company_name', $company->company_name) }}" class="form-control @error('company_name') is-invalid @enderror" required>
                @error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Industry</label>
                <input type="text" name="industry" value="{{ old('industry', $company->industry) }}" class="form-control @error('industry') is-invalid @enderror" required>
                @error('industry')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Company Size</label>
                <input type="text" name="company_size" value="{{ old('company_size', $company->company_size) }}" class="form-control @error('company_size') is-invalid @enderror" required>
                @error('company_size')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Founded Year</label>
                <input type="number" name="founded_year" value="{{ old('founded_year', $company->founded_year) }}" class="form-control @error('founded_year') is-invalid @enderror" required>
                @error('founded_year')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Website</label>
                <input type="url" name="website" value="{{ old('website', $company->website) }}" class="form-control @error('website') is-invalid @enderror">
                @error('website')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email', $company->email) }}" class="form-control @error('email') is-invalid @enderror" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $company->phone) }}" class="form-control @error('phone') is-invalid @enderror" required>
                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-12">
                <label class="form-label">Address</label>
                <input type="text" name="address" value="{{ old('address', $company->address) }}" class="form-control @error('address') is-invalid @enderror" required>
                @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">City</label>
                <input type="text" name="city" value="{{ old('city', $company->city) }}" class="form-control @error('city') is-invalid @enderror" required>
                @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Country</label>
                <input type="text" name="country" value="{{ old('country', $company->country) }}" class="form-control @error('country') is-invalid @enderror" required>
                @error('country')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-12">
                <label class="form-label">Description</label>
                <textarea name="company_description" rows="4" class="form-control @error('company_description') is-invalid @enderror">{{ old('company_description', $company->company_description) }}</textarea>
                @error('company_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12 d-flex justify-content-end gap-2">
                <a href="{{ route('admin.companies.show', $company) }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Company</button>
            </div>
        </form>
    </div>
</div>
@endsection
