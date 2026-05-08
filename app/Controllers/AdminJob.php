<?php
namespace App\Controllers;
use App\Models\Job;

class AdminJob extends BaseController
{
    public function index()
    {
        $jobModel = new Job();
        $jobs = $jobModel->findAll();
        $data['jobs'] = $jobs;
        return view('admin/job_list', $data);
    }

    public function create()
    {
        return view('admin/job_form');
    }

    public function store()
    {
        $jobModel = new Job();
        $jobModel->save([
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'company' => $this->request->getPost('company'),
            'type' => $this->request->getPost('type'),
            'publication_date' => $this->request->getPost('publication_date'),
            'email' => $this->request->getPost('email'),
            'status' => $this->request->getPost('status'),
        ]);
        return redirect()->to('/adminjob');
    }

    public function edit($id)
    {
        $jobModel = new Job();
        $data['job'] = $jobModel->find($id);
        return view('admin/job_form', $data);
    }

    public function update($id)
    {
        $jobModel = new Job();
        $jobModel->update($id, [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'company' => $this->request->getPost('company'),
            'type' => $this->request->getPost('type'),
            'publication_date' => $this->request->getPost('publication_date'),
            'email' => $this->request->getPost('email'),
            'status' => $this->request->getPost('status'),
        ]);
        return redirect()->to('/adminjob');
    }

    public function delete($id)
    {
        $jobModel = new Job();
        $jobModel->delete($id);
        return redirect()->to('/adminjob');
    }
} 