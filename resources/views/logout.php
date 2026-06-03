<?php
session_start();

// 1. Clear all session variables
$_SESSION = array();

// 2. Destroy the session cookie if it exists (highly recommended for absolute security)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), 
        '', 
        time() - 42000,
        $params["path"], 
        $params["domain"],
        $params["secure"], 
        $params["httponly"]
    );
}

// 3. Destroy the actual session on the server
session_destroy();

// 4. Start a brand new temporary session context just to deliver the logout toast message
session_start();
$_SESSION['toast'] = ['type' => 'success', 'message' => 'You have been logged out successfully.'];

// 5. Redirect back to the login page
header("Location: login.php");
exit;