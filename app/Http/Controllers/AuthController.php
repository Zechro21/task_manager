<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // 1. SHOW LOGIN PAGE
    public function showLogin()
    {
        return view('login');
    }

    // 2. HANDLE LOGIN SUBMISSION
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Laravel automatically verifies the password hash behind the scenes
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // Prevents session fixation attacks

            return redirect()->route('dashboard')->with('toast', [
                'type' => 'success',
                'message' => 'Welcome back, ' . Auth::user()->username . '!'
            ]);
        }

        return back()->with('toast', [
            'type' => 'danger',
            'message' => 'Invalid email or password.'
        ]);
    }

    // 3. SHOW REGISTER PAGE
    public function showRegister()
    {
        return view('register');
    }

    // 4. HANDLE REGISTER SUBMISSION
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|unique:users,username|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:6',
        ]);

        // Create user and safely hash the password using Hash::make
        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('toast', [
            'type' => 'success',
            'message' => 'Registration successful! Please login.'
        ]);
    }

    // 5. HANDLE LOGOUT
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('toast', [
            'type' => 'success',
            'message' => 'You have been logged out successfully.'
        ]);
    }
   // 1. ADD THIS METHOD TO FIX THE ERROR
    public function index()
    {
        // Fallback protection check if not using standard auth middleware
        $user_id = session('user_id') ?? Auth::id();
        if (!$user_id) {
            return redirect()->route('login'); // Change 'login' to your actual login route name if different
        }

        // Fetch active tasks (Pending and In Progress)
        $active_tasks = DB::select("SELECT * FROM tasks WHERE user_id = ? AND status IN ('Pending', 'In Progress') ORDER BY due_date ASC", [$user_id]);
        
        // Convert array objects to standard arrays so your existing foreach loop matches
        $active_tasks = json_decode(json_encode($active_tasks), true);

        // Fetch task progress notes
        $logs = DB::select("SELECT * FROM task_logs WHERE user_id = ? ORDER BY id DESC", [$user_id]);
        $logs = json_decode(json_encode($logs), true);

        // Fetch missed tasks (Past due date and not completed)
        $missed_tasks = DB::select("SELECT * FROM tasks WHERE user_id = ? AND status IN ('Pending', 'In Progress') AND due_date < CURDATE() ORDER BY due_date ASC", [$user_id]);
        $missed_tasks = json_decode(json_encode($missed_tasks), true);

        // Fetch completed tasks
        $completed_tasks = DB::select("SELECT * FROM tasks WHERE user_id = ? AND status = 'Completed' ORDER BY id DESC", [$user_id]);
        $completed_tasks = json_decode(json_encode($completed_tasks), true);

        // Return the view and pass all the query results into it
        return view('tasks', compact('active_tasks', 'logs', 'missed_tasks', 'completed_tasks'));
    }
}