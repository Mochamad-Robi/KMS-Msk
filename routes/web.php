<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\KpiController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\NewsCommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\FaqController;


// ── Guest routes ──────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',       [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login',      [LoginController::class, 'login']);
    Route::get('/2fa/verify',  [TwoFactorController::class, 'showVerify'])->name('2fa.verify');
    Route::post('/2fa/verify', [TwoFactorController::class, 'verify']);

    Route::get('/captcha/refresh', function () {
        return response()->json(['captcha' => captcha_src('flat')]);
    })->name('captcha.refresh');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ── Protected routes ──────────────────────────────────────
Route::middleware(['auth', 'active'])->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Documents
    Route::get('/policy',             [DocumentController::class, 'policy'])->name('documents.policy');
    Route::get('/roles',              [DocumentController::class, 'roles'])->name('documents.roles');
    Route::get('/employee-info',      [DocumentController::class, 'employeeInfo'])->name('documents.employee-info');
    Route::get('/knowledge-base',     [DocumentController::class, 'knowledgeBase'])->name('documents.knowledge-base');
    Route::get('/explicit-knowledge', [DocumentController::class, 'explicitKnowledge'])->name('documents.explicit-knowledge');
    Route::get('/tacit-knowledge',    [DocumentController::class, 'tacitKnowledge'])->name('documents.tacit-knowledge');
    Route::get('/knowledge-map',      [DocumentController::class, 'knowledgeMap'])->name('documents.knowledge-map');
    Route::get('/documents/{id}',     [DocumentController::class, 'show'])->name('documents.show');
    Route::get('/documents/{id}/pdf', [DocumentController::class, 'servePdf'])->name('documents.pdf');

    // KPI
    Route::get('/kpi',                              [KpiController::class, 'index'])->name('kpi.index');
    Route::post('/kpi',                             [KpiController::class, 'store'])->name('kpi.store');
    Route::get('/kpi/download',                     [KpiController::class, 'downloadUser'])->name('kpi.download.user');
    Route::get('/kpi/download/{userId}',            [KpiController::class, 'downloadUser'])->name('kpi.download.user.id');
    Route::get('/admin/kpi/download-dept/{deptId}', [KpiController::class, 'downloadDept'])->name('kpi.download.dept');
    // Kadept KPI
    Route::get('/kpi/kadept', [App\Http\Controllers\KadeptKpiController::class, 'index'])->name('kpi.kadept.index');
    Route::get('/kpi/kadept/form/{employeeId}', [App\Http\Controllers\KadeptKpiController::class, 'showForm'])->name('kpi.kadept.form');
    Route::post('/kpi/kadept/form/{employeeId}', [App\Http\Controllers\KadeptKpiController::class, 'saveForm'])->name('kpi.kadept.save');
    Route::get('/kpi/definisi-core-value', [App\Http\Controllers\KpiCoreValueController::class, 'index'])->name('kpi.definisi-core-value');
    

    // 2FA Setup
    Route::get('/2fa/setup',                [TwoFactorController::class, 'showSetup'])->name('2fa.setup');
    Route::post('/2fa/setup',               [TwoFactorController::class, 'enableSetup'])->name('2fa.enable');
    Route::post('/2fa/disable',             [TwoFactorController::class, 'disable'])->name('2fa.disable');

    // Media serve
    Route::get('/media/{type}/{filename}', function ($type, $filename) {
        $path = storage_path('app/' . $type . '/' . $filename);
        if (!file_exists($path)) abort(404);
        $mime = mime_content_type($path);
        return response()->file($path, ['Content-Type' => $mime]);
    })->name('media.serve');

    // Notifications
    Route::get('/notifications',           [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}/read', [NotificationController::class, 'read'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.readAll');

    // Portal News
    Route::get('/news',                    [NewsController::class, 'index'])->name('news.index');
    Route::get('/news/{id}',               [NewsController::class, 'show'])->name('news.show');
    Route::post('/news/{id}/comment',      [NewsController::class, 'comment'])->name('news.comment');
    Route::post('/news/comment/{id}/like', [NewsController::class, 'likeComment'])->name('news.comment.like');

    // News Comments
    Route::post('/news/{news}/comments',         [NewsCommentController::class, 'store'])->name('news.comments.store');
    Route::post('/news/comments/{comment}/like', [NewsCommentController::class, 'like'])->name('news.comments.like');
    Route::delete('/news/comments/{comment}',    [NewsCommentController::class, 'destroy'])->name('news.comments.destroy');

    // Birthday
    Route::post('/birthday/claim/{id}', [DashboardController::class, 'claimGift'])->name('birthday.claim');

    // Language
    Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

    // Profile
    Route::get('/profile',  [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Bookmark
    Route::get('/bookmarks',                       [BookmarkController::class, 'index'])->name('bookmarks.index');
    Route::post('/bookmarks/{documentId}/toggle',  [BookmarkController::class, 'toggle'])->name('bookmarks.toggle');

    // Acknowledge Document
    Route::post('/documents/{id}/acknowledge', [DocumentController::class, 'acknowledge'])->name('documents.acknowledge');

    // FAQ User
    Route::get('/faq', [FaqController::class, 'index'])->name('faq.index');

    Route::get('/documents/{id}/download', [DocumentController::class, 'download'])->name('documents.download');

    Route::post('/birthday-wish/{user}', [\App\Http\Controllers\BirthdayWishController::class, 'store'])
    ->name('birthday.wish.store');

    // ── Admin only ────────────────────────────────────────
    Route::prefix('admin')->name('admin.')->middleware('role:admin,super-user')->group(function () {

        // Users
        Route::resource('users', UserController::class);
        Route::patch('users/{id}/toggle',           [UserController::class, 'toggleActive'])->name('users.toggle');
        Route::patch('users/{id}/birthday-message', [UserController::class, 'updateBirthdayMessage'])->name('users.birthday-message');

        // Documents
        Route::resource('documents', \App\Http\Controllers\Admin\DocumentController::class)
             ->except(['edit', 'update', 'show']);
        Route::patch('documents/{id}/toggle', [\App\Http\Controllers\Admin\DocumentController::class, 'toggle'])
             ->name('documents.toggle');

        // Announcements
        Route::get('announcements',                [AnnouncementController::class, 'index'])->name('announcements.index');
        Route::post('announcements',               [AnnouncementController::class, 'store'])->name('announcements.store');
        Route::patch('announcements/{id}/toggle',  [AnnouncementController::class, 'toggle'])->name('announcements.toggle');
        Route::delete('announcements/{id}',        [AnnouncementController::class, 'destroy'])->name('announcements.destroy');

        // News
        Route::resource('news', \App\Http\Controllers\Admin\NewsController::class)
             ->except(['edit', 'update', 'show']);
        Route::patch('news/{id}/toggle', [\App\Http\Controllers\Admin\NewsController::class, 'toggle'])
             ->name('news.toggle');

        // Audit Log
        Route::get('audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');

        // Analytics
        Route::get('analytics', [\App\Http\Controllers\Admin\AnalyticsController::class, 'index'])
            ->name('analytics.index');
        
        Route::get('documents/{id}/acknowledgements', [\App\Http\Controllers\Admin\DocumentController::class, 'acknowledgements'])
        ->name('documents.acknowledgements');

        // FAQ Admin
        Route::resource('faqs', \App\Http\Controllers\Admin\FaqController::class)->except(['show']);
        Route::patch('faqs/{faq}/toggle', [\App\Http\Controllers\Admin\FaqController::class, 'toggle'])->name('faqs.toggle');

        Route::get('/documents/positions-by-department/{departmentId}', [\App\Http\Controllers\Admin\DocumentController::class, 'positionsByDepartment'])
        ->name('documents.positions-by-department');


        // Master Data
        Route::prefix('master')->name('master.')->group(function () {
            Route::resource('departments', \App\Http\Controllers\Admin\DepartmentController::class)
                ->except(['show']);
            Route::resource('positions', \App\Http\Controllers\Admin\PositionController::class)
                ->except(['show']);
            Route::resource('grades', \App\Http\Controllers\Admin\GradeController::class)
            ->except(['show']);
        });

        // KPI Periods
        Route::prefix('kpi/periods')->name('kpi.periods.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\KpiPeriodController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\Admin\KpiPeriodController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\KpiPeriodController::class, 'store'])->name('store');
            Route::patch('/{id}/toggle', [App\Http\Controllers\Admin\KpiPeriodController::class, 'toggleOpen'])->name('toggle');
            Route::delete('/{id}', [App\Http\Controllers\Admin\KpiPeriodController::class, 'destroy'])->name('destroy');
        });

        // KPI Quant Templates
        Route::prefix('kpi/templates')->name('kpi.templates.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\KpiQuantTemplateController::class, 'index'])->name('index');
            Route::post('/', [App\Http\Controllers\Admin\KpiQuantTemplateController::class, 'store'])->name('store');
            Route::put('/{id}', [App\Http\Controllers\Admin\KpiQuantTemplateController::class, 'update'])->name('update');
            Route::patch('/{id}/toggle', [App\Http\Controllers\Admin\KpiQuantTemplateController::class, 'toggle'])->name('toggle');
            Route::delete('/{id}', [App\Http\Controllers\Admin\KpiQuantTemplateController::class, 'destroy'])->name('destroy');
        });

        // KPI Assignments
        Route::prefix('kpi/assignments')->name('kpi.assignments.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\KpiAssignmentController::class, 'index'])->name('index');
            Route::get('/department/{deptId}', [App\Http\Controllers\Admin\KpiAssignmentController::class, 'showDepartment'])->name('department');
            Route::post('/assign', [App\Http\Controllers\Admin\KpiAssignmentController::class, 'assign'])->name('assign');
            Route::delete('/{id}', [App\Http\Controllers\Admin\KpiAssignmentController::class, 'unassign'])->name('unassign');
            Route::post('/department/{deptId}/auto-assign', [App\Http\Controllers\Admin\KpiAssignmentController::class, 'autoAssignDepartment'])->name('auto-assign');
            Route::get('/department/{deptId}/period/{periodId}', [App\Http\Controllers\Admin\KpiAssignmentController::class, 'showDepartmentPeriod'])->name('department.period');
        }); // end kpi/assignments

        // KPI Quality Assignments
        Route::prefix('kpi/quality-assignments')->name('kpi.quality-assignments.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\KpiQualityAssignmentController::class, 'index'])->name('index');
            Route::get('/department/{deptId}', [App\Http\Controllers\Admin\KpiQualityAssignmentController::class, 'showDepartment'])->name('department');
            Route::post('/assign', [App\Http\Controllers\Admin\KpiQualityAssignmentController::class, 'assignCross'])->name('assign');
            Route::delete('/{id}', [App\Http\Controllers\Admin\KpiQualityAssignmentController::class, 'unassignCross'])->name('unassign');
            Route::post('/sync-primary', [App\Http\Controllers\Admin\KpiQualityAssignmentController::class, 'syncPrimaryAssignments'])->name('sync-primary');
        }); // end kpi/quality-assignments

        Route::prefix('kpi/rekap')->name('kpi.rekap.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\KpiRekapController::class, 'index'])->name('index');
        Route::get('/{deptId}/{periodId}', [App\Http\Controllers\Admin\KpiRekapController::class, 'show'])->name('show');
        Route::get('/{deptId}/{periodId}/pdf', [App\Http\Controllers\Admin\KpiRekapController::class, 'downloadPdf'])->name('pdf');
        });

    }); // end admin prefix

}); // end middleware auth,active