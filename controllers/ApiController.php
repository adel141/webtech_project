<?php
class ApiController extends Controller {
    public function filterJobs() {
        $keyword = $this->query('keyword');
        $catId = (int)$this->query('category_id');
        $location = $this->query('location');
        $jobType = $this->query('job_type');
        $expLevel = $this->query('experience_level');
        $salMin = (float)$this->query('salary_min');
        $salMax = (float)$this->query('salary_max');
        $jobs = (new JobModel())->filterJobs($keyword,$catId,$location,$jobType,$expLevel,$salMin,$salMax);
        $this->json(['jobs'=>$jobs,'count'=>count($jobs)]);
    }

    public function toggleJobStatus() {
        Middleware::requireAuth();
        $jobId = (int)($_POST['job_id'] ?? 0);
        $status = $_POST['status'] ?? '';
        if (!in_array($status, ['active','closed','draft'])) { $this->json(['error'=>'Invalid status'], 400); }
        $job = (new JobModel())->findById($jobId);
        if (!$job) { $this->json(['error'=>'Job not found'], 404); }
        // Allow employer or recruiter who owns the job
        if ($job['employer_id'] != Auth::id() && $job['recruiter_id'] != Auth::id()) {
            $this->json(['error'=>'Unauthorized'], 403);
        }
        (new JobModel())->toggleStatus($jobId, $status);
        $this->json(['message'=>'Job status updated to '.$status,'status'=>$status]);
    }

    public function updateApplicationStatus() {
        Middleware::requireAuth();
        $appId = (int)($_POST['application_id'] ?? 0);
        $status = $_POST['status'] ?? '';
        if (!in_array($status, ['submitted','reviewed','shortlisted','interview','rejected'])) {
            $this->json(['error'=>'Invalid status'], 400);
        }
        $app = (new ApplicationModel())->findById($appId);
        if (!$app) { $this->json(['error'=>'Not found'], 404); }
        // Check ownership
        if ($app['employer_id'] != Auth::id() && Auth::role() !== 'recruiter') {
            $this->json(['error'=>'Unauthorized'], 403);
        }
        (new ApplicationModel())->updateStatus($appId, $status);
        $this->json(['message'=>'Application status updated to '.$status]);
    }

    public function searchSeekers() {
        Middleware::requireAuth();
        if (Auth::role() !== 'recruiter') { $this->json(['error'=>'Unauthorized'], 403); }
        $keyword = $this->query('keyword');
        $location = $this->query('location');
        $expLevel = $this->query('experience_level');
        $salMax = (float)$this->query('salary_max');
        $seekers = (new SeekerModel())->searchSeekers($keyword, $location, $expLevel, 0, $salMax);
        $this->json(['seekers'=>$seekers,'count'=>count($seekers)]);
    }

    public function createCategory() {
        Middleware::requireRole('admin');
        $name = trim($_POST['name'] ?? '');
        $desc = trim($_POST['description'] ?? '');
        if (!$name) { $this->json(['error'=>'Name is required'], 400); }
        $id = (new CategoryModel())->create($name, $desc);
        $this->json(['message'=>'Category created','id'=>$id]);
    }

    public function updateCategory() {
        Middleware::requireRole('admin');
        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $desc = trim($_POST['description'] ?? '');
        if (!$id || !$name) { $this->json(['error'=>'ID and name required'], 400); }
        (new CategoryModel())->update($id, $name, $desc);
        $this->json(['message'=>'Category updated']);
    }

    public function deleteCategory() {
        Middleware::requireRole('admin');
        $id = (int)($_POST['id'] ?? 0);
        $result = (new CategoryModel())->delete($id);
        if ($result === false) { $this->json(['error'=>'Cannot delete: active jobs reference this category'], 400); }
        $this->json(['message'=>'Category deleted']);
    }

    public function getNotifications() {
        Middleware::requireAuth();
        $notifications = (new AlertModel())->getMatchingJobs(Auth::id());
        $this->json(['notifications'=>$notifications,'count'=>count($notifications)]);
    }
}
