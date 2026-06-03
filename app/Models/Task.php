<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    // This tells Laravel it's okay to save data into these columns
    protected $fillable = ['title', 'description', 'status', 'due_date'];

    // This links the task back to the User who owns it
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}