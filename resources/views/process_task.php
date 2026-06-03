<?php
session_start();
require 'db.php';

// Force authentication protection
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// ==========================================
// POST REQUEST PROCESSING (Form Submissions)
// ==========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // CORE CRUD: CREATE TASK
    if ($action === 'create') {
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);
        $due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : null;
        $status = $_POST['status'] ?? 'Pending';

        if (!empty($title)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO tasks (user_id, title, description, status, due_date) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$user_id, $title, $description, $status, $due_date]);
                $_SESSION['toast'] = ['type' => 'success', 'message' => 'Task created successfully!'];
            } catch (PDOException $e) {
                $_SESSION['toast'] = ['type' => 'danger', 'message' => 'Failed to create task.'];
            }
        }
        header("Location: tasks.php");
        exit;
    }

    // CORE CRUD: UPDATE TASK
    if ($action === 'update') {
        $task_id = $_POST['task_id'] ?? null;
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);
        $due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : null;
        $status = $_POST['status'] ?? 'Pending';

        if ($task_id && !empty($title)) {
            try {
                $stmt = $pdo->prepare("UPDATE tasks SET title = ?, description = ?, status = ?, due_date = ? WHERE id = ? AND user_id = ?");
                $stmt->execute([$title, $description, $status, $due_date, $task_id, $user_id]);
                $_SESSION['toast'] = ['type' => 'success', 'message' => 'Task updated successfully!'];
            } catch (PDOException $e) {
                $_SESSION['toast'] = ['type' => 'danger', 'message' => 'Failed to update task.'];
            }
        }
        header("Location: tasks.php");
        exit;
    }

    // SECOND CRUD: CREATE LOG RECORD
    if ($action === 'create_log') {
        $task_title = trim($_POST['task_title']);
        $log_type = $_POST['log_type'] ?? 'General Note';
        $remarks = trim($_POST['remarks']);

        if (!empty($task_title) && !empty($remarks)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO task_logs (user_id, task_title, log_type, remarks) VALUES (?, ?, ?, ?)");
                $stmt->execute([$user_id, $task_title, $log_type, $remarks]);
                $_SESSION['toast'] = ['type' => 'success', 'message' => 'Archive log recorded successfully!'];
            } catch (PDOException $e) {
                $_SESSION['toast'] = ['type' => 'danger', 'message' => 'Failed to create archive log.'];
            }
        }
        header("Location: tasks.php");
        exit;
    }

    // SECOND CRUD: UPDATE LOG RECORD
    if ($action === 'update_log') {
        $log_id = $_POST['log_id'] ?? null;
        $task_title = trim($_POST['task_title']);
        $log_type = $_POST['log_type'] ?? 'General Note';
        $remarks = trim($_POST['remarks']);

        if ($log_id && !empty($task_title) && !empty($remarks)) {
            try {
                $stmt = $pdo->prepare("UPDATE task_logs SET task_title = ?, log_type = ?, remarks = ? WHERE id = ? AND user_id = ?");
                $stmt->execute([$task_title, $log_type, $remarks, $log_id, $user_id]);
                $_SESSION['toast'] = ['type' => 'success', 'message' => 'Archive log updated successfully!'];
            } catch (PDOException $e) {
                $_SESSION['toast'] = ['type' => 'danger', 'message' => 'Failed to update log.'];
            }
        }
        header("Location: tasks.php");
        exit;
    }
}

// ==========================================
// GET REQUEST PROCESSING (URL Deletions)
// ==========================================
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    $id = $_GET['id'] ?? null;
    $action = $_GET['action'];

    if ($id) {
        if ($action === 'delete') {
            try {
                $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
                $stmt->execute([$id, $user_id]);
                $_SESSION['toast'] = ['type' => 'success', 'message' => 'Task deleted successfully.'];
            } catch (PDOException $e) {
                $_SESSION['toast'] = ['type' => 'danger', 'message' => 'Failed to delete task.'];
            }
        }
        
        if ($action === 'delete_log') {
            try {
                $stmt = $pdo->prepare("DELETE FROM task_logs WHERE id = ? AND user_id = ?");
                $stmt->execute([$id, $user_id]);
                $_SESSION['toast'] = ['type' => 'success', 'message' => 'Archive log removed successfully.'];
            } catch (PDOException $e) {
                $_SESSION['toast'] = ['type' => 'danger', 'message' => 'Failed to remove archive log.'];
            }
        }
    }
    header("Location: tasks.php");
    exit;
}

// Fallback safety route catch-all
header("Location: tasks.php");
exit;