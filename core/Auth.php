<?php
/**
 * Auth — Session-based authentication
 */
class Auth {

    /**
     * Start session if not started
     */
    public static function init() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Log in a user — store essential info in session
     */
    public static function login($user) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_pic'] = $user['profile_pic'];
        $_SESSION['is_verified'] = $user['is_verified'];
    }

    /**
     * Log out — destroy session
     */
    public static function logout() {
        session_unset();
        session_destroy();
    }

    /**
     * Check if user is logged in
     */
    public static function check() {
        return isset($_SESSION['user_id']);
    }

    /**
     * Get current user ID
     */
    public static function id() {
        return $_SESSION['user_id'] ?? null;
    }

    /**
     * Get current user's role
     */
    public static function role() {
        return $_SESSION['user_role'] ?? null;
    }

    /**
     * Get current user's name
     */
    public static function name() {
        return $_SESSION['user_name'] ?? null;
    }

    /**
     * Get current user info array
     */
    public static function user() {
        if (!self::check()) return null;
        return [
            'id' => $_SESSION['user_id'],
            'name' => $_SESSION['user_name'],
            'email' => $_SESSION['user_email'],
            'role' => $_SESSION['user_role'],
            'profile_pic' => $_SESSION['user_pic'] ?? null,
            'is_verified' => $_SESSION['is_verified'] ?? 0
        ];
    }

    /**
     * Check if current user is verified
     */
    public static function isVerified() {
        return ($_SESSION['is_verified'] ?? 0) == 1;
    }
}
