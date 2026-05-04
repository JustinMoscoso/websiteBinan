<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class ContactController extends BaseController
{
    public function index()
    {
        return view('contact_page');
    }

    public function send()
    {
        $emailService = \Config\Services::email();

        $name = $this->request->getPost('name');
        $emailFrom = $this->request->getPost('email');
        $subject = $this->request->getPost('subject');
        $message = $this->request->getPost('message');

        $emailService->setFrom('nmah.business@gmail.com', 'information'); // Replace 'Your Name' with your actual name
        $emailService->setTo('nmah.business@gmail.com'); // Replace with your email address
        $emailService->setReplyTo($emailFrom, $name);
        $emailService->setSubject($subject);
        $emailService->setMessage($message);

        if ($emailService->send()) {
            // Success response
            $response = [
                'status' => 'success',
                'message' => 'Message sent successfully!'
            ];
        } else {
            // Error response
            $response = [
                'status' => 'error',
                'message' => 'Failed to send message. Please try again later.'
            ];
        }

        return $this->response->setJSON($response);
    }
} 
