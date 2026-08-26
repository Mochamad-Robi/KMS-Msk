<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentRead;
use Illuminate\Support\Facades\Auth;
use App\Models\AuditLog;

class DocumentController extends Controller
{
    private function getDocuments(string $category)
    {
        $user = Auth::user();

        return Document::where('category', $category)
                    ->where('is_active', true)
                   ->when(!$user->isSuperUser() && !$user->isAdmin(), function ($q) use ($user) {
                        // Filter grade hanya kalau min_grade_id tidak null
                        $q->where(function ($q2) use ($user) {
                            $q2->whereNull('min_grade_id')
                            ->orWhereHas('minGrade', function ($q3) use ($user) {
                                $q3->where('level', '>=', $user->grade?->level ?? 999);
                            });
                        });
                        // Filter department
                        $q->where(function ($q2) use ($user) {
                            $q2->whereNull('department_id')
                            ->orWhere('department_id', $user->department_id);
                        });
                        // Filter jabatan
                        $q->where(function ($q2) use ($user) {
                            $q2->whereNull('position_id')
                            ->orWhere('position_id', $user->position_id);
                        });
                    })
                    ->latest()
                    ->get();
    }

    public function policy()
    {
        $documents = $this->getDocuments('policy');
        $title     = 'Policy';
        return view('documents.index', compact('documents', 'title'));
    }

    public function roles()
{
    $user = Auth::user();
    $activeSubCategory = request('sub_category', 'all');

    $documents = $this->getDocuments('roles');

    if ($activeSubCategory !== 'all') {
        $documents = $documents->filter(fn($d) => $d->sub_category === $activeSubCategory);
    }

    $title = 'Roles & Responsibilities';
    $subCategories = \App\Models\Document::SUB_CATEGORIES_ROLES;

    return view('documents.index', compact('documents', 'title', 'subCategories', 'activeSubCategory'));
}

    public function employeeInfo()
    {
        $documents = $this->getDocuments('employee-info');
        $title     = 'Employee Information Center';
        return view('documents.index', compact('documents', 'title'));
    }

    public function knowledgeBase()
    {
        $documents = $this->getDocuments('knowledge-base');
        $title     = 'Department Knowledge Base';
        return view('documents.index', compact('documents', 'title'));
    }

     public function explicitKnowledge()
    {
        $activeSubCategory = request('sub_category', 'all');

        $documents = $this->getDocuments('explicit-knowledge');

        if ($activeSubCategory !== 'all') {
            $documents = $documents->filter(fn($d) => $d->sub_category === $activeSubCategory);
        }

        $title         = 'Explicit Knowledge';
        $subCategories = Document::SUB_CATEGORIES_EXPLICIT;

        return view('documents.index', compact('documents', 'title', 'subCategories', 'activeSubCategory'));
    }

    public function tacitKnowledge()
    {
        $activeSubCategory = request('sub_category', 'all');

        $documents = $this->getDocuments('tacit-knowledge');

        if ($activeSubCategory !== 'all') {
            $documents = $documents->filter(fn($d) => $d->sub_category === $activeSubCategory);
        }

        $title         = 'Tacit Knowledge';
        $subCategories = Document::SUB_CATEGORIES_TACIT;

        return view('documents.index', compact('documents', 'title', 'subCategories', 'activeSubCategory'));
    }

    public function knowledgeMap()
    {
        $documents = $this->getDocuments('knowledge-map');
        $title     = 'Knowledge Map';
        return view('documents.index', compact('documents', 'title'));
    }

    public function show($id)
    {
        $user     = Auth::user();
        $document = Document::where('is_active', true)->findOrFail($id);

        // Cek akses via canAccessDocument()
        if (!$user->canAccessDocument($document)) {
            abort(403, 'Anda tidak memiliki akses ke dokumen ini.');
        }

        // Catat sebagai sudah dibaca
        DocumentRead::firstOrCreate(
            ['document_id' => $document->id, 'user_id' => $user->id],
            [
                'read_at'        => now(),
                'watermark_text' => $user->name . ' | ' . $user->employee_id,
                'ip_address'     => request()->ip(),
            ]
        );

        AuditLog::record(
            'view_document',
            'document',
            "Membuka dokumen: {$document->title}",
            ['document_id' => $document->id, 'title' => $document->title, 'category' => $document->category]
        );

        $watermark = $user->name . ' | ' . $user->employee_id . ' | ' . now()->format('d/m/Y H:i');

        return view('documents.show', compact('document', 'watermark'));
    }

    public function servePdf($id)
    {
        $user     = Auth::user();
        $document = Document::where('is_active', true)->findOrFail($id);

        if (!$user->canAccessDocument($document)) {
            abort(403);
        }

        $path = storage_path('app/' . $document->file_path);

        if (!file_exists($path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->file($path, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline',
            'Cache-Control'       => 'no-store, no-cache',
        ]);
    }

    public function acknowledge($id)
    {
        $user     = Auth::user();
        $document = Document::where('is_active', true)->findOrFail($id);

        if (!$document->requires_acknowledgement) {
            return back()->with('error', 'Dokumen ini tidak memerlukan acknowledgement.');
        }

         DocumentRead::updateOrCreate(
            ['document_id' => $document->id, 'user_id' => $user->id],
            [
                'read_at'          => now(),
                'acknowledged_at'  => now(),
                'watermark_text'   => $user->name . ' | ' . $user->employee_id,
                'ip_address'       => request()->ip(),
            ]
        );

        AuditLog::record(
            'acknowledge_document',
            'document',
            "Acknowledge dokumen: {$document->title}",
            ['document_id' => $document->id]
        );

        return back()->with('success', 'Dokumen berhasil di-acknowledge!');
    }

    public function download($id)
    {
        $user     = Auth::user();
        $document = Document::where('is_active', true)->findOrFail($id);

        if (!$user->isAdmin() && !$user->isSuperUser()) {
            abort(403, 'Anda tidak memiliki akses untuk download.');
        }

        $path = storage_path('app/' . $document->file_path);

        if (!file_exists($path)) {
            abort(404, 'File tidak ditemukan.');
        }

        AuditLog::record(
            'download_document',
            'document',
            "Download dokumen: {$document->title}",
            ['document_id' => $document->id]
        );

        return response()->download(
            $path,
            $document->title . '.' . pathinfo($path, PATHINFO_EXTENSION)
        );
    }
}