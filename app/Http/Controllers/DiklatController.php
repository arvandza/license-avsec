<?php

namespace App\Http\Controllers;

use App\Mail\MailNotification;
use App\Models\DiklatHistory;
use App\Models\DiklatSchedule;
use App\Models\Employee;
use App\Models\License;
use App\Models\User;
use App\Notifications\Notifications;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class DiklatController extends Controller
{
    public function index()
    {
        $employees = License::with('employee')
            ->whereDoesntHave('employee.diklatSchedule')
            ->get();
        return view('admin.diklat', compact('employees'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'license_id' => 'required|array',
                'license_id.*' => 'required|exists:license,id',
                'date' => 'required|date',
            ]);

            $selectedLicenses = $request->input('license_id');
            $validatedDate = Carbon::createFromFormat('m/d/Y', $request->date)->format('Y-m-d');

            foreach ($selectedLicenses as $licenseId) {
                $license = License::findOrFail($licenseId);
                $employee  =  Employee::findOrFail($license->employee_id);
                $user = User::findOrFail($employee->user_id);
                $title = $this->getTitleBasedOnLicenseType($license->license_type);
                DiklatSchedule::create([
                    'title' => $title,
                    'license_id' => $licenseId,
                    'date' => $validatedDate,
                    'status' => 'ON GOING',
                ]);

                $user->notify(new Notifications('Anda terpilih mengikuti diklat', route('pegawais.index')));
                Mail::to($user->email)->send(new MailNotification(
                    'Selamat Anda Terpilih Mengikuti Diklat',
                    'Anda terpilih mengikuti diklat pada tanggal {$validatedDate}',
                    route('pegawais.index')
                ));
            }

            DB::commit();
            notyf()->success('Berhasil menambahkan data diklat');
            return redirect()->route('diklat.index');
        } catch (\Exception $e) {
            DB::rollBack();
            notyf()->error($e->getMessage());
            return redirect()->route('diklat.index');
        }
    }

    private function getTitleBasedOnLicenseType($licenseType)
    {
        $titleMap = [
            'BASIC AVSEC' => 'JUNIOR DIKLAT',
            'JUNIOR AVSEC' => 'SENIOR DIKLAT',
            'SENIOR AVSEC' => 'SENIOR DIKLAT',
        ];

        return $titleMap[$licenseType] ?? 'BASIC DIKLAT';
    }

    public function indexSchedule()
    {
        $license = DiklatSchedule::with('license')->paginate(20);
        return view('admin.diklatschedule', compact('license'));
    }

    public function graduated(Request $request, DiklatSchedule $schedule)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'result' => 'required|in:GRADUATED,UNGRADUATED',
                'notes' => 'nullable|string|max:150',
                'certificate_url' => 'required|file|mimes:jpeg,png,jpg|max:1048',
            ]);

            $promotionLevels = [
                'BASIC AVSEC' => 'JUNIOR AVSEC',
                'JUNIOR AVSEC' => 'SENIOR AVSEC',
                'SENIOR AVSEC' => 'SENIOR AVSEC',
            ];

            $fileUrl = null;
            if ($request->hasFile('certificate_url')) {
                $file = $request->file('certificate_url');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('public/certificates', $fileName);
                $fileUrl = str_replace('public/', 'storage/', $filePath);
            }

            $schedule->delete();

            //create history
            DiklatHistory::create([
                'employee_id' => $schedule->license->employee_id,
                'license_id' => $schedule->license_id,
                'date' => Carbon::now(),
                'old_license' => $schedule->license->license_type,
                'result' => $request->result,
                'notes' => $request->notes,
                'status' => 'DONE',
                'certificate_url' => $fileUrl
            ]);

            // update license promotion levels
            $license = License::findOrFail($schedule->license_id);
            if ($request->result === 'GRADUATED') {
                $newLicenseType = $promotionLevels[$license->license_type] ?? $license->license_type;
                if ($license->license_url) {
                    Storage::disk('public')->delete($license->license_url); // Hapus file dari storage
                }
                $newFilePath = null;
                if ($request->hasFile('license_url')) {
                    $file = $request->file('license_url');
                    $newFilePath = $file->store('license', 'public');
                }
                $license->update([
                    'license_type' => $newLicenseType,
                    'license_url' => $newFilePath
                ]);
            }

            $message = ($request->result == 'GRADUATED')
                ? 'Selamat, Anda telah lulus diklat!'
                : 'Maaf, Anda belum lulus diklat';

            $user = User::findOrFail($license->employee->user_id);
            $user->notify(new Notifications($message, route('pegawais.index')));

            DB::commit();
            notyf()->success('Berhasil menyelesaikan diklat');
            return redirect()->route('diklat.indexSchedule');
        } catch (\Exception $e) {
            DB::rollBack();
            notyf()->error('Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->route('diklat.indexSchedule');
        }
    }

    public function indexHistory()
    {
        $license = DiklatHistory::with('license')->paginate(20);
        return view('admin.diklathistory', compact('license'));
    }

    public function downloadFile($id)
    {
        $history = DiklatHistory::findOrFail($id);
        if (!$history->certificate_url) {
            return redirect()->back()->with('error', 'File tidak tersedia.');
        }

        // Ubah path agar sesuai dengan penyimpanan
        $filePath = str_replace('storage/', 'public/', $history->certificate_url);

        // Cek apakah file benar-benar ada di storage
        if (!Storage::exists($filePath)) {
            notyf()->error('File tidak ditemukan.');
            return redirect()->back();
        }

        // Unduh file
        return Storage::download($filePath);
    }
}
