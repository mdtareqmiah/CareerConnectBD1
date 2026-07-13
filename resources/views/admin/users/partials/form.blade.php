@php
    $isEdit = filled($user);
@endphp

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name ?? '') }}" required maxlength="255">
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email ?? '') }}" required maxlength="255">
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Role</label>
        <select name="role_id" class="form-select @error('role_id') is-invalid @enderror" required>
            <option value="">Select Role</option>
            @foreach($roles as $role)
                <option value="{{ $role->id }}" @selected((int) old('role_id', $user->role_id ?? 0) === (int) $role->id)>{{ $role->name }}</option>
            @endforeach
        </select>
        @error('role_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Status</label>
        <select name="is_active" class="form-select @error('is_active') is-invalid @enderror" required>
            <option value="1" @selected((string) old('is_active', isset($user) ? (int) $user->is_active : '1') === '1')>Active</option>
            <option value="0" @selected((string) old('is_active', isset($user) ? (int) $user->is_active : '1') === '0')>Inactive</option>
        </select>
        @error('is_active')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Password {{ $isEdit ? '(Optional)' : '' }}</label>
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" {{ $isEdit ? '' : 'required' }}>
        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Confirm Password</label>
        <input type="password" name="password_confirmation" class="form-control" {{ $isEdit ? '' : 'required' }}>
    </div>
</div>

<div class="d-flex justify-content-end gap-2 mt-4">
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Update User' : 'Create User' }}</button>
</div>
