<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'event_type',
        'event_level',
        'message',
        'created_at', // manually set this
        'affected_user_ids', // if you store multiple affected users
    ];

    protected $casts = [
        'affected_user_ids' => 'array',
        'created_at' => 'datetime',
    ];

    // User who performed the action
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
