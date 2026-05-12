<?php

namespace App\Controllers;

use App\Models\ContactInquiryModel;
use App\Libraries\EmailQueue;

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
            'name'    => $this->request->getPost('name'),
            'email'   => $this->request->getPost('email'),
            'subject' => $this->request->getPost('subject'),
            'message' => $this->request->getPost('message'),
        ];

        // SAVE TO DATABASE
        $model->save($data);

        // QUEUE EMAILS ASYNCHRONOUSLY
        $mailer = new EmailQueue();

        // Notify admin
        $mailer->queue([
            'to'       => 'websiteBinan@gmail.com',
            'reply_to' => $data['email'],
            'subject'  => 'New Contact Inquiry: ' . $data['subject'],
            'body'     => "
                <p><strong>Name:</strong> {$data['name']}</p>
                <p><strong>Email:</strong> {$data['email']}</p>
                <p><strong>Subject:</strong> {$data['subject']}</p>
                <p><strong>Message:</strong></p>
                <p>{$data['message']}</p>
            ",
        ]);

        // Confirmation to sender
        $mailer->queue([
            'to'      => $data['email'],
            'subject' => 'We received your inquiry',
            'body'    => "
                <p>Hello {$data['name']},</p>
                <p>Thank you for contacting us.</p>
                <p>We received your inquiry and will respond soon.</p>
            ",
        ]);

        return redirect()->back()->with(
            'success',
            'Message sent successfully!'
        );
    }
}