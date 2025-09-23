<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'filename',
        'original_filename',
        'status',
        'total_products',
        'processed_products',
        'successful_imports',
        'failed_imports',
        'error_message',
        'import_log',
        'progress_percentage',
        'started_at',
        'completed_at'
    ];

    protected $casts = [
        'import_log' => 'array',
        'progress_percentage' => 'decimal:2',
        'started_at' => 'datetime',
        'completed_at' => 'datetime'
    ];

    /**
     * Get the user that owns the import job.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the status label.
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'Pending',
            'processing' => 'Processing',
            'completed' => 'Completed',
            'failed' => 'Failed',
            default => ucfirst($this->status)
        };
    }

    /**
     * Get the status badge class.
     */
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'processing' => 'info',
            'completed' => 'success',
            'failed' => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Update progress.
     */
    public function updateProgress($processed, $successful = null, $failed = null)
    {
        $this->processed_products = $processed;
        
        if ($successful !== null) {
            $this->successful_imports = $successful;
        }
        
        if ($failed !== null) {
            $this->failed_imports = $failed;
        }
        
        $this->progress_percentage = $this->total_products > 0 
            ? round(($processed / $this->total_products) * 100, 2) 
            : 0;
            
        $this->save();
    }

    /**
     * Mark as started.
     */
    public function markAsStarted()
    {
        $this->update([
            'status' => 'processing',
            'started_at' => now()
        ]);
    }

    /**
     * Mark as completed.
     */
    public function markAsCompleted()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'progress_percentage' => 100
        ]);
    }

    /**
     * Mark as failed.
     */
    public function markAsFailed($errorMessage)
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $errorMessage,
            'completed_at' => now()
        ]);
    }

    /**
     * Add to import log.
     */
    public function addToLog($message, $type = 'info')
    {
        $log = $this->import_log ?? [];
        $log[] = [
            'timestamp' => now()->toISOString(),
            'type' => $type,
            'message' => $message
        ];
        
        $this->update(['import_log' => $log]);
    }

    /**
     * Scope for pending jobs.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for processing jobs.
     */
    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    /**
     * Scope for completed jobs.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for failed jobs.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }
}