<?php

namespace App\Console\Commands;

use App\Models\News;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateBirthdayPosts extends Command
{
    protected $signature = 'birthday:generate
                            {--date= : Tanggal ulang tahun yang diproses (YYYY-MM-DD). Kosongkan untuk hari ini.}';

    protected $description = 'Buat berita ulang tahun + notifikasi in-app untuk karyawan yang berulang tahun';

    public function handle(): int
    {
        Carbon::setLocale('id');

        // Emoji ditulis sebagai escape unicode supaya file ini tetap ASCII murni
        // dan tidak rusak saat di-upload (masalah encoding yang pernah terjadi).
        $cake  = "\u{1F382}";
        $party = "\u{1F389}";
        $conf  = "\u{1F38A}";

        // Tanggal yang diproses
        try {
            $target = $this->option('date')
                ? Carbon::parse($this->option('date'))->startOfDay()
                : now()->startOfDay();
        } catch (\Exception $e) {
            $this->error('Format tanggal tidak valid. Gunakan YYYY-MM-DD, contoh: 2026-10-25');
            return self::FAILURE;
        }

        $isToday = $target->isSameDay(now());
        $this->info('Memproses ulang tahun tanggal: ' . $target->format('Y-m-d')
                    . ($isToday ? ' (hari ini)' : ' (susulan)'));

        $birthdayUsers = User::whereNotNull('birth_date')
            ->whereMonth('birth_date', $target->month)
            ->whereDay('birth_date', $target->day)
            ->where('is_active', true)
            ->get();

        if ($birthdayUsers->isEmpty()) {
            $this->info('Tidak ada yang berulang tahun pada tanggal tersebut.');
            return self::SUCCESS;
        }

        // created_by: pakai user id 1 kalau ada, kalau tidak ambil super-user pertama
        $creatorId = User::where('id', 1)->value('id')
            ?? User::whereHas('role', fn($q) => $q->where('slug', 'super-user'))->value('id');

        if (!$creatorId) {
            $this->error('Tidak ada user yang bisa dipakai sebagai pembuat berita.');
            return self::FAILURE;
        }

        foreach ($birthdayUsers as $birthdayUser) {

            // Penjaga anti-dobel: cek berdasarkan tanggal tayang, bukan tanggal dibuat,
            // supaya tetap benar saat dipakai untuk tanggal susulan.
            $alreadyExists = News::where('category', 'birthday')
                ->where('birthday_user_id', $birthdayUser->id)
                ->whereDate('publish_at', $target)
                ->exists();

            if ($alreadyExists) {
                $this->line("Dilewati (sudah ada): {$birthdayUser->name}");
                continue;
            }

            $age      = $birthdayUser->birth_date->age;
            $deptName = $birthdayUser->department?->name ?? '-';

            $whenText = $isToday
                ? 'Hari ini'
                : 'Tanggal ' . $target->translatedFormat('d F Y');

            $content = "{$cake} {$whenText}, <strong>{$birthdayUser->name}</strong> dari departemen "
                     . "<strong>{$deptName}</strong> merayakan ulang tahun yang ke-<strong>{$age}</strong>!"
                     . "<br><br>Yuk ucapkan selamat ulang tahun dan doakan yang terbaik! {$party}{$conf}";

            $news = News::create([
                'title'            => "{$cake} Selamat Ulang Tahun, {$birthdayUser->name}!",
                'category'         => 'birthday',
                'content'          => $content,
                'image_path'       => $birthdayUser->avatar,
                'created_by'       => $creatorId,
                'birthday_user_id' => $birthdayUser->id,
                'is_active'        => true,
                'publish_at'       => $target->toDateString(),
            ]);

            // Notifikasi in-app ke semua user aktif kecuali yang ulang tahun
            $allUsers = User::where('is_active', true)
                ->where('id', '!=', $birthdayUser->id)
                ->get();

            if ($allUsers->isEmpty()) {
                continue;
            }

            $message = $isToday
                ? "{$birthdayUser->name} berulang tahun hari ini! Yuk ucapkan selamat! {$party}"
                : "{$birthdayUser->name} berulang tahun pada " . $target->translatedFormat('d F') . ". Yuk ucapkan selamat! {$party}";

            $notifs = $allUsers->map(fn($u) => [
                'user_id'    => $u->id,
                'type'       => 'birthday',
                'title'      => "{$cake} Ulang Tahun Karyawan",
                'message'    => $message,
                'link'       => route('news.show', $news->id),
                'is_read'    => false,
                'created_at' => now(),
                'updated_at' => now(),
            ])->toArray();

            Notification::insert($notifs);

            $this->info("Dibuat: {$birthdayUser->name} ({$allUsers->count()} notifikasi)");
        }

        return self::SUCCESS;
    }
}