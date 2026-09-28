<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\BirthdayGift;
use App\Models\Document;
use App\Models\Notification;
use App\Models\User;
use App\Models\News;
use Illuminate\Support\Facades\Auth;
use App\Notifications\PushNotification;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $hour = now()->hour;

        if ($hour >= 6 && $hour < 11) {
            $greeting = 'Selamat Pagi';
        } elseif ($hour >= 11 && $hour < 15) {
            $greeting = 'Selamat Siang';
        } elseif ($hour >= 15 && $hour < 18) {
            $greeting = 'Selamat Sore';
        } else {
            $greeting = 'Selamat Malam';
        }

        $announcements = Announcement::active()
                            ->latest('publish_at')
                            ->take(5)
                            ->get();

        $notifications = Notification::where('user_id', $user->id)
                            ->where('is_read', false)
                            ->latest()
                            ->take(5)
                            ->get();

        $recentDocuments = Document::where('is_active', true)
                            ->where('type', 'pdf')
                            ->when(!$user->isSuperUser() && !$user->isAdmin(), function ($q) use ($user) {
                                $q->where(function ($q2) use ($user) {
                                    $q2->whereNull('min_grade_id')
                                    ->orWhereHas('minGrade', function ($q3) use ($user) {
                                        $q3->where('level', '>=', $user->grade?->level ?? 999);
                                    });
                                });
                            })
                            ->latest()
                            ->take(6)
                            ->get();

        $totalDocuments = Document::where('is_active', true)
                            ->where('type', 'pdf')
                            ->when(!$user->isSuperUser() && !$user->isAdmin(), function ($q) use ($user) {
                                $q->where(function ($q2) use ($user) {
                                    $q2->whereNull('min_grade_id')
                                    ->orWhereHas('minGrade', function ($q3) use ($user) {
                                        $q3->where('level', '>=', $user->grade?->level ?? 999);
                                    });
                                });
                            })
                            ->count();

        $newsCount = News::active()->count();

        $isBirthday   = $user->isBirthdayToday();
        $birthdayGift = null;

        if ($isBirthday) {
            $birthdayGift = BirthdayGift::where('user_id', $user->id)
                                ->where('year', now()->year)
                                ->whereNotNull('gift_code')
                                ->first();

            $alreadyNotifiedSelf = Notification::where('user_id', $user->id)
                                    ->where('type', 'birthday')
                                    ->where('title', '🎂 Selamat Ulang Tahun!')
                                    ->whereDate('created_at', today())
                                    ->exists();

            if (!$alreadyNotifiedSelf) {
                Notification::create([
                    'user_id' => $user->id,
                    'type'    => 'birthday',
                    'title'   => '🎂 Selamat Ulang Tahun!',
                    'message' => "Selamat ulang tahun, {$user->name}! Kamu punya hadiah spesial hari ini.",
                    'is_read' => false,
                ]);

                // Push hanya sekali per hari, dijaga oleh $alreadyNotifiedSelf di atas
                $user->notify(new PushNotification(
                    'Selamat Ulang Tahun!',
                    "Selamat ulang tahun, {$user->name}! Kamu punya hadiah spesial hari ini.",
                    route('dashboard')
                ));
            }

            $otherUsers = User::where('is_active', true)
                             ->where('id', '!=', $user->id)
                             ->get();

            foreach ($otherUsers as $otherUser) {
                $alreadyGotNotif = Notification::where('user_id', $otherUser->id)
                                    ->where('type', 'birthday')
                                    ->where('title', '🎂 Ulang Tahun Karyawan')
                                    ->where('message', 'like', "%{$user->name}%")
                                    ->whereDate('created_at', today())
                                    ->exists();

                if (!$alreadyGotNotif) {
                    Notification::create([
                        'user_id' => $otherUser->id,
                        'type'    => 'birthday',
                        'title'   => '🎂 Ulang Tahun Karyawan',
                        'message' => "Hari ini {$user->name} dari {$user->department?->name} berulang tahun! Ucapkan selamat ya! 🎉",
                        'link'    => route('dashboard') . '?birthday_wish=' . $user->id,
                        'is_read' => false,
                    ]);
                }
            }
            // CATATAN: push untuk $otherUsers sengaja TIDAK dipasang di sini.
            // Notifikasi ulang tahun karyawan sudah dikirim beserta push-nya di
            // Auth\LoginController@generateBirthdayPosts(). Memasang push di sini
            // akan membuat user menerima notifikasi ganda.
        }

        $this->sendDocumentReminders($user);

        return view('dashboard.index', compact(
            'announcements',
            'notifications',
            'recentDocuments',
            'totalDocuments',
            'newsCount',
            'isBirthday',
            'birthdayGift',
            'greeting',
        ));
    }

    public function claimGift($id)
    {
        $gift = BirthdayGift::where('user_id', Auth::id())
                    ->where('id', $id)
                    ->where('is_claimed', false)
                    ->firstOrFail();

        $gift->update([
            'is_claimed' => true,
            'claimed_at' => now(),
        ]);

        return back()->with('success', 'Hadiah berhasil diklaim! Tunjukkan kode ini ke HRD.');
    }

    private function sendDocumentReminders(User $user): void
    {
        $unreadDocuments = Document::where('is_active', true)
            ->where('type', 'pdf')
            ->when(!$user->isAdmin() && !$user->isSuperUser(), function ($q) use ($user) {
                    $q->where(function ($q2) use ($user) {
                        $q2->whereNull('min_grade_id')
                        ->orWhereHas('minGrade', function ($q3) use ($user) {
                            $q3->where('level', '>=', $user->grade?->level ?? 999);
                        });
                    });
                    $q->where(function ($q2) use ($user) {
                        $q2->whereNull('department_id')
                        ->orWhere('department_id', $user->department_id);
                    });
                })
            ->whereDoesntHave('reads', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->latest()
            ->take(3)
            ->get();

        foreach ($unreadDocuments as $document) {
            $alreadyReminded = Notification::where('user_id', $user->id)
                ->where('type', 'reminder')
                ->where('message', 'like', "%{$document->title}%")
                ->where('created_at', '>=', now()->subDays(7))
                ->exists();

            if (!$alreadyReminded) {
                Notification::create([
                    'user_id' => $user->id,
                    'type'    => 'reminder',
                    'title'   => '📋 Dokumen Belum Dibaca',
                    'message' => "Kamu belum membaca dokumen \"{$document->title}\". Yuk segera baca!",
                    'link'    => route('documents.show', $document->id),
                    'is_read' => false,
                ]);

                // Push hanya sekali per 7 hari per dokumen, dijaga $alreadyReminded
                $user->notify(new PushNotification(
                    'Dokumen Belum Dibaca',
                    "Kamu belum membaca dokumen: {$document->title}",
                    route('documents.show', $document->id)
                ));
            }
        }
    }
}