<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecklistItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'content',
        'is_completed',
        'order',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
