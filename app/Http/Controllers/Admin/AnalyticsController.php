<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Department;
use App\Models\Document;
use App\Models\DocumentRead;
use App\Models\News;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        // ── Dokumen paling banyak dibaca ──────────────────
        $topDocuments = DocumentRead::select('document_id', DB::raw('count(*) as total'))
            ->with('document:id,title,category')
            ->groupBy('document_id')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        // ── User paling aktif (dari audit_logs) ───────────
        $topUsers = AuditLog::select('user_id', DB::raw('count(*) as total'))
            ->with('user:id,name,employee_id,department_id')
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        // ── Departemen paling aktif ────────────────────────
        $topDepartments = AuditLog::select('users.department_id', DB::raw('count(*) as total'))
            ->join('users', 'audit_logs.user_id', '=', 'users.id')
            ->whereNotNull('users.department_id')
            ->groupBy('users.department_id')
            ->orderByDesc('total')
            ->take(5)
            ->get()
            ->map(function ($item) {
                $item->department = Department::find($item->department_id);
                return $item;
            });

        // ── Aktivitas per hari (7 hari terakhir) ──────────
        $activityByDay = AuditLog::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as total')
            )
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $last7Days = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $last7Days->push([
                'date'  => now()->subDays($i)->format('d M'),
                'total' => $activityByDay->get($date)?->total ?? 0,
            ]);
        }

        // ── Aktivitas per module ───────────────────────────
        $activityByModule = AuditLog::select('module', DB::raw('count(*) as total'))
            ->groupBy('module')
            ->orderByDesc('total')
            ->get();

        // ── Summary stats ──────────────────────────────────
        $stats = [
            'total_users'      => User::where('is_active', true)->count(),
            'total_documents'  => Document::where('is_active', true)->count(),
            'total_news'       => News::where('is_active', true)->count(),
            'total_activities' => AuditLog::count(),
            'reads_today'      => DocumentRead::whereDate('read_at', today())->count(),
            'active_today'     => AuditLog::whereDate('created_at', today())
                                    ->distinct('user_id')->count('user_id'),
        ];

        return view('admin.analytics.index', compact(
            'topDocuments',
            'topUsers',
            'topDepartments',
            'last7Days',
            'activityByModule',
            'stats'
        ));
    }
}