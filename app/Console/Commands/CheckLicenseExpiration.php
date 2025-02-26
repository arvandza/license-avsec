<?php

namespace App\Console\Commands;

use App\Mail\MailNotification;
use App\Models\License;
use App\Notifications\Notifications;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CheckLicenseExpiration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-license-expiration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'License check when the license is about to expire';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();

        $licenses = License::whereIn('end_date', [
            $today->copy()->addMonths(2)->toDateString(),
            $today->copy()->addMonths(1)->toDateString(),
            $today->copy()->addWeeks(2)->toDateString(),
        ])->get();

        foreach ($licenses as $license) {
            $employee = $license->employee;

            if (!$employee) {
                continue;
            }

            $user = $employee->user;

            if (!$user) {
                continue;
            }

            $diff = Carbon::parse($license->end_date)->diffInDays($today);

            if ($diff == 60) {
                $warningLevel = "2 Bulan lagi";
            } elseif ($diff == 30) {
                $warningLevel = "1 Bulan lagi";
            } else {
                $warningLevel = "2 Minggu lagi";
            }

            $user->notify(new Notifications(
                "⚠️ Lisensi anda akan berakhir dalam {$warningLevel}",
                route('pegawais.index')
            ));

            Mail::to($user->email)->send(new MailNotification(
                'Peringatan Lisensi Akan Kadaluarsa',
                "Lisensi {$license->license_type} Anda akan kadaluarsa dalam {$warningLevel}. Segera perbarui sebelum masa berlaku habis.",
                route('pegawais.index')
            ));

            $this->info('Notification sent to ' . $user->name . ' (' . $user->email . ')');
        }

        License::where('end_date', '<', $today)
            ->where('license_status', 'ACTIVE') // Pastikan hanya lisensi yang masih aktif yang diupdate
            ->update(['license_status' => 'INACTIVE']);

        $this->info("Semua lisensi yang sudah kadaluarsa telah diperbarui menjadi 'non active'.");
    }
}
