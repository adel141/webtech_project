<?php
class SeekerController extends Controller {
    public function __construct() { Middleware::requireRole('seeker'); }

    public function dashboard() {
        $appModel = new ApplicationModel();
        $alertModel = new AlertModel();
        $msgModel = new MessageModel();
        $jobModel = new JobModel();
        $apps = $appModel->getBySeekerWithJob(Auth::id());
        $totalApps = count($apps);
        $activeApps = count(array_filter($apps, fn($a) => !in_array($a['status'], ['rejected','withdrawn'])));
        $interviews = count(array_filter($apps, fn($a) => $a['status'] === 'interview'));
        $unread = $msgModel->countUnread(Auth::id());
        $featured = $jobModel->getFeatured(4);
        $notifications = $alertModel->getMatchingJobs(Auth::id());
        $notifCount = count($notifications);
        $pageTitle = 'Dashboard'; $activePage = 'dashboard';
        $this->view('seeker/dashboard', compact('pageTitle','activePage','totalApps','activeApps','interviews','unread','featured','notifCount'));
    }

    public function profile() {
        $seekerModel = new SeekerModel();
        $profile = $seekerModel->getProfile(Auth::id());
        $pageTitle = 'My Profile'; $activePage = 'profile';
        $this->view('seeker/profile', compact('pageTitle','activePage','profile'));
    }

    public function updateProfile() {
        Middleware::verifyCsrf();
        $seekerModel = new SeekerModel();
        $data = [
            'headline' => $this->input('headline'),
            'summary' => $this->input('summary'),
            'skills' => $this->input('skills'),
            'years_experience' => (int)$this->input('years_experience'),
            'education_level' => $this->input('education_level'),
            'current_salary' => (float)$this->input('current_salary'),
            'expected_salary' => (float)$this->input('expected_salary'),
            'preferred_location' => $this->input('preferred_location')
        ];
        $seekerModel->updateProfile(Auth::id(), $data);
        // Update name in users table
        $name = $this->input('name');
        if ($name) {
            $db = getDB();
            $stmt = $db->prepare("UPDATE users SET name = ? WHERE id = ?");
            $stmt->bind_param("si", $name, $_SESSION['user_id']);
            $stmt->execute(); $stmt->close();
            $_SESSION['user_name'] = $name;
        }
        $this->flash('success', 'Profile updated successfully!');
        $this->redirect('/seeker/profile');
    }

    public function uploadResume() {
        Middleware::verifyCsrf();
        if (!isset($_FILES['resume']) || $_FILES['resume']['error'] !== UPLOAD_ERR_OK) {
            $this->flash('error', 'Please select a valid PDF file.'); $this->redirect('/seeker/profile'); return;
        }
        $file = $_FILES['resume'];
        if ($file['size'] > MAX_RESUME_SIZE) { $this->flash('error', 'Resume must be under 5MB.'); $this->redirect('/seeker/profile'); return; }
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        if (!in_array($mime, ALLOWED_RESUME_TYPES)) { $this->flash('error', 'Only PDF files are allowed.'); $this->redirect('/seeker/profile'); return; }
        $filename = 'resume_' . Auth::id() . '_' . time() . '.pdf';
        $dest = UPLOAD_PATH . '/resumes/' . $filename;
        if (!is_dir(UPLOAD_PATH . '/resumes')) mkdir(UPLOAD_PATH . '/resumes', 0755, true);
        move_uploaded_file($file['tmp_name'], $dest);
        (new SeekerModel())->updateResume(Auth::id(), $filename);
        $this->flash('success', 'Resume uploaded!');
        $this->redirect('/seeker/profile');
    }

    public function uploadPhoto() {
        Middleware::verifyCsrf();
        if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
            $this->flash('error', 'Please select an image.'); $this->redirect('/seeker/profile'); return;
        }
        $file = $_FILES['photo'];
        if ($file['size'] > MAX_IMAGE_SIZE) { $this->flash('error', 'Image must be under 2MB.'); $this->redirect('/seeker/profile'); return; }
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        if (!in_array($mime, ALLOWED_IMAGE_TYPES)) { $this->flash('error', 'Only JPEG/PNG/GIF/WebP allowed.'); $this->redirect('/seeker/profile'); return; }
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'avatar_' . Auth::id() . '_' . time() . '.' . $ext;
        $dest = UPLOAD_PATH . '/profile_pics/' . $filename;
        if (!is_dir(UPLOAD_PATH . '/profile_pics')) mkdir(UPLOAD_PATH . '/profile_pics', 0755, true);
        move_uploaded_file($file['tmp_name'], $dest);
        (new UserModel())->updateProfilePic(Auth::id(), $filename);
        $_SESSION['user_pic'] = $filename;
        $this->flash('success', 'Profile picture updated!');
        $this->redirect('/seeker/profile');
    }

    public function jobs() {
        $jobModel = new JobModel(); $catModel = new CategoryModel();
        $jobs = $jobModel->getActiveJobs(50);
        $categories = $catModel->getAll2();
        $pageTitle = 'Browse Jobs'; $activePage = 'jobs';
        $this->view('seeker/jobs', compact('pageTitle','activePage','jobs','categories'));
    }

    public function jobDetail($id) {
        $job = (new JobModel())->findById($id);
        if (!$job || $job['status'] !== 'active') { $this->flash('error', 'Job not found.'); $this->redirect('/seeker/jobs'); return; }
        $hasApplied = (new ApplicationModel())->exists($id, Auth::id());
        $isSaved = (new Model())->__construct() ? false : false;
        // Check if saved
        $db = getDB();
        $stmt = $db->prepare("SELECT id FROM saved_jobs WHERE user_id = ? AND job_id = ?");
        $stmt->bind_param("ii", $_SESSION['user_id'], $id);
        $stmt->execute();
        $isSaved = $stmt->get_result()->fetch_assoc() ? true : false;
        $stmt->close();
        $pageTitle = $job['title']; $activePage = 'jobs';
        $this->view('seeker/job_detail', compact('pageTitle','activePage','job','hasApplied','isSaved'));
    }

    public function apply($id) {
        Middleware::verifyCsrf();
        $appModel = new ApplicationModel();
        if ($appModel->exists($id, Auth::id())) { $this->flash('error', 'You already applied.'); $this->redirect('/seeker/jobs/' . $id); return; }
        $coverLetter = $this->input('cover_letter');
        $resumePath = null;
        if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['resume'];
            $filename = 'app_resume_' . Auth::id() . '_' . time() . '.pdf';
            if (!is_dir(UPLOAD_PATH . '/resumes')) mkdir(UPLOAD_PATH . '/resumes', 0755, true);
            move_uploaded_file($file['tmp_name'], UPLOAD_PATH . '/resumes/' . $filename);
            $resumePath = $filename;
        } else {
            $profile = (new SeekerModel())->getProfile(Auth::id());
            $resumePath = $profile['resume_path'] ?? null;
        }
        $appModel->create($id, Auth::id(), null, $coverLetter, $resumePath);
        $this->flash('success', 'Application submitted!');
        $this->redirect('/seeker/applications');
    }

    public function withdraw($id) {
        Middleware::verifyCsrf();
        (new ApplicationModel())->withdraw($id, Auth::id());
        $this->flash('success', 'Application withdrawn.');
        $this->redirect('/seeker/applications');
    }

    public function applications() {
        $apps = (new ApplicationModel())->getBySeekerWithJob(Auth::id());
        $pageTitle = 'My Applications'; $activePage = 'applications';
        $this->view('seeker/applications', compact('pageTitle','activePage','apps'));
    }

    public function savedJobs() {
        $db = getDB();
        $stmt = $db->prepare("SELECT sj.*, j.title, j.location, j.job_type, j.salary_min, j.salary_max, j.status, ep.company_name FROM saved_jobs sj JOIN jobs j ON sj.job_id = j.id LEFT JOIN employer_profiles ep ON j.employer_id = ep.user_id WHERE sj.user_id = ? ORDER BY sj.saved_at DESC");
        $uid = Auth::id(); $stmt->bind_param("i", $uid); $stmt->execute();
        $saved = $stmt->get_result()->fetch_all(MYSQLI_ASSOC); $stmt->close();
        $pageTitle = 'Saved Jobs'; $activePage = 'saved';
        $this->view('seeker/saved_jobs', compact('pageTitle','activePage','saved'));
    }

    public function toggleSave($jobId) {
        Middleware::verifyCsrf();
        $db = getDB(); $uid = Auth::id();
        $stmt = $db->prepare("SELECT id FROM saved_jobs WHERE user_id = ? AND job_id = ?");
        $stmt->bind_param("ii", $uid, $jobId); $stmt->execute();
        if ($stmt->get_result()->fetch_assoc()) {
            $stmt->close();
            $stmt = $db->prepare("DELETE FROM saved_jobs WHERE user_id = ? AND job_id = ?");
            $stmt->bind_param("ii", $uid, $jobId); $stmt->execute(); $stmt->close();
            $this->flash('success', 'Job removed from bookmarks.');
        } else {
            $stmt->close();
            $stmt = $db->prepare("INSERT INTO saved_jobs (user_id, job_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $uid, $jobId); $stmt->execute(); $stmt->close();
            $this->flash('success', 'Job saved!');
        }
        $ref = $_SERVER['HTTP_REFERER'] ?? BASE_URL . '/seeker/jobs';
        header('Location: ' . $ref); exit;
    }

    public function alerts() {
        $alertModel = new AlertModel(); $catModel = new CategoryModel();
        $alerts = $alertModel->getBySeeker(Auth::id());
        $categories = $catModel->getAll2();
        $pageTitle = 'Job Alerts'; $activePage = 'alerts';
        $this->view('seeker/alerts', compact('pageTitle','activePage','alerts','categories'));
    }

    public function createAlert() {
        Middleware::verifyCsrf();
        (new AlertModel())->create(Auth::id(), $this->input('keyword'), (int)$this->input('category_id') ?: null, $this->input('location'), $this->input('job_type') ?: null);
        $this->flash('success', 'Alert created!');
        $this->redirect('/seeker/alerts');
    }

    public function deleteAlert($id) {
        Middleware::verifyCsrf();
        (new AlertModel())->delete($id, Auth::id());
        $this->flash('success', 'Alert deleted.');
        $this->redirect('/seeker/alerts');
    }

    public function notifications() {
        $notifications = (new AlertModel())->getMatchingJobs(Auth::id());
        $pageTitle = 'Notifications'; $activePage = 'alerts';
        $this->view('seeker/notifications', compact('pageTitle','activePage','notifications'));
    }

    public function outreach() {
        $outreach = (new RecruiterModel())->getOutreachForSeeker(Auth::id());
        $pageTitle = 'Recruiter Outreach'; $activePage = 'outreach';
        $this->view('seeker/outreach', compact('pageTitle','activePage','outreach'));
    }

    public function respondOutreach($id) {
        Middleware::verifyCsrf();
        (new RecruiterModel())->updateOutreachStatus($id, 'responded');
        $this->flash('success', 'Response sent.');
        $this->redirect('/seeker/outreach');
    }

    public function complaints() {
        $complaints = (new ComplaintModel())->getBySubmitter(Auth::id());
        $pageTitle = 'Complaints'; $activePage = 'complaints';
        $this->view('seeker/complaints', compact('pageTitle','activePage','complaints'));
    }

    public function submitComplaint() {
        Middleware::verifyCsrf();
        $desc = $this->input('description');
        $subjectId = (int)$this->input('subject_id') ?: null;
        if (!$desc) { $this->flash('error', 'Description is required.'); $this->redirect('/seeker/complaints'); return; }
        (new ComplaintModel())->create(Auth::id(), $subjectId, $desc);
        $this->flash('success', 'Complaint submitted to admin.');
        $this->redirect('/seeker/complaints');
    }
}
