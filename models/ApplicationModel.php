<?php
class ApplicationModel extends Model {
    public function create($jobId, $seekerId, $recruiterId, $coverLetter, $resumePath) {
        return $this->insert("INSERT INTO applications (job_id, seeker_id, recruiter_id, cover_letter, resume_path) VALUES (?, ?, ?, ?, ?)", "iiiss", [$jobId, $seekerId, $recruiterId ?: null, $coverLetter, $resumePath]);
    }
    public function exists($jobId, $seekerId) {
        return $this->getOne("SELECT id FROM applications WHERE job_id = ? AND seeker_id = ?", "ii", [$jobId, $seekerId]);
    }
    public function getBySeekerWithJob($seekerId) {
        return $this->getAll("SELECT a.*, j.title, j.status as job_status, ep.company_name, j.location FROM applications a JOIN jobs j ON a.job_id = j.id LEFT JOIN employer_profiles ep ON j.employer_id = ep.user_id WHERE a.seeker_id = ? ORDER BY a.applied_at DESC", "i", [$seekerId]);
    }
    public function getByJob($jobId, $status = '') {
        $sql = "SELECT a.*, u.name as seeker_name, u.email as seeker_email, u.profile_pic, sp.headline, sp.skills, sp.years_experience FROM applications a JOIN users u ON a.seeker_id = u.id LEFT JOIN seeker_profiles sp ON a.seeker_id = sp.user_id WHERE a.job_id = ?";
        $types = "i"; $params = [$jobId];
        if ($status) { $sql .= " AND a.status = ?"; $types .= "s"; $params[] = $status; }
        $sql .= " ORDER BY a.applied_at DESC";
        return $this->getAll($sql, $types, $params);
    }
    public function findById($id) {
        return $this->getOne("SELECT a.*, j.title, j.employer_id, ep.company_name, u.name as seeker_name, u.email as seeker_email, sp.* FROM applications a JOIN jobs j ON a.job_id = j.id LEFT JOIN employer_profiles ep ON j.employer_id = ep.user_id JOIN users u ON a.seeker_id = u.id LEFT JOIN seeker_profiles sp ON a.seeker_id = sp.user_id WHERE a.id = ?", "i", [$id]);
    }
    public function updateStatus($id, $status) {
        return $this->execute("UPDATE applications SET status = ? WHERE id = ?", "si", [$status, $id]);
    }
    public function withdraw($id, $seekerId) {
        return $this->execute("UPDATE applications SET status = 'withdrawn' WHERE id = ? AND seeker_id = ? AND status = 'submitted'", "ii", [$id, $seekerId]);
    }
    public function getShortlistedByEmployer($empId) {
        return $this->getAll("SELECT a.*, j.title, u.name as seeker_name, u.email, sp.headline FROM applications a JOIN jobs j ON a.job_id = j.id JOIN users u ON a.seeker_id = u.id LEFT JOIN seeker_profiles sp ON a.seeker_id = sp.user_id WHERE j.employer_id = ? AND a.status IN ('shortlisted','interview') ORDER BY a.applied_at DESC", "i", [$empId]);
    }
    public function getFunnelByJob($jobId) {
        return $this->getAll("SELECT status, COUNT(*) as count FROM applications WHERE job_id = ? GROUP BY status", "i", [$jobId]);
    }
    public function countByEmployer($empId) {
        return $this->count("SELECT COUNT(*) FROM applications a JOIN jobs j ON a.job_id = j.id WHERE j.employer_id = ?", "i", [$empId]);
    }
    public function countToday() {
        return $this->count("SELECT COUNT(*) FROM applications WHERE DATE(applied_at) = CURDATE()");
    }
    public function countAll() { return $this->count("SELECT COUNT(*) FROM applications"); }
    public function getByRecruiterJobs($recId) {
        return $this->getAll("SELECT a.*, j.title, u.name as seeker_name, ep.company_name FROM applications a JOIN jobs j ON a.job_id = j.id JOIN users u ON a.seeker_id = u.id LEFT JOIN employer_profiles ep ON j.employer_id = ep.user_id WHERE j.recruiter_id = ? ORDER BY a.applied_at DESC", "i", [$recId]);
    }
    public function getPipelineByRecruiter($recId) {
        return $this->getAll("SELECT a.*, j.title, u.name as seeker_name, ep.company_name, j.employer_id FROM applications a JOIN jobs j ON a.job_id = j.id JOIN users u ON a.seeker_id = u.id LEFT JOIN employer_profiles ep ON j.employer_id = ep.user_id WHERE j.recruiter_id = ? AND a.status NOT IN ('rejected','withdrawn') ORDER BY a.status, a.applied_at DESC", "i", [$recId]);
    }
    public function getApplicationsOverTime($empId = 0) {
        $sql = "SELECT DATE(a.applied_at) as date, COUNT(*) as count FROM applications a";
        if ($empId) { $sql .= " JOIN jobs j ON a.job_id = j.id WHERE j.employer_id = ?"; }
        $sql .= " GROUP BY date ORDER BY date DESC LIMIT 30";
        return $empId ? $this->getAll($sql, "i", [$empId]) : $this->getAll($sql);
    }
}
