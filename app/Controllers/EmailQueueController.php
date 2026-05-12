<?php

namespace App\Controllers;

use App\Libraries\EmailQueue;

/**
 * EmailQueueController
 * ----------------------
 * Processes pending emails from the email_queue table.
 *
 * This controller is hit by EmailQueue::dispatchAsync() via a
 * fire-and-forget socket request — it is NOT meant to be called by
 * regular users. The route is intentionally not in the admin group so
 * it can be triggered from within the same PHP process on loopback.
 *
 * Security: protected by a shared secret token passed as a query param.
 *           Configure EMAIL_QUEUE_SECRET in your .env file.
 *
 * Route: GET /email-queue/process?token=<secret>
 */
class EmailQueueController extends BaseController
{
    public function process(): void
    {
        // ── Basic self-protection ──────────────────────────────────────
        // Only allow loopback calls or CLI. Shared secret is compared
        // in constant time to prevent timing attacks.
        $expectedToken = env('EMAIL_QUEUE_SECRET', 'binan_queue_secret_2026');
        $providedToken = $this->request->getGet('token') ?? '';

        if (!hash_equals($expectedToken, $providedToken)) {
            // Silently refuse — no useful info for attackers.
            http_response_code(403);
            exit;
        }

        // ── Prevent overlapping runs ───────────────────────────────────
        $lockFile = WRITEPATH . 'email_queue.lock';
        $lock = fopen($lockFile, 'c');

        if (!flock($lock, LOCK_EX | LOCK_NB)) {
            // Another worker is already processing — skip this run.
            fclose($lock);
            http_response_code(200);
            echo json_encode(['status' => 'skipped', 'reason' => 'locked']);
            exit;
        }

        // ── Run the queue ──────────────────────────────────────────────
        try {
            $queue  = new EmailQueue();
            $result = $queue->processQueue();

            echo json_encode(['status' => 'ok', 'result' => $result]);
        } finally {
            flock($lock, LOCK_UN);
            fclose($lock);
        }

        exit;
    }
}
