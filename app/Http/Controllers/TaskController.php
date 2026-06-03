<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display the task manager dashboard view.
     */
    public function index()
    {
        // Session or Auth fallback checks
        $user_id = session('user_id') ?? Auth::id();
        if (!$user_id) {
            return redirect()->to('/login'); 
        }

        // 1. Fetch active tasks (Pending and In Progress)
        $active_tasks = DB::select("SELECT * FROM tasks WHERE user_id = ? AND status IN ('Pending', 'In Progress') ORDER BY due_date ASC", [$user_id]);
        $active_tasks = json_decode(json_encode($active_tasks), true);

        // 2. Fetch task progress notes
        $logs = DB::select("SELECT * FROM task_logs WHERE user_id = ? ORDER BY id DESC", [$user_id]);
        $logs = json_decode(json_encode($logs), true);

        // 3. Fetch missed tasks (Past due date and not completed)
        $missed_tasks = DB::select("SELECT * FROM tasks WHERE user_id = ? AND status IN ('Pending', 'In Progress') AND due_date < CURDATE() ORDER BY due_date ASC", [$user_id]);
        $missed_tasks = json_decode(json_encode($missed_tasks), true);

        // 4. Fetch completed tasks
        $completed_tasks = DB::select("SELECT * FROM tasks WHERE user_id = ? AND status = 'Completed' ORDER BY id DESC", [$user_id]);
        $completed_tasks = json_decode(json_encode($completed_tasks), true);

        // Return the dashboard template populated with our dataset array sets
        return view('tasks', compact('active_tasks', 'logs', 'missed_tasks', 'completed_tasks'));
    }

    /**
     * Handle Form POST submissions (Create, Update Tasks & Notes)
     */
    public function processTask(Request $request)
    {
        $user_id = session('user_id') ?? Auth::id();
        if (!$user_id) return redirect()->to('/login');

        $action = $request->input('action', '');

        // Action: Create Task
        if ($action === 'create') {
            $title = trim($request->input('title'));
            $description = trim($request->input('description'));
            $due_date = $request->filled('due_date') ? $request->input('due_date') : null;
            $status = $request->input('status', 'Pending');

            if (!empty($title)) {
                try {
                    DB::insert("INSERT INTO tasks (user_id, title, description, status, due_date) VALUES (?, ?, ?, ?, ?)", [
                        $user_id, $title, $description, $status, $due_date
                    ]);
                    Session::flash('toast', ['type' => 'success', 'message' => 'Task created successfully!']);
                } catch (\Exception $e) {
                    Session::flash('toast', ['type' => 'danger', 'message' => 'Failed to create task.']);
                }
            }
            return redirect()->back();
        }

        // Action: Update Task
        if ($action === 'update') {
            $task_id = $request->input('task_id');
            $title = trim($request->input('title'));
            $description = trim($request->input('description'));
            $due_date = $request->filled('due_date') ? $request->input('due_date') : null;
            $status = $request->input('status', 'Pending');

            if ($task_id && !empty($title)) {
                try {
                    DB::update("UPDATE tasks SET title = ?, description = ?, status = ?, due_date = ? WHERE id = ? AND user_id = ?", [
                        $title, $description, $status, $due_date, $task_id, $user_id
                    ]);
                    Session::flash('toast', ['type' => 'success', 'message' => 'Task updated successfully!']);
                } catch (\Exception $e) {
                    Session::flash('toast', ['type' => 'danger', 'message' => 'Failed to update task.']);
                }
            }
            return redirect()->back();
        }

        // Action: Create Progress Log Note
        if ($action === 'create_log') {
            $task_title = trim($request->input('task_title'));
            $log_type = $request->input('log_type', 'General Note');
            $remarks = trim($request->input('remarks'));

            if (!empty($task_title) && !empty($remarks)) {
                try {
                    DB::insert("INSERT INTO task_logs (user_id, task_title, log_type, remarks) VALUES (?, ?, ?, ?)", [
                        $user_id, $task_title, $log_type, $remarks
                    ]);
                    Session::flash('toast', ['type' => 'success', 'message' => 'Archive log recorded successfully!']);
                } catch (\Exception $e) {
                    Session::flash('toast', ['type' => 'danger', 'message' => 'Failed to create archive log.']);
                }
            }
            return redirect()->back();
        }
    }

    /**
     * Handle URL Deletions via GET Requests
     */
    public function processTaskGet(Request $request)
    {
        $user_id = session('user_id') ?? Auth::id();
        if (!$user_id) return redirect()->to('/login');

        $id = $request->input('id');
        $action = $request->input('action');

        if ($id) {
            if ($action === 'delete') {
                try {
                    DB::delete("DELETE FROM tasks WHERE id = ? AND user_id = ?", [$id, $user_id]);
                    Session::flash('toast', ['type' => 'success', 'message' => 'Task deleted successfully.']);
                } catch (\Exception $e) {
                    Session::flash('toast', ['type' => 'danger', 'message' => 'Failed to delete task.']);
                }
            }

            if ($action === 'delete_log') {
                try {
                    DB::delete("DELETE FROM task_logs WHERE id = ? AND user_id = ?", [$id, $user_id]);
                    Session::flash('toast', ['type' => 'success', 'message' => 'Archive log removed successfully.']);
                } catch (\Exception $e) {
                    Session::flash('toast', ['type' => 'danger', 'message' => 'Failed to remove archive log.']);
                }
            }
        }
        return redirect()->back();
    }
}