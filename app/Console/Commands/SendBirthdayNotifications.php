<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Console\Command;

class SendBirthdayNotifications extends Command
{
    protected $signature   = 'notifications:birthday';
    protected $description = 'Kirim notifikasi ulang tahun ke user yang berulang tahun hari ini';

    public function handle(): void
    {
        $today = now();

        $users = User::where('is_active', true)
                     ->whereNotNull('birth_date')
                     ->whereRaw('DAY(birth_date) = ?', [$today->day])
                     ->whereRaw('MONTH(birth_date) = ?', [$today->month])
                     ->get();

        foreach ($users as $user) {
            // Cek sudah kirim notif hari ini belum
            $exists = Notification::where('user_id', $user->id)
                          ->where('type', 'birthday')
                          ->whereDate('created_at', today())
                          ->exists();

            if (!$exists) {
                Notification::create([
                    'user_id' => $user->id,
                    'type'    => 'birthday',
                    'title'   => '🎂 Selamat Ulang Tahun!',
                    'message' => "Selamat ulang tahun, {$user->name}! Semoga selalu sehat dan sukses. — PT MSK",
                    'is_read' => false,
                ]);

                $this->info("Notifikasi dikirim ke: {$user->name}");
            }
        }

        $this->info('Birthday notifications selesai.');
    }
}