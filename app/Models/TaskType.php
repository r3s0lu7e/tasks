<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'color',
        'icon',
        'icon_color',
    ];

    /**
     * Get the tasks that belong to this type.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class, 'task_type_id');
    }

    public function getRgbColorAttribute()
    {
        $hex = ltrim($this->color, '#');
        if (strlen($hex) == 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        if (strlen($hex) != 6) {
            return '128, 128, 128'; // Default to gray if format is invalid
        }

        list($r, $g, $b) = sscanf($hex, "%02x%02x%02x");
        return "$r, $g, $b";
    }
}
