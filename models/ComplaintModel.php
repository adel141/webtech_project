<?php
class ComplaintModel extends Model {
    public function create($submitterId, $subjectId, $description) {
        return $this->insert("INSERT INTO complaints (submitter_id, subject_id, description) VALUES (?, ?, ?)", "iis", [$submitterId, $subjectId ?: null, $description]);
    }
    public function getBySubmitter($id) { return $this->getAll("SELECT c.*, u.name as subject_name FROM complaints c LEFT JOIN users u ON c.subject_id = u.id WHERE c.submitter_id = ? ORDER BY c.created_at DESC", "i", [$id]); }
    public function getAll2() { return $this->getAll("SELECT c.*, u1.name as submitter_name, u1.role as submitter_role, u2.name as subject_name FROM complaints c JOIN users u1 ON c.submitter_id = u1.id LEFT JOIN users u2 ON c.subject_id = u2.id ORDER BY c.created_at DESC"); }
    public function resolve($id, $note) { return $this->execute("UPDATE complaints SET status = 'resolved', admin_note = ? WHERE id = ?", "si", [$note, $id]); }
    public function countOpen() { return $this->count("SELECT COUNT(*) FROM complaints WHERE status = 'open'"); }
}
