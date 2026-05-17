<?php
class AlertModel extends Model {
    public function create($seekerId, $keyword, $catId, $location, $jobType) {
        return $this->insert("INSERT INTO job_alerts (seeker_id, keyword, category_id, location, job_type) VALUES (?, ?, ?, ?, ?)", "isiss", [$seekerId, $keyword, $catId ?: null, $location, $jobType]);
    }
    public function getBySeeker($id) { return $this->getAll("SELECT ja.*, c.name as category_name FROM job_alerts ja LEFT JOIN categories c ON ja.category_id = c.id WHERE ja.seeker_id = ? ORDER BY ja.created_at DESC", "i", [$id]); }
    public function delete($id, $seekerId) { return $this->execute("DELETE FROM job_alerts WHERE id = ? AND seeker_id = ?", "ii", [$id, $seekerId]); }
    public function getMatchingJobs($seekerId) {
        return $this->getAll("SELECT DISTINCT j.*, c.name as category_name, ep.company_name FROM job_alerts ja JOIN jobs j ON j.status = 'active' AND (j.title LIKE CONCAT('%', ja.keyword, '%') OR j.description LIKE CONCAT('%', ja.keyword, '%') OR (ja.category_id IS NOT NULL AND j.category_id = ja.category_id) OR (ja.location IS NOT NULL AND ja.location != '' AND j.location LIKE CONCAT('%', ja.location, '%')) OR (ja.job_type IS NOT NULL AND j.job_type = ja.job_type)) LEFT JOIN categories c ON j.category_id = c.id LEFT JOIN employer_profiles ep ON j.employer_id = ep.user_id WHERE ja.seeker_id = ? AND j.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) ORDER BY j.created_at DESC LIMIT 20", "i", [$seekerId]);
    }
}
