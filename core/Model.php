<?php
/**
 * Base Model — Database access wrapper using mysqli prepared statements
 */
class Model {
    protected $db;

    public function __construct() {
        $this->db = getDB();
    }

    /**
     * Execute a prepared statement and return the statement object
     */
    protected function query($sql, $types = '', $params = []) {
        $stmt = $this->db->prepare($sql);
        if ($types && $params) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        return $stmt;
    }

    /**
     * Get a single row as associative array
     */
    protected function getOne($sql, $types = '', $params = []) {
        $stmt = $this->query($sql, $types, $params);
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row;
    }

    /**
     * Get all rows as associative array
     */
    protected function getAll($sql, $types = '', $params = []) {
        $stmt = $this->query($sql, $types, $params);
        $result = $stmt->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    /**
     * Insert and return the new ID
     */
    protected function insert($sql, $types = '', $params = []) {
        $stmt = $this->query($sql, $types, $params);
        $id = $stmt->insert_id;
        $stmt->close();
        return $id;
    }

    /**
     * Update/delete and return affected rows
     */
    protected function execute($sql, $types = '', $params = []) {
        $stmt = $this->query($sql, $types, $params);
        $affected = $stmt->affected_rows;
        $stmt->close();
        return $affected;
    }

    /**
     * Get count from a query
     */
    protected function count($sql, $types = '', $params = []) {
        $row = $this->getOne($sql, $types, $params);
        return $row ? (int) reset($row) : 0;
    }
}
