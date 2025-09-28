<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        // Filter by module
        if ($request->has('module')) {
            $query->where('module', $request->module);
        }

        // Filter by activity type
        if ($request->has('activity_type')) {
            $query->where('activity_type', $request->activity_type);
        }

        // Filter by date range
        if ($request->has('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->has('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Filter by user
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $logs = $query->paginate(25);

        return view('logs.index', compact('logs'));
    }

    public function show(ActivityLog $log)
    {
        return view('logs.show', compact('log'));
    }

    public function export(Request $request)
    {
        $query = ActivityLog::with('user')->latest();
        
        // Apply filters similar to index method
        // ... (similar filtering logic)

        $logs = $query->get();

        return response()->streamDownload(function () use ($logs) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, [
                'Tanggal',
                'User',
                'Modul',
                'Tipe Aktivitas',
                'Deskripsi',
                'IP Address'
            ]);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->user->name ?? 'System',
                    $log->module,
                    $log->activity_type,
                    $log->description,
                    $log->ip_address
                ]);
            }

            fclose($file);
        }, 'activity_logs_' . date('Y-m-d') . '.csv');
    }
}