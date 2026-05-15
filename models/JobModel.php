<?php
class JobModel extends Model {

    public function create($data) {
        return $this->insert(
            "INSERT INTO jobs (employer_id, recruiter_id, category_id, title, description, requirements, benefits, salary_min, salary_max, location, job_type, experience_level, deadline, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            "iiissssddsssss",
            [$data['employer_id'], $data['recruiter_id'] ?: null, $data['category_id'] ?: null, $data['title'], $data['description'], $data['requirements'], $data['benefits'], $data['salary_min'], $data['salary_max'], $data['location'], $data['job_type'], $data['experience_level'], $data['deadline'], $data['status']]
        );
    }

    public function update($id, $data) {
        return $this->execute(
            "UPDATE jobs SET category_id=?, title=?, description=?, requirements=?, benefits=?, salary_min=?, salary_max=?, location=?, job_type=?, experience_level=?, deadline=?, status=? WHERE id=?",
            "issssddsssssi",
            [$data['category_id'] ?: null, $data['title'], $data['description'], $data['requirements'], $data['benefits'], $data['salary_min'], $data['salary_max'], $data['location'], $data['job_type'], $data['experience_level'], $data['deadline'], $data['status'], $id]
        );
    }

    public function findById($id) {
        return $this->getOne(
            "SELECT j.*, c.name as category_name, ep.company_name, ep.logo_path, ep.industry, rp.agency_name, u_emp.name as employer_name FROM jobs j LEFT JOIN categories c ON j.category_id = c.id LEFT JOIN employer_profiles ep ON j.employer_id = ep.user_id LEFT JOIN recruiter_profiles rp ON j.recruiter_id = rp.user_id LEFT JOIN users u_emp ON j.employer_id = u_emp.id WHERE j.id = ?", "i", [$id]
        );
    }

    public function getActiveJobs($limit = 20, $offset = 0) {
        return $this->getAll(
            "SELECT j.*, c.name as category_name, ep.company_name, ep.logo_path FROM jobs j LEFT JOIN categories c ON j.category_id = c.id LEFT JOIN employer_profiles ep ON j.employer_id = ep.user_id WHERE j.status = 'active' ORDER BY j.is_featured DESC, j.created_at DESC LIMIT ? OFFSET ?", "ii", [$limit, $offset]
        );
    }

    public function filterJobs($keyword, $catId, $location, $jobType, $expLevel, $salMin, $salMax) {
        $sql = "SELECT j.*, c.name as category_name, ep.company_name, ep.logo_path FROM jobs j LEFT JOIN categories c ON j.category_id = c.id LEFT JOIN employer_profiles ep ON j.employer_id = ep.user_id WHERE j.status = 'active'";
        $types = ""; $params = [];
        if ($keyword) { $sql .= " AND (j.title LIKE ? OR j.description LIKE ? OR ep.company_name LIKE ?)"; $types .= "sss"; $k = "%$keyword%"; array_push($params, $k, $k, $k); }
        if ($catId) { $sql .= " AND j.category_id = ?"; $types .= "i"; $params[] = $catId; }
        if ($location) { $sql .= " AND j.location LIKE ?"; $types .= "s"; $params[] = "%$location%"; }
        if ($jobType) { $sql .= " AND j.job_type = ?"; $types .= "s"; $params[] = $jobType; }
        if ($expLevel) { $sql .= " AND j.experience_level = ?"; $types .= "s"; $params[] = $expLevel; }
        if ($salMin > 0) { $sql .= " AND j.salary_max >= ?"; $types .= "d"; $params[] = $salMin; }
        if ($salMax > 0) { $sql .= " AND j.salary_min <= ?"; $types .= "d"; $params[] = $salMax; }
        $sql .= " ORDER BY j.is_featured DESC, j.created_at DESC LIMIT 50";
        return $this->getAll($sql, $types, $params);
    }

    public function getByEmployer($empId) {
        return $this->getAll("SELECT j.*, c.name as category_name, (SELECT COUNT(*) FROM applications a WHERE a.job_id = j.id) as app_count FROM jobs j LEFT JOIN categories c ON j.category_id = c.id WHERE j.employer_id = ? ORDER BY j.created_at DESC", "i", [$empId]);
    }

    public function getByRecruiter($recId) {
        return $this->getAll("SELECT j.*, c.name as category_name, ep.company_name, (SELECT COUNT(*) FROM applications a WHERE a.job_id = j.id) as app_count FROM jobs j LEFT JOIN categories c ON j.category_id = c.id LEFT JOIN employer_profiles ep ON j.employer_id = ep.user_id WHERE j.recruiter_id = ? ORDER BY j.created_at DESC", "i", [$recId]);
    }

    public function toggleStatus($id, $status) { return $this->execute("UPDATE jobs SET status = ? WHERE id = ?", "si", [$status, $id]); }
    public function delete($id) { return $this->execute("DELETE FROM jobs WHERE id = ?", "i", [$id]); }
    public function setFeatured($id, $f) { return $this->execute("UPDATE jobs SET is_featured = ? WHERE id = ?", "ii", [$f, $id]); }
    public function countActive() { return $this->count("SELECT COUNT(*) FROM jobs WHERE status = 'active'"); }
    public function countAll() { return $this->count("SELECT COUNT(*) FROM jobs"); }

    public function getByCategory() {
        return $this->getAll("SELECT c.name, COUNT(j.id) as count FROM categories c LEFT JOIN jobs j ON j.category_id = c.id AND j.status = 'active' GROUP BY c.id ORDER BY count DESC");
    }

    public function getFeatured($limit = 5) {
        return $this->getAll("SELECT j.*, ep.company_name, ep.logo_path, c.name as category_name FROM jobs j LEFT JOIN employer_profiles ep ON j.employer_id = ep.user_id LEFT JOIN categories c ON j.category_id = c.id WHERE j.is_featured = 1 AND j.status = 'active' ORDER BY j.created_at DESC LIMIT ?", "i", [$limit]);
    }

    public function getAllAdmin($kw = '', $st = '', $empId = 0) {
        $sql = "SELECT j.*, c.name as category_name, ep.company_name, rp.agency_name FROM jobs j LEFT JOIN categories c ON j.category_id = c.id LEFT JOIN employer_profiles ep ON j.employer_id = ep.user_id LEFT JOIN recruiter_profiles rp ON j.recruiter_id = rp.user_id WHERE 1=1";
        $types = ""; $params = [];
        if ($kw) { $sql .= " AND (j.title LIKE ? OR ep.company_name LIKE ?)"; $types .= "ss"; $k = "%$kw%"; array_push($params, $k, $k); }
        if ($st) { $sql .= " AND j.status = ?"; $types .= "s"; $params[] = $st; }
        if ($empId) { $sql .= " AND j.employer_id = ?"; $types .= "i"; $params[] = $empId; }
        $sql .= " ORDER BY j.created_at DESC LIMIT 100";
        return $this->getAll($sql, $types, $params);
    }
}
