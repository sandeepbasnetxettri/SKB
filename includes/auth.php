<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Authentication Helper Functions
 */

include_once 'db.php';

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function getCurrentUser() {
    return $_SESSION['user_name'] ?? 'Guest';
}

function loginUser($email, $password) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user'] = $user;
        return true;
    }
    return false;
}

function registerUser($name, $email, $password) {
    global $pdo;

    // Check if user exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        return false;
    }

    // Add new user
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')");
    return $stmt->execute([$name, $email, $hashed_password]);
}

function updateUserProfile($id, $name, $email) {
    global $pdo;
    
    // Check if new email is taken by another user
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $stmt->execute([$email, $id]);
    if ($stmt->fetch()) {
        return false;
    }

    $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
    if ($stmt->execute([$name, $email, $id])) {
        $_SESSION['user_name'] = $name;
        $_SESSION['user']['name'] = $name;
        $_SESSION['user']['email'] = $email;
        return true;
    }
    return false;
}

function logoutUser() {
    session_unset();
    session_destroy();
}
?>
