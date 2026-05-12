<?php

namespace App\Libraries;

use App\Models\EmailQueueModel;
use Config\Services;

/**
 * EmailQueue Library
 * -------------------
 * Saves every outgoing email to the `email_queue` table for auditing
 * and retry, then sends it immediately using CI4's built-in email
 * service with SMTPKeepAlive so multiple emails in one request share
 * a single SMTP connection instead of opening one per message.
 *
 * Usage (same API as before):
 *   $mailer = new \App\Libraries\EmailQueue();
 *   $mailer->queue([
 *       'to'        => 'user@example.com',
 *       'subject'   => 'Hello',
 *       'body'      => '<p>Hi!</p>',
 *       // Optional:
 *       'from'      => 'noreply@example.com',
 *       'from_name' => 'My App',
 *       'reply_to'  => 'support@example.com',
 *   ]);
 */
class EmailQueue
{
    /**
     * Shared CI4 email service instance — reused across all queue()
     * calls in the same request so we open only ONE SMTP connection
     * regardless of how many emails are sent.
     */
    private static ?\CodeIgniter\Email\Email $mailer = null;

    /**
     * Queue an email: persist it to DB, then send via CI4 email.
     */
    public function queue(array $params): bool
    {
        $model = new EmailQueueModel();

        $toEmail   = $params['to'];
        $fromEmail = $params['from']      ?? 'websiteBinan@gmail.com';
        $fromName  = $params['from_name'] ?? 'Biñan Tech Support';
        $replyTo   = $params['reply_to']  ?? null;
        $subject   = $params['subject'];
        $body      = $params['body'];

        // ── 1. Persist to queue table (audit / retry trail) ──────────
        $model->insert([
            'to_email'   => $toEmail,
            'from_email' => $fromEmail,
            'from_name'  => $fromName,
            'reply_to'   => $replyTo,
            'subject'    => $subject,
            'body'       => $body,
            'status'     => 'PENDING',
            'attempts'   => 0,
        ]);
        $queueId = $model->getInsertID();

        // ── 2. Send immediately via CI4 email ─────────────────────────
        $email = $this->getMailer();

        // clear() with true resets recipients/subject/body but keeps
        // the open SMTP connection (SMTPKeepAlive) alive.
        $email->clear(true);

        $email->setFrom($fromEmail, $fromName);
        $email->setTo($toEmail);

        if (!empty($replyTo)) {
            $email->setReplyTo($replyTo);
        }

        $email->setSubject($subject);
        $email->setMessage($body);

        $sent = $email->send(false); // false = don't auto-clear after send

        // ── 3. Update queue record with result ────────────────────────
        if ($sent) {
            $model->markSent((int) $queueId);
        } else {
            $model->markFailed(
                (int) $queueId,
                1,
                $email->printDebugger(['headers'])
            );
            log_message('error', '[EmailQueue] Failed to send email to ' . $toEmail . ' — ' . $email->printDebugger(['headers']));
        }

        return $sent;
    }

    /**
     * Retry all PENDING / previously FAILED emails in the queue.
     * Useful if called from a cron job or admin panel.
     *
     * @return array{sent: int, failed: int}
     */
    public function processQueue(int $limit = 50): array
    {
        $model   = new EmailQueueModel();
        $pending = $model->getPending($limit);

        $sent   = 0;
        $failed = 0;

        if (empty($pending)) {
            return compact('sent', 'failed');
        }

        $email = $this->getMailer();

        foreach ($pending as $item) {
            try {
                $email->clear(true);
                $email->setFrom($item['from_email'], $item['from_name']);
                $email->setTo($item['to_email']);

                if (!empty($item['reply_to'])) {
                    $email->setReplyTo($item['reply_to']);
                }

                $email->setSubject($item['subject']);
                $email->setMessage($item['body']);

                if ($email->send(false)) {
                    $model->markSent((int) $item['id']);
                    $sent++;
                } else {
                    $attempts = (int) $item['attempts'] + 1;
                    $model->markFailed(
                        (int) $item['id'],
                        $attempts,
                        $email->printDebugger(['headers'])
                    );
                    $failed++;
                }
            } catch (\Throwable $e) {
                $attempts = (int) $item['attempts'] + 1;
                $model->markFailed((int) $item['id'], $attempts, $e->getMessage());
                log_message('error', '[EmailQueue] Exception: ' . $e->getMessage());
                $failed++;
            }
        }

        return compact('sent', 'failed');
    }

    // ----------------------------------------------------------------
    // PRIVATE HELPERS
    // ----------------------------------------------------------------

    /**
     * Returns (and lazily creates) a single shared CI4 email service
     * instance with SMTPKeepAlive enabled.  All queue() calls in the
     * same PHP request reuse this instance, so only ONE SMTP handshake
     * is needed no matter how many emails are sent.
     */
    private function getMailer(): \CodeIgniter\Email\Email
    {
        if (self::$mailer === null) {
            self::$mailer = Services::email();
            // SMTPKeepAlive keeps the TCP connection to Gmail open
            // between multiple send() calls — avoids repeated TLS
            // handshakes which are the main source of latency.
            self::$mailer->initialize(['SMTPKeepAlive' => true]);
        }

        return self::$mailer;
    }
}
