<?php

namespace App\Console\Commands;

use App\Mail\MailNotification;
use App\Models\License;
use App\Models\User;
use App\Notifications\Notifications;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
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
        $today = Carbon::now();

        $licenses = License::with('employee')->get();

        foreach ($licenses as $license) {
            $endDate = Carbon::parse($license->end_date);
            $diffInDays = $today->diffInDays($endDate, false);

            if ($diffInDays == 60 || $diffInDays == 30 || $diffInDays == 14) {
                $employee = $license->employee;
                $user = $employee->user;

                if ($user) {
                    $user->notify(new Notifications('Lisensi anda akan segera expired', route('pegawais.index')));
                    $admin = User::where('role_id', 1)->first();
                    $admin->notify(new Notifications('Lisensi ' . $license->license_number . ' akan segera expired', route('pegawais.index')));
                    Mail::to($user->email)->send(new MailNotification(
                        'Peringatan Lisensi Akan Kadaluarasa',
                        'Lisensi Anda akan berkahir dalam ' . $diffInDays . ' hari. Segera perbarui sebelum masa berlaku habis.',
                        route('pegawais.index')
                    ));

                    Log::info('License expiration notification sent to ' . $user->email);
                }
            }

            if ($diffInDays < 0 && $license->license_status == 'ACTIVE') {
                $license->update([
                    'license_status' => 'INACTIVE'
                ]);
                Log::info('License ' . $license->license_number . ' expired');
            }
        }


        $this->info('Cek lisensi selesai');
    }
}
