<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    public $timestamps = false; // created_at only, no updated_at

    protected $fillable = [
        'user_id',
        'message',
        'event_type',
        'event_level',
        'created_at',
    ];

    // --------------------------------
    // Relationships
    // --------------------------------

    // The user who triggered the event
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
