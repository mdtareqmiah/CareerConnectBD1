<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feedback extends Model
{
    use HasFactory;

    public const TYPES = ['suggestion', 'complaint', 'bug_report', 'feature_request', 'general_feedback'];

    public const STATUSES = ['pending', 'in_review', 'resolved', 'closed'];

    protected $table = 'feedback';

    protected $fillable = [
        'user_id',
        'type',
        'subject',
        'message',
        'rating',
        'attachment_path',
        'status',
        'assigned_admin_id',
        'resolved_at',
        'closed_at',
    ];

    protected $casts = [
        'rating' => 'integer',
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignedAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_admin_id');
    }
}
