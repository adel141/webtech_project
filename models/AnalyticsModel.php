<?php
class AnalyticsModel extends Model {
    public function getSettings() { return $this->getAll("SELECT * FROM settings"); }
    public function updateSetting($key, $value) { return $this->execute("UPDATE settings SET setting_value = ? WHERE setting_key = ?", "ss", [$value, $key]); }
    public function getSetting($key) { $r = $this->getOne("SELECT setting_value FROM settings WHERE setting_key = ?", "s", [$key]); return $r ? $r['setting_value'] : null; }
    public function getAnnouncements() { return $this->getAll("SELECT a.*, u.name as admin_name FROM announcements a JOIN users u ON a.admin_id = u.id ORDER BY a.created_at DESC"); }
    public function createAnnouncement($adminId, $title, $body) { return $this->insert("INSERT INTO announcements (admin_id, title, body) VALUES (?, ?, ?)", "iss", [$adminId, $title, $body]); }
    public function getRecentAnnouncements($limit = 5) { return $this->getAll("SELECT * FROM announcements ORDER BY created_at DESC LIMIT ?", "i", [$limit]); }
    public function getPopularLocations() { return $this->getAll("SELECT location, COUNT(*) as count FROM jobs WHERE status = 'active' AND location IS NOT NULL AND location != '' GROUP BY location ORDER BY count DESC LIMIT 10"); }
    public function getPopularJobTypes() { return $this->getAll("SELECT job_type, COUNT(*) as count FROM jobs WHERE status = 'active' GROUP BY job_type ORDER BY count DESC"); }
}
