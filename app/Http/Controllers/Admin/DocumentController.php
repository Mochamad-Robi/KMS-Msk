<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Document;
use App\Models\Position;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Grade;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::with(['uploader', 'department'])
                        ->latest()
                        ->paginate(15);

        return view('admin.documents.index', compact('documents'));
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get();
        $positions   = Position::orderBy('name')->get();
        $grades      = Grade::active()->ordered()->get();

        return view('admin.documents.create', compact('departments', 'positions', 'grades'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'                    => 'required|string|max:255',
            'category'                 => 'required|in:policy,roles,knowledge-base',
            'sub_category'             => 'nullable|required_if:category,roles|in:struktur-organisasi,jobdesc',
            'type'                     => 'required|in:pdf,attachment,poster,info,banner',
            'file'                     => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png|max:20480',
            'department_id'            => 'nullable|exists:departments,id',
            'position_id'              => 'nullable|exists:positions,id',
            'min_grade_id'             => 'nullable|exists:grades,id',
            'description'              => 'nullable|string',
            'show_on_dashboard'        => 'nullable|boolean',
            'requires_acknowledgement' => 'nullable|boolean',
        ]);

        $file     = $request->file('file');
        $ext      = $file->getClientOriginalExtension();
        $filename = time() . '_' . str($request->title)->slug() . '.' . $ext;

        if (in_array($request->type, ['poster', 'info', 'banner'])) {
            $folder = 'posters';
        } elseif ($request->type === 'attachment') {
            $folder = 'attachments';
        } else {
            $folder = 'documents';
        }

        $path      = $file->storeAs($folder, $filename);
        $thumbnail = in_array($request->type, ['poster', 'info', 'banner']) ? $path : null;

        $document = Document::create([
            'title'                    => $request->title,
            'category'                 => $request->category,
            'sub_category'             => $request->category === 'roles' ? $request->sub_category : null,
            'type'                     => $request->type,
            'file_path'                => $path,
            'thumbnail_path'           => $thumbnail,
            'file_hash'                => hash_file('sha256', $file->getRealPath()),
            'uploaded_by'              => Auth::id(),
            'department_id'            => $request->department_id,
            'position_id'              => $request->department_id ? $request->position_id : null,
            'min_grade_id'             => $request->min_grade_id,
            'description'              => $request->description,
            'is_active'                => true,
            'show_on_dashboard'        => $request->boolean('show_on_dashboard'),
            'requires_acknowledgement' => $request->boolean('requires_acknowledgement'),
        ]);

        AuditLog::record(
            'upload_document',
            'document',
            "Upload dokumen: {$document->title}",
            ['document_id' => $document->id, 'title' => $document->title]
        );

        $this->notifyUsers($document);

        return redirect()->route('admin.documents.index')
                         ->with('success', 'Dokumen berhasil diupload!');
    }

    private function notifyUsers(Document $document): void
    {
        $users = \App\Models\User::where('is_active', true)
                    ->where('id', '!=', Auth::id())
                    ->where(function ($q) use ($document) {
                        // Admin & super-user selalu dapat notif
                        $q->whereHas('role', fn($r) => $r->whereIn('slug', ['admin', 'super-user']))
                          ->orWhere(function ($q2) use ($document) {
                              // Filter grade: skip kalau min_grade_id null = semua grade dapat notif
                              $q2->when($document->min_grade_id, function ($q3) use ($document) {
                                      $q3->whereHas('grade', function ($q4) use ($document) {
                                          $q4->where('level', '<=', $document->minGrade->level);
                                      });
                                  })
                                 // Filter dept
                                 ->when($document->department_id, function ($q3) use ($document) {
                                     $q3->where('department_id', $document->department_id);
                                 })
                                 // Filter jabatan
                                 ->when($document->position_id, function ($q3) use ($document) {
                                     $q3->where('position_id', $document->position_id);
                                 });
                          });
                    })
                    ->get();

        $notifs = $users->map(fn($user) => [
            'user_id'    => $user->id,
            'type'       => 'document',
            'title'      => '📄 Dokumen Baru',
            'message'    => "Dokumen baru telah ditambahkan: \"{$document->title}\"",
            'link'       => route('documents.show', $document->id),
            'is_read'    => false,
            'created_at' => now(),
            'updated_at' => now(),
        ])->toArray();

        \App\Models\Notification::insert($notifs);
    }

    public function destroy($id)
    {
        $document = Document::findOrFail($id);
        Storage::delete($document->file_path);
        $document->delete();

        return back()->with('success', 'Dokumen berhasil dihapus.');
    }

    public function toggle($id)
    {
        $document = Document::findOrFail($id);
        $document->update(['is_active' => !$document->is_active]);

        return back()->with('success', 'Status dokumen diperbarui.');
    }

    public function acknowledgements($id)
{
    $document = Document::with(['reads.user'])->findOrFail($id);

    $query = \App\Models\User::where('is_active', true);

    // Filter grade: hanya kalau min_grade_id tidak null
    if ($document->min_grade_id) {
        $query->whereHas('grade', function ($q) use ($document) {
            $q->where('level', '<=', $document->minGrade->level);
        });
    }

    // Filter dept: hanya kalau department_id tidak null
    if ($document->department_id) {
        $query->where('department_id', $document->department_id);
    }

    // Filter jabatan: hanya kalau position_id tidak null
    if ($document->position_id) {
        $query->where('position_id', $document->position_id);
    }

    $users = $query->get();

    $statuses = $users->map(function ($user) use ($document) {
        $read = $document->reads->firstWhere('user_id', $user->id);
        return [
            'user'            => $user,
            'read_at'         => $read?->read_at,
            'acknowledged_at' => $read?->acknowledged_at,
        ];
    });

    $acknowledgedCount = $statuses->whereNotNull('acknowledged_at')->count();
    $pendingCount      = $statuses->whereNull('acknowledged_at')->count();

    return view('admin.documents.acknowledgements', compact(
        'document', 'statuses', 'acknowledgedCount', 'pendingCount'
    ));
}

    public function positionsByDepartment($departmentId)
    {
        $positions = Position::where('department_id', $departmentId)
                        ->orderBy('name')
                        ->get(['id', 'name']);

        return response()->json($positions);
    }
}