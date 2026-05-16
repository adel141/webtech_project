<?php
class UserModel extends Model {

    public function findByEmail($email) {
        return $this->getOne("SELECT * FROM users WHERE email = ?", "s", [$email]);
    }

    public function findById($id) {
        return $this->getOne("SELECT * FROM users WHERE id = ?", "i", [$id]);
    }

    public function create($name, $email, $passwordHash, $phone, $role) {
        return $this->insert(
            "INSERT INTO users (name, email, password_hash, phone, role, is_verified) VALUES (?, ?, ?, ?, ?, ?)",
            "sssssi",
            [$name, $email, $passwordHash, $phone, $role, ($role === 'seeker' ? 1 : 0)]
        );
    }

    public function updateProfilePic($userId, $path) {
        return $this->execute("UPDATE users SET profile_pic = ? WHERE id = ?", "si", [$path, $userId]);
    }

    public function getAllByRole($role) {
        return $this->getAll("SELECT * FROM users WHERE role = ? ORDER BY created_at DESC", "s", [$role]);
    }

    public function countByRole($role) {
        return $this->count("SELECT COUNT(*) FROM users WHERE role = ?", "s", [$role]);
    }

    public function setVerified($id, $verified) {
        return $this->execute("UPDATE users SET is_verified = ? WHERE id = ?", "ii", [$verified, $id]);
    }

    public function setActive($id, $active) {
        return $this->execute("UPDATE users SET is_active = ? WHERE id = ?", "ii", [$active, $id]);
    }

    public function search($query, $role = null) {
        $sql = "SELECT * FROM users WHERE (name LIKE ? OR email LIKE ?)";
        $types = "ss";
        $params = ["%$query%", "%$query%"];
        if ($role) {
            $sql .= " AND role = ?";
            $types .= "s";
            $params[] = $role;
        }
        $sql .= " ORDER BY created_at DESC";
        return $this->getAll($sql, $types, $params);
    }

    public function getGrowthByMonth() {
        return $this->getAll(
            "SELECT role, DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count 
             FROM users GROUP BY role, month ORDER BY month DESC LIMIT 60"
        );
    }

    public function getTotalCount() {
        return $this->count("SELECT COUNT(*) FROM users");
    }
}
