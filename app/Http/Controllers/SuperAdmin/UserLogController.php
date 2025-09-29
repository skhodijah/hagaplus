<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\UserLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UserLogController extends Controller
{
    /**
     * Display a listing of user logs.
     */
    public function index(Request $request): View
    {
        $query = UserLog::with('user')
            ->orderBy('created_at', 'desc');

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search in description
        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        $logs = $query->paginate(25);

        // Get unique actions for filter dropdown
        $actions = UserLog::distinct('action')->pluck('action');

        // Get users for filter dropdown
        $users = \App\Models\Core\User::select('id', 'name', 'email')->get();

        return view('superadmin.system.user-logs.index', compact('logs', 'actions', 'users'));
    }

    /**
     * Display the specified user log.
     */
    public function show(UserLog $userLog): View
    {
        $userLog->load('user');

        return view('superadmin.system.user-logs.show', compact('userLog'));
    }

    /**
     * Remove the specified user log from storage.
     */
    public function destroy(UserLog $userLog): RedirectResponse
    {
        $userLog->delete();

        return redirect()->route('superadmin.system.user-logs.index')
            ->with('success', 'User log deleted successfully.');
    }

    /**
     * Bulk delete user logs.
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:user_logs,id'
        ]);

        UserLog::whereIn('id', $request->ids)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Selected logs deleted successfully.'
        ]);
    }

    /**
     * Clear old logs (older than specified days).
     */
    public function clearOld(Request $request): RedirectResponse
    {
        $days = $request->get('days', 90);

        $count = UserLog::where('created_at', '<', now()->subDays($days))->delete();

        return redirect()->route('superadmin.system.user-logs.index')
            ->with('success', "Cleared {$count} logs older than {$days} days.");
    }

    /**
     * Export logs to CSV.
     */
    public function export(Request $request)
    {
        $query = UserLog::with('user')
            ->orderBy('created_at', 'desc');

        // Apply same filters as index
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        $logs = $query->get();

        $filename = 'user-logs-' . now()->format('Y-m-d-H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($logs) {
            $file = fopen('php://output', 'w');

            // CSV headers
            fputcsv($file, [
                'ID',
                'User',
                'Action',
                'Description',
                'Model Type',
                'Model ID',
                'IP Address',
                'Created At'
            ]);

            // CSV data
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->user->name ?? 'Unknown',
                    $log->action,
                    $log->description,
                    $log->model_type,
                    $log->model_id,
                    $log->ip_address,
                    $log->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
