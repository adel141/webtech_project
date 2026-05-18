<?php
class MessageModel extends Model {
    private $columns = null;

    private function columns() {
        if ($this->columns !== null) {
            return $this->columns;
        }

        $this->columns = [];
        $result = $this->db->query("SHOW COLUMNS FROM messages");
        while ($row = $result->fetch_assoc()) {
            $this->columns[$row['Field']] = true;
        }
        $result->free();

        return $this->columns;
    }

    private function hasColumn($column) {
        $columns = $this->columns();
        return isset($columns[$column]);
    }

    private function recipientColumn() {
        return $this->hasColumn('recipient_id') ? 'recipient_id' : 'receiver_id';
    }

    private function bodyColumn() {
        return $this->hasColumn('body') ? 'body' : 'message';
    }

    private function timeColumn() {
        return $this->hasColumn('sent_at') ? 'sent_at' : 'created_at';
    }

    private function readSelect() {
        return $this->hasColumn('is_read') ? 'm.is_read AS is_read' : '0 AS is_read';
    }

    public function send($senderId, $recipientId, $applicationId, $body) {
        $recipientColumn = $this->recipientColumn();
        $bodyColumn = $this->bodyColumn();

        if ($this->hasColumn('application_id')) {
            return $this->insert(
                "INSERT INTO messages (sender_id, {$recipientColumn}, application_id, {$bodyColumn}) VALUES (?, ?, ?, ?)",
                "iiis",
                [$senderId, $recipientId, $applicationId ?: null, $body]
            );
        }

        return $this->insert(
            "INSERT INTO messages (sender_id, {$recipientColumn}, {$bodyColumn}) VALUES (?, ?, ?)",
            "iis",
            [$senderId, $recipientId, $body]
        );
    }

    public function getInbox($userId) {
        $recipientColumn = $this->recipientColumn();
        $bodyColumn = $this->bodyColumn();
        $timeColumn = $this->timeColumn();
        $readSelect = $this->readSelect();

        return $this->getAll(
            "SELECT m.*, m.{$bodyColumn} AS body, m.{$timeColumn} AS sent_at, {$readSelect},
                    u.name as sender_name, u.profile_pic as sender_pic
             FROM messages m
             JOIN users u ON m.sender_id = u.id
             WHERE m.{$recipientColumn} = ?
             ORDER BY m.{$timeColumn} DESC",
            "i",
            [$userId]
        );
    }

    public function getSent($userId) {
        $recipientColumn = $this->recipientColumn();
        $bodyColumn = $this->bodyColumn();
        $timeColumn = $this->timeColumn();
        $readSelect = $this->readSelect();

        return $this->getAll(
            "SELECT m.*, m.{$bodyColumn} AS body, m.{$timeColumn} AS sent_at, {$readSelect},
                    u.name as recipient_name
             FROM messages m
             JOIN users u ON m.{$recipientColumn} = u.id
             WHERE m.sender_id = ?
             ORDER BY m.{$timeColumn} DESC",
            "i",
            [$userId]
        );
    }

    public function getThread($userId, $otherId) {
        $recipientColumn = $this->recipientColumn();
        $bodyColumn = $this->bodyColumn();
        $timeColumn = $this->timeColumn();
        $readSelect = $this->readSelect();

        return $this->getAll(
            "SELECT m.*, m.{$bodyColumn} AS body, m.{$timeColumn} AS sent_at, {$readSelect},
                    u.name as sender_name
             FROM messages m
             JOIN users u ON m.sender_id = u.id
             WHERE (m.sender_id = ? AND m.{$recipientColumn} = ?)
                OR (m.sender_id = ? AND m.{$recipientColumn} = ?)
             ORDER BY m.{$timeColumn} ASC",
            "iiii",
            [$userId, $otherId, $otherId, $userId]
        );
    }

    public function markRead($id) {
        if (!$this->hasColumn('is_read')) {
            return 0;
        }

        return $this->execute("UPDATE messages SET is_read = 1 WHERE id = ?", "i", [$id]);
    }

    public function countUnread($userId) {
        if (!$this->hasColumn('is_read')) {
            return 0;
        }

        $recipientColumn = $this->recipientColumn();
        return $this->count("SELECT COUNT(*) FROM messages WHERE {$recipientColumn} = ? AND is_read = 0", "i", [$userId]);
    }
}
