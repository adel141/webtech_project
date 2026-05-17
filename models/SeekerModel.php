<?php
class SeekerModel extends Model {

    public function getProfile($userId) {
        return $this->getOne("SELECT * FROM seeker_profiles WHERE user_id = ?", "i", [$userId]);
    }

    public function createProfile($userId) {
        return $this->insert("INSERT INTO seeker_profiles (user_id) VALUES (?)", "i", [$userId]);
    }

    public function updateProfile($userId, $data) {
        return $this->execute(
            "UPDATE seeker_profiles SET headline=?, summary=?, skills=?, years_experience=?, 
             education_level=?, current_salary=?, expected_salary=?, preferred_location=? WHERE user_id=?",
            "sssisddsi",
            [
                $data['headline'], $data['summary'], $data['skills'],
                $data['years_experience'], $data['education_level'],
                $data['current_salary'], $data['expected_salary'],
                $data['preferred_location'], $userId
            ]
        );
    }

    public function updateResume($userId, $path) {
        return $this->execute("UPDATE seeker_profiles SET resume_path = ? WHERE user_id = ?", "si", [$path, $userId]);
    }

    public function getFullProfile($userId) {
        return $this->getOne(
            "SELECT u.*, sp.* FROM users u 
             LEFT JOIN seeker_profiles sp ON u.id = sp.user_id 
             WHERE u.id = ?", "i", [$userId]
        );
    }

    public function searchSeekers($keyword, $location = '', $expLevel = '', $salaryMin = 0, $salaryMax = 0) {
        $sql = "SELECT u.id, u.name, u.email, u.profile_pic, sp.headline, sp.skills, sp.years_experience, 
                sp.education_level, sp.expected_salary, sp.preferred_location, sp.resume_path
                FROM users u 
                JOIN seeker_profiles sp ON u.id = sp.user_id 
                WHERE u.role = 'seeker' AND u.is_active = 1";
        $types = "";
        $params = [];

        if ($keyword) {
            $sql .= " AND (sp.skills LIKE ? OR sp.headline LIKE ? OR u.name LIKE ?)";
            $types .= "sss";
            $k = "%$keyword%";
            $params = array_merge($params, [$k, $k, $k]);
        }
        if ($location) {
            $sql .= " AND sp.preferred_location LIKE ?";
            $types .= "s";
            $params[] = "%$location%";
        }
        if ($expLevel) {
            $map = ['entry' => [0,2], 'mid' => [3,6], 'senior' => [7,99]];
            if (isset($map[$expLevel])) {
                $sql .= " AND sp.years_experience BETWEEN ? AND ?";
                $types .= "ii";
                $params[] = $map[$expLevel][0];
                $params[] = $map[$expLevel][1];
            }
        }
        if ($salaryMax > 0) {
            $sql .= " AND sp.expected_salary <= ?";
            $types .= "d";
            $params[] = $salaryMax;
        }

        $sql .= " ORDER BY u.created_at DESC LIMIT 50";
        return $this->getAll($sql, $types, $params);
    }
}
