<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\License;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employee = Employee::paginate(20);
        return view('admin.employee', compact('employee'));
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
                'fullname' => 'required|regex:/^[a-zA-Z\s]+$/|max:150',
                'nip' => 'required|numeric|digits_between:1,50|unique:employees,nip',
                'place_of_birth' => 'required|regex:/^[a-zA-Z\s]+$/|max:150',
                'date_of_birth' => 'required|date',
                'rank' => 'required|regex:/^[a-zA-Z\s]+$/|max:150',
                'position' => 'required|in:Kanit Avsec,Danru 1,Danru 2,Danru 3,Admin,Anggota',
                'education' => 'required|in:SD,SMP,SMA,D1,D2,D3,D4,S1,S2,S3',
                'competence' => 'nullable|regex:/^[a-zA-Z\s]+$/|max:150',
                'email' => 'required|email|max:150|unique:employees,email',
                'contact' => 'required|numeric|digits_between:1,15',
                'role' => 'required|in:1,2',
            ]);

            $user = User::create([
                'email' => $request->email,
                'password' => bcrypt('password'),
                'role_id' => $request->role
            ]);

            $validatedData['date_of_birth'] = Carbon::createFromFormat('m/d/Y', $request->date_of_birth)->format('Y-m-d');

            Employee::create([
                'fullname' => $request->fullname,
                'nip' => $request->nip,
                'place_of_birth' => $request->place_of_birth,
                'date_of_birth' => $validatedData['date_of_birth'],
                'rank' => $request->rank,
                'position' => $request->position,
                'education' => $request->education,
                'competence' => $request->competence,
                'email' => $request->email,
                'contact' => $request->contact,
                'user_id' => $user->id,
            ]);
            DB::commit();
            notyf()->success('Berhasil menambahkan kedalam data pegawai');
            return redirect()->route('employees.index');
        } catch (\Exception $e) {
            DB::rollBack();
            notyf()->error($e->getMessage());
            return redirect()->route('employees.index');
            // return response()->json(['error' => $e->getMessage()], 500);
        }
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
    public function update(Request $request, Employee $employee)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'fullname' => 'required|regex:/^[a-zA-Z\s]+$/|max:150',
                'nip' => 'required|numeric|digits_between:1,50|unique:employees,nip,' . $employee->id,
                'place_of_birth' => 'required|regex:/^[a-zA-Z\s]+$/|max:150',
                'date_of_birth' => 'required|date',
                'rank' => 'required|regex:/^[a-zA-Z\s]+$/|max:150',
                'position' => 'required|in:Kanit Avsec,Danru 1,Danru 2,Danru 3,Admin,Anggota',
                'education' => 'required|in:SD,SMP,SMA,D1,D2,D3,D4,S1,S2,S3',
                'competence' => 'nullable|regex:/^[a-zA-Z\s]+$/|max:150',
                'email' => 'required|email|max:150|unique:employees,email,' . $employee->id,
                'contact' => 'required|numeric|digits_between:1,15'
            ]);

            $validatedData['date_of_birth'] = Carbon::createFromFormat('m/d/Y', $request->date_of_birth)->format('Y-m-d');

            $employee->update([
                'fullname' => $request->fullname,
                'nip' => $request->nip,
                'place_of_birth' => $request->place_of_birth,
                'date_of_birth' => $validatedData['date_of_birth'],
                'rank' => $request->rank,
                'position' => $request->position,
                'education' => $request->education,
                'competence' => $request->competence,
                'email' => $request->email,
                'contact' => $request->contact,
            ]);

            $employee->user()->update([
                'email' => $request->email
            ]);

            DB::commit();
            notyf()->success('Berhasil memperbarui data pegawai');
            return redirect()->route('employees.index');
        } catch (\Exception $e) {
            DB::rollBack();
            notyf()->error($e->getMessage());
            return redirect()->route('employees.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        DB::beginTransaction();
        try {
            $employee->user()->delete();
            $employee->delete();
            DB::commit();
            notyf()->success('Berhasil menghapus data pegawai');
            return redirect()->route('employees.index');
        } catch (\Exception $e) {
            DB::rollBack();
            notyf()->error($e->getMessage());
            return redirect()->route('employees.index');
        }
    }
}
