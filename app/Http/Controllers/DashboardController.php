<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Fetch task status aggregations using Eloquent group by
        $statusCounts = Task::where('user_id', $user->id)
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // 2. Ensure all keys exist with default values if they are empty
        $statusCounts = array_merge([
            'Pending' => 0, 
            'In Progress' => 0, 
            'Completed' => 0
        ], $statusCounts);

        // 3. Compute metric calculations
        $totalTasks = array_sum($statusCounts);
        
        // Calculate completion percentage safely to avoid division by zero
        $completionRate = $totalTasks > 0 
            ? round(($statusCounts['Completed'] / $totalTasks) * 100) 
            : 0;

        // 4. Pass everything directly into our upcoming dashboard blade view
        return view('dashboard', compact('statusCounts', 'totalTasks', 'completionRate', 'user'));
    }
}