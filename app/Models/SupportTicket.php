<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupportTicket extends Model
{
    use HasFactory;

    public const PRIORITIES = ['low', 'medium', 'high', 'urgent'];

    public const STATUSES = ['open', 'pending', 'answered', 'resolved', 'closed'];

    protected $fillable = [
        'ticket_number',
        'user_id',
        'category',
        'priority',
        'status',
        'subject',
        'message',
        'assigned_admin_id',
        'closed_at',
        'resolved_at',
        'last_reply_at',
    ];

    protected $casts = [
        'closed_at' => 'datetime',
        'resolved_at' => 'datetime',
        'last_reply_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignedAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_admin_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(SupportTicketMessage::class)->latest('created_at');
    }
}
