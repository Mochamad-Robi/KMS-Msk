<?php

namespace App\Console\Commands;

use App\Models\News;
use App\Models\User;
use App\Notifications\PushNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification as NotificationFacade;

class SendBirthdayPush extends Command
{
    protected $signature   = 'birthday:push';
    protected $description = 'Kirim push notification ulang tahun ke perangkat karyawan';

    public function handle(): int
    {
        $birthdayNews = News::where('category', 'birthday')
            ->whereDate('created_at', today())
            ->with('birthdayUser')
            ->get();

        if ($birthdayNews->isEmpty()) {
            $this->info('Tidak ada berita ulang tahun hari ini.');
            return self::SUCCESS;
        }

        foreach ($birthdayNews as $news) {

            if (!$news->birthday_user_id) {
                continue;
            }

            $users = User::where('is_active', true)
                ->where('id', '!=', $news->birthday_user_id)
                ->get();

            if ($users->isEmpty()) {
                continue;
            }

            $name = $news->birthdayUser?->name ?? 'Rekan kerja';

            NotificationFacade::send($users, new PushNotification(
                'Ulang Tahun Karyawan',
                "{$name} berulang tahun hari ini. Yuk ucapkan selamat!",
                route('news.show', $news->id)
            ));

            $this->info("Push dikirim untuk {$name} ke {$users->count()} user.");
        }

        return self::SUCCESS;
    }
}