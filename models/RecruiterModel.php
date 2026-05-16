<?php
class RecruiterModel extends Model {

    public function getProfile($userId) {
        return $this->getOne("SELECT * FROM recruiter_profiles WHERE user_id = ?", "i", [$userId]);
    }

    public function createProfile($userId) {
        return $this->insert("INSERT INTO recruiter_profiles (user_id) VALUES (?)", "i", [$userId]);
    }

    public function updateProfile($userId, $data) {
        return $this->execute(
            "UPDATE recruiter_profiles SET agency_name=?, specialization=?, description=?, website=? WHERE user_id=?",
            "ssssi",
            [$data['agency_name'], $data['specialization'], $data['description'], $data['website'], $userId]
        );
    }

    public function getClients($recruiterId) {
        return $this->getAll(
            "SELECT rc.*, ep.company_name as employer_company, u.name as employer_name 
             FROM recruiter_clients rc 
             LEFT JOIN users u ON rc.employer_id = u.id 
             LEFT JOIN employer_profiles ep ON rc.employer_id = ep.user_id
             WHERE rc.recruiter_id = ? ORDER BY rc.added_at DESC", "i", [$recruiterId]
        );
    }

    public function addClient($recruiterId, $employerId, $companyOverride) {
        return $this->insert(
            "INSERT INTO recruiter_clients (recruiter_id, employer_id, company_name_override) VALUES (?, ?, ?)",
            "iis", [$recruiterId, $employerId ?: null, $companyOverride]
        );
    }

    public function deleteClient($id, $recruiterId) {
        return $this->execute(
            "DELETE FROM recruiter_clients WHERE id = ? AND recruiter_id = ?", "ii", [$id, $recruiterId]
        );
    }

    public function getOutreach($recruiterId) {
        return $this->getAll(
            "SELECT ro.*, u.name as seeker_name, j.title as job_title 
             FROM recruiter_outreach ro 
             JOIN users u ON ro.seeker_id = u.id 
             LEFT JOIN jobs j ON ro.job_id = j.id
             WHERE ro.recruiter_id = ? ORDER BY ro.sent_at DESC", "i", [$recruiterId]
        );
    }

    public function sendOutreach($recruiterId, $seekerId, $jobId, $message) {
        return $this->insert(
            "INSERT INTO recruiter_outreach (recruiter_id, seeker_id, job_id, message) VALUES (?, ?, ?, ?)",
            "iiis", [$recruiterId, $seekerId, $jobId, $message]
        );
    }

    public function getOutreachForSeeker($seekerId) {
        return $this->getAll(
            "SELECT ro.*, u.name as recruiter_name, rp.agency_name, j.title as job_title 
             FROM recruiter_outreach ro 
             JOIN users u ON ro.recruiter_id = u.id 
             LEFT JOIN recruiter_profiles rp ON ro.recruiter_id = rp.user_id
             LEFT JOIN jobs j ON ro.job_id = j.id
             WHERE ro.seeker_id = ? ORDER BY ro.sent_at DESC", "i", [$seekerId]
        );
    }

    public function updateOutreachStatus($id, $status) {
        return $this->execute("UPDATE recruiter_outreach SET status = ? WHERE id = ?", "si", [$status, $id]);
    }

    public function getAllWithProfiles() {
        return $this->getAll(
            "SELECT u.*, rp.agency_name, rp.specialization FROM users u 
             LEFT JOIN recruiter_profiles rp ON u.id = rp.user_id 
             WHERE u.role = 'recruiter' ORDER BY u.created_at DESC"
        );
    }

    public function getOutreachStats($recruiterId) {
        return $this->getOne(
            "SELECT COUNT(*) as total, 
             SUM(CASE WHEN status = 'responded' THEN 1 ELSE 0 END) as responded,
             SUM(CASE WHEN status = 'read' THEN 1 ELSE 0 END) as read_count
             FROM recruiter_outreach WHERE recruiter_id = ?", "i", [$recruiterId]
        );
    }

    public function getClientById($id) {
        return $this->getOne("SELECT * FROM recruiter_clients WHERE id = ?", "i", [$id]);
    }
}
