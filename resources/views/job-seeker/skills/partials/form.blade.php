@props(['action', 'method' => 'POST', 'skill' => null, 'submitLabel' => 'Save Skill'])

<form action="{{ $action }}" method="POST" class="row g-3">
    @csrf

    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="col-12">
        <label for="skill_name" class="form-label">Skill Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('skill_name') is-invalid @enderror" id="skill_name" name="skill_name" value="{{ old('skill_name', $skill?->skill_name) }}" required>
        @error('skill_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="proficiency_level" class="form-label">Proficiency Level <span class="text-danger">*</span></label>
        <select class="form-select @error('proficiency_level') is-invalid @enderror" id="proficiency_level" name="proficiency_level" required>
            <option value="">Select proficiency</option>
            @foreach (['Beginner', 'Intermediate', 'Advanced', 'Expert'] as $level)
                <option value="{{ $level }}" {{ old('proficiency_level', $skill?->proficiency_level) === $level ? 'selected' : '' }}>{{ $level }}</option>
            @endforeach
        </select>
        @error('proficiency_level')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="years_of_experience" class="form-label">Years of Experience</label>
        <input type="number" step="0.5" min="0" max="60" class="form-control @error('years_of_experience') is-invalid @enderror" id="years_of_experience" name="years_of_experience" value="{{ old('years_of_experience', $skill?->years_of_experience) }}">
        @error('years_of_experience')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="notes" class="form-label">Notes</label>
        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="4">{{ old('notes', $skill?->notes) }}</textarea>
        @error('notes')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12 d-flex justify-content-between align-items-center">
        <a href="{{ route('job-seeker.skills.index') }}" class="btn btn-outline-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
    </div>
</form>
