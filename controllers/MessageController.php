<?php
class MessageController extends Controller {
    public function seekerInbox() {
        Middleware::requireRole('seeker');
        $messages = (new MessageModel())->getInbox(Auth::id());
        // Mark all as read
        foreach($messages as $m) { if(!$m['is_read']) (new MessageModel())->markRead($m['id']); }
        $pageTitle = 'Messages'; $activePage = 'messages';
        $this->view('seeker/messages', compact('pageTitle','activePage','messages'));
    }
    public function employerInbox() {
        Middleware::requireRole('employer');
        $messages = (new MessageModel())->getInbox(Auth::id());
        foreach($messages as $m) { if(!$m['is_read']) (new MessageModel())->markRead($m['id']); }
        $pageTitle = 'Messages'; $activePage = 'messages';
        $this->view('employer/messages', compact('pageTitle','activePage','messages'));
    }
    public function reply() {
        Middleware::requireAuth(); Middleware::verifyCsrf();
        $recipientId = (int)$_POST['recipient_id'];
        $body = trim($_POST['body'] ?? '');
        $appId = (int)($_POST['application_id'] ?? 0) ?: null;
        if ($recipientId && $body) {
            (new MessageModel())->send(Auth::id(), $recipientId, $appId, $body);
        }
        $ref = $_SERVER['HTTP_REFERER'] ?? BASE_URL . '/' . Auth::role() . '/messages';
        header('Location: ' . $ref); exit;
    }
    public function send() {
        Middleware::requireAuth(); Middleware::verifyCsrf();
        $recipientId = (int)$_POST['recipient_id'];
        $body = trim($_POST['body'] ?? '');
        $appId = (int)($_POST['application_id'] ?? 0) ?: null;
        if ($recipientId && $body) {
            (new MessageModel())->send(Auth::id(), $recipientId, $appId, $body);
            $this->flash('success', 'Message sent!');
        }
        $ref = $_SERVER['HTTP_REFERER'] ?? BASE_URL . '/' . Auth::role() . '/messages';
        header('Location: ' . $ref); exit;
    }
}
