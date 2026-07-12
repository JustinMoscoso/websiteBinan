<?php

namespace App\Controllers;

use App\Models\VisitCountModel;

class Home extends BaseController
{
    public function __construct()
    {
        helper('asset_helper');
    }

    public function index()
    {
        $visitModel = new VisitCountModel();
        $contentModel = new \App\Models\Content();
        $mayor_m = new \App\Models\MayorContent();
        $hotline_m = new \App\Models\Hotlines();
        $about_m = new \App\Models\About();

        $data['visit_count'] = $visitModel->getTodayVisitCount();
        $data['announcements'] = $contentModel
            ->where('status', 'ACTIVE')
            ->where('category', 'anns')
            ->orderBy('created_date', 'DESC')
            ->findAll(3);

        $data['news_events'] = $contentModel
            ->where('status', 'ACTIVE')
            ->where('category', 'news')
            ->orderBy('created_date', 'DESC')
            ->findAll(3);

        $data['mayor_content'] = $mayor_m
            ->where('status', 'ACTIVE')
            ->where('section', 'Home Page')
            ->asArray()
            ->first();

        $data['knowmore'] = $about_m
            ->where('status', 'ACTIVE')
            ->where('section', 'Home Page')
            ->asArray()
            ->first();

        $data['hotlines'] = $hotline_m
            ->where('status', 'ACTIVE')
            ->where('section', 'Others')
            ->findAll();

        $data['emergency_hotlines'] = $about_m
            ->where('status', 'ACTIVE')
            ->where('section', 'Emergency Hotlines')
            ->orderBy('created_date', 'ASC')
            ->findAll();


        return view('home_page', $data);
    }

    public function home_page()
    {
        $contentModel = new \App\Models\Content();
        $mayor_m = new \App\Models\MayorContent();
        $hotline_m = new \App\Models\Hotlines();
        $about_m = new \App\Models\About();

        $data['announcements'] = $contentModel
            ->where('status', 'ACTIVE')
            ->where('category', 'anns')
            ->orderBy('created_date', 'DESC')
            ->findAll(3);

        $data['news_events'] = $contentModel
            ->where('status', 'ACTIVE')
            ->where('category', 'news')
            ->orderBy('created_date', 'DESC')
            ->findAll(3);

        $data['mayor_content'] = $mayor_m
            ->where('status', 'ACTIVE')
            ->where('section', 'Home Page')
            ->asArray()
            ->first();

        $data['knowmore'] = $about_m
            ->where('status', 'ACTIVE')
            ->where('section', 'Home Page')
            ->asArray()
            ->first();

        $data['hotlines'] = $hotline_m
            ->where('status', 'ACTIVE')
            ->where('section', 'Others')
            ->findAll();

        $data['emergency_hotlines'] = $about_m
            ->where('status', 'ACTIVE')
            ->where('section', 'Emergency Hotlines')
            ->orderBy('created_date', 'ASC')
            ->findAll();

        return view('home_page', $data);
    }
    public function mayor()
    {
        $may_m = new \App\Models\MayorContent();
        // Fetch all ACTIVE mayor content
        $data['mayor'] = $may_m->where('status', 'ACTIVE')->findAll();

        return view('mayor_page', $data);
    }

    public function barangay()
    {
        return view('barangay_page');
    }

    public function barangays()
    {
        $brgy_m = new \App\Models\Barangay();
        $search = $this->request->getGet('search');
        if ($search) {
            $brgy_m->like('brgy_name', $search);
        }
        $data['brgys'] = $brgy_m
            ->where('status', 'ACTIVE')
            ->findAll();
        return view('barangays_page', $data);
    }

    public function barangaycontent($id)
    {
        $brgy_m = new \App\Models\Barangay();
        $serv_m = new \App\Models\Services();
        $barangay = $brgy_m->find($id);

        if ($barangay) {
            $data['brgy'] = $barangay;
            $data['services'] = $serv_m->where('status', 'ACTIVE')
                ->where('brngy_cont_ID', $id)
                ->findAll();
            return view('barangaycontent_page', $data);
        } else {
            return view('barangay_page');
        }
    }

    public function department()
    {
        $dept_m = new \App\Models\Department();
        $search = $this->request->getGet('search');
        if ($search) {
            $dept_m->like('dept_name', $search);
        }
        $data['depts'] = $dept_m
            ->where('status', 'ACTIVE')
            ->findAll();
        return view('department_page', $data);
    }

    public function departmentcontent($id)
    {
        $dept_m = new \App\Models\Department();
        $serv_m = new \App\Models\Services();
        $department = $dept_m->find($id);

        if ($department) {
            $data['dept'] = $department;
            $data['services'] = $serv_m->where('status', 'ACTIVE')
                ->where('dept_cont_id', $id)
                ->findAll();
            return view('departmentcontent_page', $data);
        } else {
            return view('department_page');
        }
    }

    public function services($entity_id = null)
    {
        return view('services_page');
    }

    public function servicescontent($id)
    {
        $serv_m = new \App\Models\Services();
        $service = $serv_m->find($id);

        if ($service) {
            $data['serve'] = $service;
            return view('servicescontent_page', $data);
        } else {
            return view('services_page');
        }
    }

    public function about()
    {
        $about_m = new \App\Models\About();
        // Fetch Header Content
        $header_content = $about_m->where('status', 'ACTIVE')
            ->where('section', 'Header')
            ->first();

        // Fetch Content Section
        $content_sections = $about_m->where('status', 'ACTIVE')
            ->where('section', 'Content')
            ->findAll();

        // Fetch Emergency Hotlines
        $emergency_hotlines = $about_m->where('status', 'ACTIVE')
            ->where('section', 'Emergency Hotlines')
            ->orderBy('created_date', 'ASC')
            ->findAll();

        // Prepare data for view
        $data['header_content'] = $header_content;
        $data['content_sections'] = $content_sections;
        $data['emergency_hotlines'] = $emergency_hotlines;
        return view('about_page', $data);
    }

    public function contact()
    {
        return view('contact_page');
    }
    public function send()
    {
        $model = new \App\Models\ContactInquiryModel();

        $data = [
            'name'    => $this->request->getPost('name'),
            'email'   => $this->request->getPost('email'),
            'subject' => $this->request->getPost('subject'),
            'message' => $this->request->getPost('message'),
        ];

        // SAVE TO DATABASE
        $model->save($data);

        // QUEUE EMAILS ASYNCHRONOUSLY (no more blocking SMTP waits)
        $mailer = new \App\Libraries\EmailQueue();

        /*
        |--------------------------------------------------------------------------
        | SEND EMAIL TO ADMIN
        |--------------------------------------------------------------------------
        */

        // 1) Notify admin
        $mailer->queue([
            'to'       => 'websiteBinan@gmail.com',
            'reply_to' => $data['email'],
            'subject'  => '[Contact Inquiry] ' . $data['subject'],
            'body'     => "
            <div style='font-family: Arial, sans-serif; line-height: 1.6;'>
                <h2 style='color:#2563eb;'>New Contact Inquiry Received</h2>
                <p>A new inquiry has been submitted through the website contact form.</p>
                <hr>
                <table cellpadding='8' cellspacing='0' width='100%' style='border-collapse:collapse;'>
                    <tr><td><strong>Full Name</strong></td><td>{$data['name']}</td></tr>
                    <tr><td><strong>Email Address</strong></td><td>{$data['email']}</td></tr>
                    <tr><td><strong>Subject</strong></td><td>{$data['subject']}</td></tr>
                </table>
                <br>
                <strong>Message</strong>
                <div style='background:#f4f4f4;padding:15px;border-radius:5px;margin-top:10px;'>
                    {$data['message']}
                </div>
                <br><br>
                <p style='color:#666;font-size:13px;'>This email was automatically generated by the Biñan Website Contact System.</p>
            </div>
        ",
        ]);

        // 2) Confirm receipt to sender
        $mailer->queue([
            'to'      => $data['email'],
            'subject' => 'We Received Your Inquiry - Biñan Tech Support',
            'body'    => "
            <div style='font-family: Arial, sans-serif; line-height: 1.6;'>
                <h2 style='color:#2563eb;'>Hello {$data['name']},</h2>
                <p>Thank you for contacting <strong>Biñan Tech Support</strong>.</p>
                <p>We have successfully received your inquiry regarding:</p>
                <div style='background:#f4f4f4;padding:15px;border-left:4px solid #2563eb;margin:20px 0;'>
                    <strong>{$data['subject']}</strong>
                </div>
                <p>Our support team will review your message and respond as soon as possible.</p>
                <img src='" . base_url('assets/img/binanlogo.png') . "' alt='Biñan Tech Support' style='width:150px;margin-top:10px;'>
                <hr>
                <p style='font-size:12px;color:#777;'>This is an automated confirmation email from the Biñan Website Contact System.</p>
            </div>
        ",
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Message sent successfully!',
        ]);
    }
    public function career()
    {
        return view('career_page');
    }

    public function careers()
    {
        $career_m = new \App\Models\FileTbl();
        $career_d = $career_m
            ->where('status', 'ACTIVE')
            ->findAll();

        $data['careers'] = $career_d;
        return view('careers_page', $data);
    }

    public function fulldisc()
    {
        $fdisc_m = new \App\Models\FileTbl();
        $fdisc_d = $fdisc_m->where('status', 'ACTIVE')->findAll();

        $data['fdiscol'] = $fdisc_d;
        return view('fulldisc_page', $data);
    }

    public function jobs()
    {
        try {
            $jobModel = new \App\Models\Job();
            $search = $this->request->getGet('search');
            $company = $this->request->getGet('company');

            $builder = $jobModel->where('status', 'ACTIVE');

            // Apply search filter
            if ($search) {
                $builder = $builder->groupStart()
                    ->like('title', $search)
                    ->orLike('description', $search)
                    ->orLike('company', $search)
                    ->groupEnd();
            }

            // Apply company filter
            if ($company) {
                $builder = $builder->where('company', $company);
            }

            $jobs = $builder->orderBy('publication_date', 'DESC')->findAll();

            $data['jobs'] = $jobs;
            return view('jobs', $data);
        } catch (\Exception $e) {
            // If there's a database error, show a simple message
            log_message('error', 'Jobs page error: ' . $e->getMessage());
            $data['jobs'] = [];
            $data['error'] = 'Unable to load jobs at the moment. Please try again later.';
            return view('jobs', $data);
        }
    }

    public function officials()
    {
        $cityo_m = new \App\Models\CityOfficial();
        $cityo_d = $cityo_m->where('status', 'ACTIVE')->findAll();

        $data['cityoffi'] = $cityo_d;
        return view('officials_page', $data);
    }

    public function cityofficials()
    {
        return view('cityofficials_page');
    }

    public function newsevents($page = 1)
    {
        $contentModel = new \App\Models\Content();
        $perPage = 5; // Number of records per page
        $offset = ($page - 1) * $perPage;

        $search = $this->request->getGet('search');
        $builder = $contentModel->where('status', 'ACTIVE')->where('category', 'news')->limit(5);
        if ($search) {
            $builder = $builder->groupStart()
                ->like('title', $search)
                ->orLike('description', $search)
                ->orLike('author', $search)
                ->groupEnd();
        }
        $totalRecords = $builder->countAllResults(false);
        $news_events = $builder->orderBy('created_date', 'DESC')->findAll($perPage, $offset);

        $data['news_events'] = $news_events;
        $data['currentPage'] = $page;
        $data['totalPages'] = ceil($totalRecords / $perPage);

        return view('newsevents_page', $data);
    }

    public function announcements($page = 1)
    {
        $contentModel = new \App\Models\Content();
        $perPage = 5; // Number of records per page
        $offset = ($page - 1) * $perPage;

        $search = $this->request->getGet('search');
        $builder = $contentModel->where('status', 'ACTIVE')->where('category', 'anns');
        if ($search) {
            $builder = $builder->groupStart()

                ->like('title', $search)
                ->orLike('description', $search)
                ->orLike('author', $search)
                ->groupEnd();
        }

        $totalRecords = $builder->countAllResults(false);
        $anns_cont = $builder->orderBy('created_date', 'DESC')->findAll($perPage, $offset);

        $data['anns_cont'] = $anns_cont;
        $data['currentPage'] = $page;
        $data['totalPages'] = ceil($totalRecords / $perPage);

        return view('announcements_page', $data);
    }

    public function newseventscontent($id)
    {
        $contentModel = new \App\Models\Content();
        $news_event = $contentModel->find($id);

        $data = [];

        if ($news_event) {
            $data['news_event'] = $news_event;

            // Fetch all other news except the current one
            $news_events = $contentModel
                ->where('ID !=', $id)
                ->where('status', 'ACTIVE')
                ->where('category', 'news')
                ->orderBy('created_date', 'DESC')
                ->findAll();

            $data['news_events'] = $news_events;
        }

        return view('newseventscontent_page', $data);
    }


    public function announcementcontent($id)
    {
        $contentModel = new \App\Models\Content();
        $announcement = $contentModel->find($id);

        $data = [
            'anns' => $announcement,
            'announcements' => $contentModel
                ->where('status', 'ACTIVE')
                ->where('ID !=', $id)
                ->where('category', 'anns')
                ->orderBy('created_date', 'DESC')
                ->findAll(3)
        ];

        return view('annscontent', $data);
    }


    public function history()
    {
        $about_m = new \App\Models\About();
        $about_d = $about_m->where('status', 'ACTIVE')
            ->where('section', 'History')
            ->findAll();

        usort($about_d, static function ($left, $right) {
            $leftTitle = trim((string) ($left->title ?? ''));
            $rightTitle = trim((string) ($right->title ?? ''));
            $leftYear = preg_match('/\d{3,4}/', $leftTitle, $leftMatch) ? (int) $leftMatch[0] : PHP_INT_MAX;
            $rightYear = preg_match('/\d{3,4}/', $rightTitle, $rightMatch) ? (int) $rightMatch[0] : PHP_INT_MAX;

            if ($leftYear === $rightYear) {
                return strnatcasecmp($leftTitle, $rightTitle);
            }

            return $leftYear <=> $rightYear;
        });

        $data['history_content'] = $about_d;
        return view('history', $data);
    }

    public function invest()
    {
        $inv_m = new \App\Models\FileTbl();
        $inv_d = $inv_m->where('status', 'ACTIVE')
            ->where('category', 'INVEST')
            ->findAll();

        $data['investfiles'] = $inv_d;
        return view('invest_page', $data);
    }

    public function investmentopp()
    {
        return view('investment_opp');
    }

    public function safetyseal()
    {
        return view('safetyseal');
    }

    public function safetysealprocess()
    {
        return view('safetysealprocess');
    }

    public function hotlines()
    {
        $hotline_m = new \App\Models\Hotlines();
        $hotline_d = $hotline_m
            ->select('hotlines.*, barangay_content.brgy_name, department_content.dept_name')
            ->join('barangay_content', 'barangay_content.ID = hotlines.content_ref_id', 'left')
            ->join('department_content', 'department_content.ID = hotlines.content_ref_id', 'left')
            ->where('hotlines.status', 'ACTIVE')
            ->findAll();

        $data['hotlines'] = $hotline_d;
        return view('hotlines', $data);
    }

    public function login()
    {
        helper('asset');
        return view('login_page');
    }

    public function jobpostings($page = 1)
    {
        $jobModel = new \App\Models\FileTbl();
        $perPage = 10;
        $offset = ($page - 1) * $perPage;
        $search = $this->request->getGet('search');
        $builder = $jobModel->where('status', 'ACTIVE')->where('category', 'JOBS');
        if ($search) {
            $builder = $builder->groupStart()
                ->like('title', $search)
                ->orLike('description', $search)
                ->orLike('author', $search)
                ->groupEnd();
        }
        $totalRecords = $builder->countAllResults(false);
        $jobs = $builder->orderBy('created_date', 'DESC')->findAll($perPage, $offset);
        $data['jobs'] = $jobs;
        $data['currentPage'] = $page;
        $data['totalPages'] = ceil($totalRecords / $perPage);
        return view('jobpostings_page', $data);
    }

    public function getdepartments()
    {
        $dept_m = new \App\Models\Department();
        $departments = $dept_m->where('status', 'ACTIVE')->findAll();
        return $this->response->setJSON([
            'status' => 1,
            'data' => $departments
        ]);
    }

    public function getAllJobs()
    {
        $jobModel = new \App\Models\Job();
        $jobs = $jobModel->select('jobs.*, department_content.dept_name as company_name')
            ->join('department_content', 'department_content.ID = jobs.company', 'left')
            ->where('jobs.status', 'ACTIVE')
            ->orderBy('jobs.created_date', 'DESC')
            ->findAll();

        return $this->response->setJSON([
            'status' => 1,
            'data' => $jobs
        ]);
    }

    public function jobDetails($id)
    {
        $jobModel = new \App\Models\Job();
        $job = $jobModel->select('jobs.*, department_content.dept_name as company_name')
            ->join('department_content', 'department_content.ID = jobs.company', 'left')
            ->where('jobs.ID', $id)
            ->first();

        if ($job) {
            return $this->response->setJSON([
                'status' => 1,
                'data' => $job
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 0,
                'message' => 'Job not found'
            ]);
        }
    }

}
