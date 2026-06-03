<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskLog extends Model
{
    // This tells Laravel it's okay to save data into these columns
    protected $fillable = ['task_title', 'log_type', 'remarks'];

    // This links the log back to the User who owns it
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}