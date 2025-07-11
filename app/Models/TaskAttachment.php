<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskAttachment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'task_id',
        'filename',
        'path',
        'size',
        'mime_type',
        'uploaded_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the task that owns the attachment.
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Get the user who uploaded the attachment.
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get the file size in human readable format.
     */
    public function getFormattedSizeAttribute()
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Get the file icon based on mime type.
     */
    public function getIconAttribute()
    {
        $type = explode('/', $this->mime_type)[0];

        return match ($type) {
            'image' => 'photo',
            'video' => 'video',
            'audio' => 'music',
            'application' => match ($this->mime_type) {
                'application/pdf' => 'document-text',
                'application/zip', 'application/x-rar-compressed' => 'archive',
                default => 'document',
            },
            default => 'document',
        };
    }
}
