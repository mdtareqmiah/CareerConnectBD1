<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactMessage extends Model
{
    use HasFactory;

    public const STATUSES = ['unread', 'in_review', 'replied', 'closed'];

    public const CATEGORIES = ['general', 'technical', 'billing', 'partnership', 'other'];

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'subject',
        'category',
        'message',
        'assigned_admin_id',
        'status',
        'read_at',
        'replied_at',
        'closed_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'replied_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function assignedAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_admin_id');
    }
}
