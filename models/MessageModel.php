<?php
class MessageModel extends Model {
    public function send($senderId, $recipientId, $applicationId, $body) {
        return $this->insert("INSERT INTO messages (sender_id, recipient_id, application_id, body) VALUES (?, ?, ?, ?)", "iiis", [$senderId, $recipientId, $applicationId ?: null, $body]);
    }
    public function getInbox($userId) {
        return $this->getAll("SELECT m.*, u.name as sender_name, u.profile_pic as sender_pic FROM messages m JOIN users u ON m.sender_id = u.id WHERE m.recipient_id = ? ORDER BY m.sent_at DESC", "i", [$userId]);
    }
    public function getSent($userId) {
        return $this->getAll("SELECT m.*, u.name as recipient_name FROM messages m JOIN users u ON m.recipient_id = u.id WHERE m.sender_id = ? ORDER BY m.sent_at DESC", "i", [$userId]);
    }
    public function getThread($userId, $otherId) {
        return $this->getAll("SELECT m.*, u.name as sender_name FROM messages m JOIN users u ON m.sender_id = u.id WHERE (m.sender_id = ? AND m.recipient_id = ?) OR (m.sender_id = ? AND m.recipient_id = ?) ORDER BY m.sent_at ASC", "iiii", [$userId, $otherId, $otherId, $userId]);
    }
    public function markRead($id) { return $this->execute("UPDATE messages SET is_read = 1 WHERE id = ?", "i", [$id]); }
    public function countUnread($userId) { return $this->count("SELECT COUNT(*) FROM messages WHERE recipient_id = ? AND is_read = 0", "i", [$userId]); }
}
