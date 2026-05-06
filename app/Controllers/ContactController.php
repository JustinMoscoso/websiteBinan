<?php

namespace App\Controllers;

use App\Models\ContactInquiry;
use CodeIgniter\Controller;

class ContactController extends BaseController
{
    public function index()
    {
        return view('contact_page');
    }

    public function send()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to(base_url('contact'));
        }

        $model = new ContactInquiry();
        
        $data = [
            'name'    => $this->request->getPost('name'),
            'email'   => $this->request->getPost('email'),
            'subject' => $this->request->getPost('subject'),
            'message' => $this->request->getPost('message'),
        ];

        // 1. Validate and Save to Database
        if (!$model->save($data)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => implode('<br>', $model->errors())
            ]);
        }

        // 2. Send Email
        $emailService = \Config\Services::email();
        $emailConfig = config('Email');

        $emailService->setFrom($emailConfig->fromEmail, $emailConfig->fromName);
        $emailService->setTo($emailConfig->fromEmail); // Sending to admin
        $emailService->setReplyTo($data['email'], $data['name']);
        $emailService->setSubject("Contact Inquiry: " . $data['subject']);
        
        $emailBody = "You have received a new contact inquiry.\n\n"
                   . "Name: " . $data['name'] . "\n"
                   . "Email: " . $data['email'] . "\n"
                   . "Subject: " . $data['subject'] . "\n\n"
                   . "Message:\n" . $data['message'];
                   
        $emailService->setMessage($emailBody);

        if ($emailService->send()) {
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Your message has been sent successfully!'
            ]);
        } else {
            // Even if email fails, we return success because it's saved in DB
            // But we might want to log the email failure
            log_message('error', 'Failed to send contact email: ' . $emailService->printDebugger(['headers']));
            
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Your message was saved, but we had trouble sending the email notification. Our team will still check your message!'
            ]);
        }
    }
} 
