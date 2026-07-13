<div class="d-flex justify-content-end gap-2 flex-wrap">
    @if(! $user->trashed())
        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary">Edit</a>

        <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="d-inline">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-sm {{ $user->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}" onclick="return confirm('Are you sure?')">
                {{ $user->is_active ? 'Deactivate' : 'Activate' }}
            </button>
        </form>

        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Soft delete this user?')">Delete</button>
        </form>
    @else
        <form action="{{ route('admin.users.restore', $user->id) }}" method="POST" class="d-inline">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-sm btn-outline-success">Restore</button>
        </form>
    @endif
</div>
