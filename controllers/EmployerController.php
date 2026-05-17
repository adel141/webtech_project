<?php
class EmployerController extends Controller {
    public function __construct() { Middleware::requireRole('employer'); }

    public function dashboard() {
        $jobModel = new JobModel(); $appModel = new ApplicationModel(); $msgModel = new MessageModel();
        $jobs = $jobModel->getByEmployer(Auth::id());
        $totalJobs = count($jobs);
        $activeJobs = count(array_filter($jobs, fn($j) => $j['status'] === 'active'));
        $totalApps = $appModel->countByEmployer(Auth::id());
        $unread = $msgModel->countUnread(Auth::id());
        $verified = Auth::isVerified();
        $pageTitle = 'Dashboard'; $activePage = 'dashboard';
        $this->view('employer/dashboard', compact('pageTitle','activePage','totalJobs','activeJobs','totalApps','unread','jobs','verified'));
    }

    public function profile() {
        $profile = (new EmployerModel())->getProfile(Auth::id());
        $pageTitle = 'Company Profile'; $activePage = 'profile';
        $this->view('employer/profile', compact('pageTitle','activePage','profile'));
    }

    public function updateProfile() {
        Middleware::verifyCsrf();
        $data = ['company_name'=>$this->input('company_name'),'industry'=>$this->input('industry'),'company_size'=>$this->input('company_size'),'description'=>$this->input('description'),'website'=>$this->input('website'),'address'=>$this->input('address')];
        (new EmployerModel())->updateProfile(Auth::id(), $data);
        $this->flash('success', 'Company profile updated!');
        $this->redirect('/employer/profile');
    }

    public function uploadLogo() {
        Middleware::verifyCsrf();
        if (!isset($_FILES['logo']) || $_FILES['logo']['error'] !== UPLOAD_ERR_OK) { $this->flash('error','Select an image.'); $this->redirect('/employer/profile'); return; }
        $file = $_FILES['logo'];
        if ($file['size'] > MAX_IMAGE_SIZE) { $this->flash('error','Max 2MB.'); $this->redirect('/employer/profile'); return; }
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'logo_' . Auth::id() . '_' . time() . '.' . $ext;
        if (!is_dir(UPLOAD_PATH . '/logos')) mkdir(UPLOAD_PATH . '/logos', 0755, true);
        move_uploaded_file($file['tmp_name'], UPLOAD_PATH . '/logos/' . $filename);
        (new EmployerModel())->updateLogo(Auth::id(), $filename);
        $this->flash('success','Logo updated!');
        $this->redirect('/employer/profile');
    }

    public function jobs() {
        $jobs = (new JobModel())->getByEmployer(Auth::id());
        $pageTitle = 'Job Postings'; $activePage = 'jobs';
        $this->view('employer/jobs', compact('pageTitle','activePage','jobs'));
    }

    public function createJob() {
        $categories = (new CategoryModel())->getAll2();
        $pageTitle = 'Create Job'; $activePage = 'jobs'; $job = null;
        $this->view('employer/job_form', compact('pageTitle','activePage','categories','job'));
    }

    public function storeJob() {
        Middleware::verifyCsrf();
        $data = ['employer_id'=>Auth::id(),'recruiter_id'=>null,'category_id'=>(int)$this->input('category_id'),'title'=>$this->input('title'),'description'=>$this->input('description'),'requirements'=>$this->input('requirements'),'benefits'=>$this->input('benefits'),'salary_min'=>(float)$this->input('salary_min'),'salary_max'=>(float)$this->input('salary_max'),'location'=>$this->input('location'),'job_type'=>$this->input('job_type'),'experience_level'=>$this->input('experience_level'),'deadline'=>$this->input('deadline'),'status'=>$this->input('status','draft')];
        $errors = [];
        if (!$data['title']) $errors[] = 'Title is required.';
        if (!$data['description']) $errors[] = 'Description is required.';
        if (!empty($errors)) { $this->flash('error', implode(' ', $errors)); $this->redirect('/employer/jobs/create'); return; }
        (new JobModel())->create($data);
        $this->flash('success','Job created!');
        $this->redirect('/employer/jobs');
    }

    public function editJob($id) {
        $job = (new JobModel())->findById($id);
        if (!$job || $job['employer_id'] != Auth::id()) { $this->flash('error','Job not found.'); $this->redirect('/employer/jobs'); return; }
        $categories = (new CategoryModel())->getAll2();
        $pageTitle = 'Edit Job'; $activePage = 'jobs';
        $this->view('employer/job_form', compact('pageTitle','activePage','categories','job'));
    }

    public function updateJob($id) {
        Middleware::verifyCsrf();
        $job = (new JobModel())->findById($id);
        if (!$job || $job['employer_id'] != Auth::id()) { $this->flash('error','Unauthorized.'); $this->redirect('/employer/jobs'); return; }
        $data = ['category_id'=>(int)$this->input('category_id'),'title'=>$this->input('title'),'description'=>$this->input('description'),'requirements'=>$this->input('requirements'),'benefits'=>$this->input('benefits'),'salary_min'=>(float)$this->input('salary_min'),'salary_max'=>(float)$this->input('salary_max'),'location'=>$this->input('location'),'job_type'=>$this->input('job_type'),'experience_level'=>$this->input('experience_level'),'deadline'=>$this->input('deadline'),'status'=>$this->input('status','draft')];
        (new JobModel())->update($id, $data);
        $this->flash('success','Job updated!');
        $this->redirect('/employer/jobs');
    }

    public function deleteJob($id) {
        Middleware::verifyCsrf();
        $job = (new JobModel())->findById($id);
        if ($job && $job['employer_id'] == Auth::id()) { (new JobModel())->delete($id); $this->flash('success','Job deleted.'); }
        $this->redirect('/employer/jobs');
    }

    public function repostJob($id) {
        Middleware::verifyCsrf();
        $job = (new JobModel())->findById($id);
        if ($job && $job['employer_id'] == Auth::id()) { (new JobModel())->toggleStatus($id, 'active'); $this->flash('success','Job reposted!'); }
        $this->redirect('/employer/jobs');
    }

    public function applicants($jobId) {
        $job = (new JobModel())->findById($jobId);
        if (!$job || $job['employer_id'] != Auth::id()) { $this->flash('error','Not found.'); $this->redirect('/employer/jobs'); return; }
        $statusFilter = $this->query('status');
        $applicants = (new ApplicationModel())->getByJob($jobId, $statusFilter);
        $pageTitle = 'Applicants — ' . $job['title']; $activePage = 'jobs';
        $this->view('employer/applicants', compact('pageTitle','activePage','job','applicants','statusFilter'));
    }

    public function applicantDetail($id) {
        $app = (new ApplicationModel())->findById($id);
        if (!$app || $app['employer_id'] != Auth::id()) { $this->flash('error','Not found.'); $this->redirect('/employer/jobs'); return; }
        $pageTitle = 'Applicant Detail'; $activePage = 'jobs';
        $this->view('employer/applicant_detail', compact('pageTitle','activePage','app'));
    }

    public function shortlisted() {
        $shortlisted = (new ApplicationModel())->getShortlistedByEmployer(Auth::id());
        $pageTitle = 'Shortlisted Candidates'; $activePage = 'shortlisted';
        $this->view('employer/shortlisted', compact('pageTitle','activePage','shortlisted'));
    }

    public function analytics() {
        $jobModel = new JobModel(); $appModel = new ApplicationModel();
        $jobs = $jobModel->getByEmployer(Auth::id());
        $totalApps = $appModel->countByEmployer(Auth::id());
        $appTimeline = $appModel->getApplicationsOverTime(Auth::id());
        $pageTitle = 'Analytics'; $activePage = 'analytics';
        $this->view('employer/analytics', compact('pageTitle','activePage','jobs','totalApps','appTimeline'));
    }

    public function jobAnalytics($id) {
        $job = (new JobModel())->findById($id);
        if (!$job || $job['employer_id'] != Auth::id()) { $this->redirect('/employer/analytics'); return; }
        $funnel = (new ApplicationModel())->getFunnelByJob($id);
        $pageTitle = 'Job Analytics'; $activePage = 'analytics';
        $this->view('employer/job_analytics', compact('pageTitle','activePage','job','funnel'));
    }

    public function recruiters() {
        $recruiters = (new EmployerModel())->getRecruitersForEmployer(Auth::id());
        $pageTitle = 'Recruiter Agencies'; $activePage = 'recruiters';
        $this->view('employer/recruiters_list', compact('pageTitle','activePage','recruiters'));
    }

    public function complaints() {
        $complaints = (new ComplaintModel())->getBySubmitter(Auth::id());
        $pageTitle = 'Complaints'; $activePage = 'complaints';
        $this->view('employer/complaints', compact('pageTitle','activePage','complaints'));
    }

    public function submitComplaint() {
        Middleware::verifyCsrf();
        $desc = $this->input('description');
        $subjectId = (int)$this->input('subject_id') ?: null;
        if (!$desc) { $this->flash('error','Description required.'); $this->redirect('/employer/complaints'); return; }
        (new ComplaintModel())->create(Auth::id(), $subjectId, $desc);
        $this->flash('success','Complaint submitted.');
        $this->redirect('/employer/complaints');
    }
}
