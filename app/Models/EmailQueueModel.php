<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * EmailQueueModel
 *
 * Manages the `email_queue` table. All email sending goes through
 * this queue to prevent blocking HTTP responses on SMTP round-trips.
 */
class EmailQueueModel extends Model
{
    protected $table      = 'email_queue';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'to_email',
        'from_email',
        'from_name',
        'reply_to',
        'subject',
        'body',
        'status',
        'attempts',
        'error_msg',
        'sent_at',
    ];

    protected $useTimestamps  = false; // We manage created_at via DB default
    protected $returnType     = 'array';

    /**
     * Fetch all PENDING emails, oldest first (FIFO).
     */
    public function getPending(int $limit = 20): array
    {
        return $this->where('status', 'PENDING')
                    ->where('attempts <', 3)
                    ->orderBy('created_at', 'ASC')
                    ->findAll($limit);
    }

    /**
     * Mark a queued email as SENT.
     */
    public function markSent(int $id): void
    {
        $this->update($id, [
            'status'  => 'SENT',
            'sent_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Mark a queued email as FAILED and store the error message.
     */
    public function markFailed(int $id, int $attempts, string $error): void
    {
        $this->update($id, [
            'status'    => $attempts >= 3 ? 'FAILED' : 'PENDING',
            'attempts'  => $attempts,
            'error_msg' => $error,
        ]);
    }
}
