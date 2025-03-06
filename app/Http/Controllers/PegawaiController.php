<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\License;
use App\Models\RekurenSchedule;
use App\Models\RekurenSubmission;
use App\Models\User;
use App\Notifications\Notifications;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $employee = Employee::where('user_id', $user->id)->first();
        $employe = License::with('employee')
            ->where('employee_id', $employee->id)
            ->first();
        $employees = License::with('employee', 'diklatHistory', 'rekurenHistory')->where('employee_id', $user->employee->id)->first();

        if ($employe) {
            $expiredDate = Carbon::parse($employees->end_date);
            $today = Carbon::now();
            $monthsLeft = round($today->diffInMonths($expiredDate) + ($today->diffInDays($expiredDate) / 30), 1);
            $daysLeft = $today->diffInDays($expiredDate, false);
            $status = null;
            $message = null;

            if ($monthsLeft <= 6 && $monthsLeft > 0) {
                if ($daysLeft <= 30) {
                    $status = 'warning';
                    $message = "⚠️ Lisensi akan expired dalam " . intval($daysLeft) . " hari pada {$expiredDate->locale('id')->isoFormat('D MMM Y')}.";
                } else {
                    $status = 'warning';
                    $message = "⚠️ Lisensi akan expired dalam {$monthsLeft} bulan pada {$expiredDate->locale('id')->isoFormat('D MMM Y')}.";
                }
            } elseif ($monthsLeft == 0) {
                $status = 'danger';
                $message = "❌ Lisensi akan expired bulan ini pada {$expiredDate->locale('id')->isoFormat('D MMM Y')}.";
            } elseif ($monthsLeft < 0) {
                $status = 'danger';
                $message = "⛔ Lisensi sudah expired sejak {$expiredDate->locale('id')->isoFormat('D MMM Y')}.";
            }
        } else {
            $status = null;
            $message = null;
        }

        return view('employee.profile', compact('employee', 'status', 'message', 'employe', 'employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $pegawai)
    {
        DB::beginTransaction();
        try {
            // Validasi data
            $validatedData = $request->validate([
                'fullname' => 'required|regex:/^[a-zA-Z\s]+$/|max:150',
                'place_of_birth' => 'required|regex:/^[a-zA-Z\s]+$/|max:150',
                'date_of_birth' => 'required|date',
                'email' => 'required|email|max:150|unique:employees,email,' . $pegawai->id,
                'contact' => 'required|numeric|digits_between:1,15',
                'photo_url' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            // Format tanggal lahir
            $validatedData['date_of_birth'] = Carbon::createFromFormat('m/d/Y', $request->date_of_birth)->format('Y-m-d');

            // Jika ada file yang diunggah
            if ($request->hasFile('photo_url')) {
                // Hapus gambar lama jika ada
                if ($pegawai->photo_url) {
                    Storage::disk('public')->delete($pegawai->photo_url);
                }

                // Simpan gambar baru
                $path = $request->file('photo_url')->store('profile_pictures', 'public');
                $validatedData['photo_url'] = $path; // Simpan path saja
            } else {
                // Jika tidak ada file yang diunggah, gunakan gambar lama
                $validatedData['photo_url'] = $pegawai->photo_url;
            }

            $user = User::where('id', $pegawai->user_id)->first();

            $user->update([
                'email' => $request->email,
            ]);

            // Update data pegawai
            $pegawai->update($validatedData);

            DB::commit();
            notyf()->success('Berhasil mengubah biodata');
            return redirect()->route('pegawais.index');
        } catch (\Exception $e) {
            DB::rollBack();
            notyf()->error('Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->route('pegawais.index');
        }
    }

    public function updatePassword(Request $request, Employee $pegawai)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'password' => 'required',
                'newPassword' => 'required|min:6',
                'confirmPassword' => 'required|min:6|same:newPassword',
            ]);

            if (!password_verify($request->password, $pegawai->user->password)) {
                notyf()->error('Password lama salah');
                return redirect()->route('pegawais.index');
            } else {
                $pegawai->user->update([
                    'password' => bcrypt($request->newPassword),
                ]);
                notyf()->success('Berhasil mengubah password');
                DB::commit();
                return redirect()->route('pegawais.index');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            notyf()->error('Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->route('pegawais.index');
        }
    }

    public function licenseSubmission(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'employee_id' => 'required|exists:employees,id',
                'license_type' => 'required|in:BASIC AVSEC,JUNIOR AVSEC,SENIOR AVSEC',
                'license_number' => 'required|unique:license,license_number',
                'end_date' => 'required|date',
                'license_url' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            $fileUrl = null;
            if ($request->hasFile('license_url')) {
                $file = $request->file('license_url');

                $fileUrl = $file->store('license', 'public');
            }

            License::create([
                'employee_id' => $request->employee_id,
                'license_type' => $request->license_type,
                'license_number' => $request->license_number,
                'end_date' => Carbon::createFromFormat('m/d/Y', $request->end_date)->format('Y-m-d'),
                'license_url' => $fileUrl,
                'status' => 'PENDING',
            ]);

            $pegawai = Employee::where('id', $request->employee_id)->first();
            $admins = User::where('role_id', 1)->get();

            foreach ($admins as $admin) {
                $admin->notify(new Notifications($pegawai->fullname . ' mengajukan verifikasi lisensi', route('licenses.index')));
            }

            DB::commit();
            notyf()->success('Berhasil mengajukan lisensi');
            return redirect()->route('pegawais.index');
        } catch (\Exception $e) {
            DB::rollBack();
            notyf()->error('Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->route('pegawais.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        //
    }
}
