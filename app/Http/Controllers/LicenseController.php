<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\License;
use App\Models\User;
use App\Notifications\Notifications;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LicenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $license = License::where('status', 'PENDING')->paginate(20);
        return view('admin.license', compact('license'));
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
        DB::beginTransaction();
        try {
            $request->validate([
                'employee_id' => 'required|exists:employees,id',
                'end_date' => 'required|date',
                'license_type' => 'required|in:BASIC AVSEC,JUNIOR AVSEC,SENIOR AVSEC',
                'license_number' => 'required',
                'license_status' => 'required|in:ACTIVE,INACTIVE',
                'notes' => 'nullable',
            ]);

            $validatedData['end_date'] = Carbon::createFromFormat('m/d/Y', $request->end_date)->format('Y-m-d');

            License::create([
                'employee_id' => $request->employee_id,
                'end_date' => $validatedData['end_date'],
                'license_type' => $request->license_type,
                'license_number' => $request->license_number,
                'license_status' => $request->license_status,
                'notes' => $request->notes
            ]);
            DB::commit();
            notyf()->success('Berhasil menambahkan data lisensi');
            return redirect()->route('licenses.index');
        } catch (\Exception $e) {
            DB::rollBack();
            notyf()->error($e->getMessage());
            return redirect()->route('licenses.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(License $license)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(License $license)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, License $license)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'end_date' => 'required|date',
                'license_type' => 'required|in:BASIC AVSEC,JUNIOR AVSEC,SENIOR AVSEC',
                'license_number' => 'required',
                'license_status' => 'required|in:ACTIVE,INACTIVE',
                'notes' => 'nullable',
            ]);

            $validatedData['end_date'] = Carbon::createFromFormat('m/d/Y', $request->end_date)->format('Y-m-d');

            $license->update([
                'end_date' => $validatedData['end_date'],
                'license_type' => $request->license_type,
                'license_number' => $request->license_number,
                'license_status' => $request->license_status,
                'notes' => $request->notes
            ]);

            DB::commit();
            notyf()->success('Berhasil memperbarui data lisensi');
            return redirect()->route('licenses.index');
        } catch (\Exception $e) {
            DB::rollBack();
            notyf()->error($e->getMessage());
            return redirect()->route('licenses.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(License $license)
    {
        DB::beginTransaction();
        try {
            $license->delete();
            DB::commit();
            notyf()->success('Berhasil menghapus data lisensi');
            return redirect()->route('licenses.index');
        } catch (\Exception $e) {
            DB::rollBack();
            notyf()->error($e->getMessage());
            return redirect()->route('licenses.index');
        }
    }

    public function actionSubmission(Request $request, License $license)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'employee_id' => 'required|exists:employees,id',
                'status' => 'required|in:ACCEPTED,REJECTED'
            ]);

            $license->update([
                'status' => $request->status
            ]);

            if ($request->status === 'REJECTED') {
                $license->delete();

                if ($license->license_url) {
                    Storage::disk('public')->delete($license->license_url); // Hapus file dari storage
                }
            }

            $message = ($request->status === 'ACCEPTED')
                ? 'Verifikasi lisensi anda diterima'
                : 'Maaf, verifikasi lisensi anda ditolak';

            $user = User::find($license->employee->user_id);
            if ($user) {
                $user->notify(new Notifications(
                    $message,
                    route('pegawais.index')
                ));
            }

            DB::commit();
            notyf()->success('Berhasil Melakukan Aksi');
            return redirect()->route('licenses.index');
        } catch (\Exception $e) {
            DB::rollback();
            notyf()->error('Terjad kesalahan,' . $e->getMessage());
            return redirect()->route('licenses.index');
        }
    }
}
