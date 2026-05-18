<?php
class EmployerModel extends Model {

    public function getProfile($userId) {
        return $this->getOne("SELECT * FROM employer_profiles WHERE user_id = ?", "i", [$userId]);
    }

    public function createProfile($userId) {
        return $this->insert("INSERT INTO employer_profiles (user_id) VALUES (?)", "i", [$userId]);
    }

    public function updateProfile($userId, $data) {
        return $this->execute(
            "UPDATE employer_profiles SET company_name=?, industry=?, company_size=?, 
             description=?, website=?, address=? WHERE user_id=?",
            "ssssssi",
            [$data['company_name'], $data['industry'], $data['company_size'],
             $data['description'], $data['website'], $data['address'], $userId]
        );
    }

    public function updateLogo($userId, $path) {
        return $this->execute("UPDATE employer_profiles SET logo_path = ? WHERE user_id = ?", "si", [$path, $userId]);
    }

    public function getFullProfile($userId) {
        return $this->getOne(
            "SELECT u.*, ep.* FROM users u 
             LEFT JOIN employer_profiles ep ON u.id = ep.user_id 
             WHERE u.id = ?", "i", [$userId]
        );
    }

    public function getCompanyInfo($employerId) {
        return $this->getOne(
            "SELECT ep.company_name, ep.industry, ep.logo_path, ep.description 
             FROM employer_profiles ep WHERE ep.user_id = ?", "i", [$employerId]
        );
    }

    public function getRecruitersForEmployer($employerId) {
        return $this->getAll(
            "SELECT rc.*, u.name, rp.agency_name FROM recruiter_clients rc 
             JOIN users u ON rc.recruiter_id = u.id 
             LEFT JOIN recruiter_profiles rp ON rc.recruiter_id = rp.user_id
             WHERE rc.employer_id = ?", "i", [$employerId]
        );
    }

    public function getAllWithProfiles() {
        return $this->getAll(
            "SELECT u.*, ep.company_name, ep.industry FROM users u 
             LEFT JOIN employer_profiles ep ON u.id = ep.user_id 
             WHERE u.role = 'employer' ORDER BY u.created_at DESC"
        );
    }

    public function getTopEmployers($limit = 5) {
        return $this->getAll(
            "SELECT ep.company_name, u.id, COUNT(a.id) as app_count 
             FROM users u 
             JOIN employer_profiles ep ON u.id = ep.user_id 
             JOIN jobs j ON j.employer_id = u.id 
             JOIN applications a ON a.job_id = j.id 
             WHERE u.role = 'employer'
             GROUP BY u.id ORDER BY app_count DESC LIMIT ?", "i", [$limit]
        );
    }
}
