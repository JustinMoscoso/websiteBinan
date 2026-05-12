<?php

namespace App\Controllers;

use App\Models\TicketModel;
use App\Models\UserAccount;
use App\Libraries\EmailQueue;

class TicketController extends BaseController
{
    public function __construct()
    {
        helper('asset');
    }

    public function create()
    {
        return view('tickets/create');
    }

    public function submit()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 0, 'message' => 'Invalid request']);
        }

        $username = trim($this->request->getPost('username'));
        $concern = trim($this->request->getPost('concern'));

        if (empty($username) || empty($concern)) {
            return $this->response->setJSON(['status' => 0, 'message' => 'All fields are required']);
        }

        $userModel = new UserAccount();
        $user = $userModel->where('username', $username)
                          ->orWhere('email', $username)
                          ->first();

        if (!$user) {
            return $this->response->setJSON(['status' => 0, 'message' => 'User not found. Please enter a valid username.']);
        }

        $ticketModel = new TicketModel();
        $ticketNumber = 'TKT-' . strtoupper(bin2hex(random_bytes(3)));

        $data = [
            'ticket_number' => $ticketNumber,
            'user_id'       => $user->ID,
            'username'      => $user->username,
            'concern'       => $concern,
            'status'        => 'OPEN'
        ];

        if ($ticketModel->insert($data)) {
            // Queue email confirmation asynchronously
            $mailer = new EmailQueue();
            $mailer->queue([
                'to'      => $user->email,
                'subject' => 'Support Ticket Confirmation - ' . $ticketNumber,
                'body'    => "
                    <p>Hello {$user->fname},</p>
                    <p>We have received your concern. Your ticket has been created successfully.</p>
                    <p><strong>Ticket Number:</strong> {$ticketNumber}</p>
                    <p><strong>Your Concern:</strong><br>{$concern}</p>
                    <p>An admin will attend to your concern shortly. You will be notified via email once it is resolved.</p>
                    <p>Thank you.</p>
                ",
            ]);

            return $this->response->setJSON([
                'status' => 1, 
                'message' => 'Ticket submitted successfully!',
                'ticket_number' => $ticketNumber
            ]);
        } else {
            return $this->response->setJSON(['status' => 0, 'message' => 'Failed to submit ticket. Please try again.']);
        }
    }
}
