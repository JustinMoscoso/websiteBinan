<?php

namespace App\Controllers;

use App\Models\ContactInquiryModel;

class ContactController extends BaseController
{
    public function index()
    {
        return view('contact_page');
    }

    public function send()
    {
        $model = new ContactInquiryModel();

        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'subject' => $this->request->getPost('subject'),
            'message' => $this->request->getPost('message'),
        ];

        // SAVE TO DATABASE
        $model->save($data);

        // EMAIL SERVICE
        $email = \Config\Services::email();

        // SEND TO ADMIN
        $email->setTo('websiteBinan@gmail.com');

        $email->setFrom(
            $data['email'],
            $data['name']
        );

        $email->setSubject(
            'New Contact Inquiry: ' . $data['subject']
        );

        $email->setMessage("
            Name: {$data['name']}
            
            Email: {$data['email']}
            
            Subject: {$data['subject']}
            
            Message:
            {$data['message']}
        ");

        $email->send();

        // OPTIONAL CONFIRMATION EMAIL
        $email->clear();

        $email->setTo($data['email']);

        $email->setFrom(
            'websiteBinan@gmail.com',
            'Website Support'
        );

        $email->setSubject('We received your inquiry');

        $email->setMessage("
            Hello {$data['name']},

            Thank you for contacting us.

            We received your inquiry and will respond soon.
        ");

        $email->send();

        return redirect()->back()->with(
            'success',
            'Message sent successfully!'
        );
    }
}