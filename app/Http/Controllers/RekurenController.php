<?php

namespace App\Http\Controllers;

use App\Mail\MailNotification;
use App\Models\Employee;
use App\Models\License;
use App\Models\RekurenHistory;
use App\Models\RekurenSchedule;
use App\Models\RekurenSubmission;
use App\Models\User;
use App\Notifications\ApprovedNotification;
use App\Notifications\DiklatRekurenRequestNotification;
use App\Notifications\Notifications;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class RekurenController extends Controller
{
    protected $auth;
    public function __construct()
    {
        $this->auth = Auth::user();
    }


    public function index()
    {
        $license = RekurenSubmission::with('license')->where('status', 'PENDING')->paginate(20);
        return view('admin.rekuren', compact('license'));
    }

    public function indexSchedule()
    {
        $license = RekurenSchedule::with('license')->where('status', 'ON GOING')->paginate(20);
        return view('admin.rekurenschedule', compact('license'));
    }

    public function makeSubmission()
    {
        DB::beginTransaction();
        try {
            $employee = Employee::where('user_id', $this->auth->employee->user_id)->first();
            $license = License::with('rekurenSubmission')->where('employee_id', $employee->id)->first();

            if ($license->status === 'PENDING') {
                return redirect()->route('pegawais.index');
            }
            $admins = User::where('role_id', 1)->get(); // Pastikan ada kolom role di tabel users
            if (!$license->rekurenSubmission || $license->rekurenSubmission->status === 'ACCEPTED') {
                $license = RekurenSubmission::create([
                    'license_id' => $license->id,
                    'status' => 'PENDING',
                    'requested' => 1
                ]);
                notyf()->success('Berhasil mengajukan permintaan rekuren, mohon menunggu konfirmasi');
            } else {
                if ($license->rekurenSubmission->requested === 3) {
                    notyf()->error('Anda sudah mengajukan rekuren sebanyak 3 kali');
                    return redirect()->route('pegawais.index');
                } else if ($license->rekurenSubmission->requested < 3) {
                    $license->rekurenSubmission->update([
                        'status' => 'PENDING',
                        'requested' => $license->rekurenSubmission->requested + 1
                    ]);
                    notyf()->success('Berhasil mengajukan permintaan rekuren, mohon menunggu konfirmasi');
                }
            }

            foreach ($admins as $admin) {
                $admin->notify(new Notifications($employee->fullname . ' mengajukan rekuren', route('rekuren.index')));
            }

            DB::commit();
            return redirect()->route('pegawais.index');
        } catch (\Exception $e) {
            DB::rollBack();
            notyf()->error('Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function acceptSubmission(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'date' => 'required|date',
                'ids' => 'required|string',
            ]);

            $licenseIds = explode(',', $request->ids);

            // Update status dalam satu query
            RekurenSubmission::whereIn('id', $licenseIds)
                ->update(['status' => 'ACCEPTED']);

            // Ambil semua submission yang terkait
            $submissions = RekurenSubmission::whereIn('id', $licenseIds)->get();

            // Buat array untuk mass insert
            $scheduleData = $submissions->map(function ($submission) use ($request) {
                $user = User::findOrFail($submission->license->employee->user_id);
                $user->notify(new Notifications('Anda terpilih mengikuti tes rekuren', route('pegawais.index')));
                Mail::to($user->email)->send(new MailNotification(
                    'Selamat Anda Terpilih Mengikuti Diklat',
                    'Anda terpilih mengikuti diklat pada tanggal ' . $request->date,
                    route('pegawais.index')
                ));
                return [
                    'license_id' => $submission->license_id,
                    'date' => $request->date,
                    'status' => 'ON GOING',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();

            // Mass insert ke RekurenSchedule
            RekurenSchedule::insert($scheduleData);

            DB::commit();
            notyf()->success('Berhasil menyetujui permintaan rekuren');
            return redirect()->route('rekuren.index');
        } catch (\Exception $e) {
            DB::rollback();
            notyf()->error('Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->route('rekuren.index');
        }
    }

    public function rejectSubmission(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'ids' => 'required|string',
            ]);

            $licenseIds = explode(',', $request->ids);

            // Update status dalam satu query
            $submissions = RekurenSubmission::whereIn('id', $licenseIds)
                ->update(['status' => 'REJECTED']);

            foreach ($submissions as $submission) {
                if ($submission->license && $submission->license->employee) {
                    $user = User::find($submission->license->employee->user_id);
                    if ($user) {
                        $user->notify(new Notifications(
                            'Maaf, permintaan rekuren Anda telah ditolak.',
                            route('pegawais.index')
                        ));
                    }
                }
            }
            DB::commit();
            notyf()->success('Berhasil menolak permintaan rekuren');
            return redirect()->route('rekuren.index');
        } catch (\Exception $e) {
            DB::rollback();
            notyf()->error('Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->route('rekuren.index');
        }
    }

    public function graduated(Request $request, RekurenSchedule $schedule)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'test_result' => 'required|in:GRADUATED,UNGRADUATED',
                'notes' => 'nullable|string|max:150',
            ]);

            $schedule->delete();

            // update license date 2 years
            $license = License::findOrFail($schedule->license_id);

            //create history
            RekurenHistory::create([
                'employee_id' => $schedule->license->employee_id,
                'license_id' => $schedule->license_id,
                'license_type' => $license->license_type,
                'date' => Carbon::now(),
                'old_period_of_validity' => $license->end_date,
                'period_of_validity' => $request->test_result === 'GRADUATED' ? Carbon::now()->addYears(2) : $license->end_date,
                'test_result' => $request->test_result,
                'notes' => $request->notes,
                'status' => 'DONE'
            ]);

            $license->update([
                'end_date' => $request->test_result === 'GRADUATED' ? Carbon::now()->addYears(2) : $license->end_date,
                'license_status' => $request->test_result === 'GRADUATED' ? 'ACTIVE' : 'INACTIVE'
            ]);

            $message = ($request->test_result == 'GRADUATED')
                ? 'Selamat, Anda telah lulus tes rekuren!'
                : 'Maaf, Anda belum lulus tes rekuren';

            $user = User::findOrFail($license->employee->user_id);
            $user->notify(new Notifications($message, route('pegawais.index')));

            DB::commit();
            notyf()->success('Berhasil memperbarui data rekuren');
            return redirect()->route('rekuren.indexSchedule');
        } catch (\Exception $e) {
            DB::rollback();
            notyf()->error('Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->route('rekuren.indexSchedule');
        }
    }

    public function indexRekurenHistory()
    {
        $license = RekurenHistory::with('license')->paginate(20);
        return view('admin.rekurenhistory', compact('license'));
    }
}
