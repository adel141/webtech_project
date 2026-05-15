<?php
class CategoryModel extends Model {
    public function getAll2() { return $this->getAll("SELECT c.*, (SELECT COUNT(*) FROM jobs j WHERE j.category_id = c.id AND j.status='active') as job_count FROM categories c ORDER BY c.name"); }
    public function findById($id) { return $this->getOne("SELECT * FROM categories WHERE id = ?", "i", [$id]); }
    public function create($name, $desc) { return $this->insert("INSERT INTO categories (name, description) VALUES (?, ?)", "ss", [$name, $desc]); }
    public function update($id, $name, $desc) { return $this->execute("UPDATE categories SET name = ?, description = ? WHERE id = ?", "ssi", [$name, $desc, $id]); }
    public function delete($id) {
        $count = $this->count("SELECT COUNT(*) FROM jobs WHERE category_id = ? AND status = 'active'", "i", [$id]);
        if ($count > 0) return false;
        $this->execute("DELETE FROM categories WHERE id = ?", "i", [$id]);
        return true;
    }
}
