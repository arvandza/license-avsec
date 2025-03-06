<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\FileManager;
use App\Models\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileManagerController extends Controller
{

    public function index()
    {
        $employeeId = Auth::user()->employee->id;
        $fileManager = FileManager::where('employee_id', $employeeId)->first();
        if (!$fileManager) {
            $files = null;
        } else {
            $files = FileUpload::where('file_manager_id', $fileManager->id)->get();
        }
        return view('employee.filemanager', compact('files'));
    }

    public function indexManager()
    {
        $folders = FileUpload::with('fileManager.employee')
            ->get()
            ->groupBy('fileManager.employee.id') // Grup berdasarkan employee_id
            ->map(function ($files, $employeeId) {
                $employee = $files->first()->fileManager->employee; // Ambil data pegawai pertama di grup

                return [
                    'folder_name' => Str::slug($employee->fullname, '_'),
                    'created_at'  => $files->max('created_at'), // Ambil file terakhir berdasarkan tanggal
                    'employee_id' => $employeeId,
                    'photo_url'   => $employee->photo_url,
                    'fullname'    => $employee->fullname,
                ];
            });

        return view('admin.filemanager', compact('folders'));
    }

    public function showFiles($folder)
    {
        $files = FileUpload::whereHas('fileManager.employee', function ($query) use ($folder) {
            $query->whereRaw("REPLACE(fullname, ' ', '_') = ?", [$folder]);
        })->get();

        $photo = FileUpload::with('fileManager.employee')->where('file_manager_id', $files->first()->file_manager_id)->first();
        return view('admin.filelist', compact('files', 'folder', 'photo'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'files' => 'required|array', // Pastikan files berupa array
            'files.*' => 'max:2048' // Tambahkan validasi jenis file
        ]);

        // Ambil data pegawai
        $employee = Employee::findOrFail($request->employee_id);
        $folderPath = 'files/' . Str::slug($employee->fullname, '_'); // Hindari karakter khusus

        // Pastikan folder file manager dibuat
        $fileManager = FileManager::firstOrCreate([
            'employee_id' => $request->employee_id
        ], [
            'folder_name' => $folderPath
        ]);

        $uploadedFiles = [];
        $files = is_array($request->file('files')) ? $request->file('files') : [$request->file('files')];

        // Loop untuk setiap file yang diunggah
        foreach ($files as $file) {
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs($folderPath, $fileName, 'public'); // Simpan di storage/app/public/files/nama_pegawai/

            // Simpan ke database
            FileUpload::create([
                'file_manager_id' => $fileManager->id,
                'file_name' => $fileName,
                'file_path' => $path,
                'file_size' => $file->getSize(),
                'file_type' => $file->getClientMimeType()
            ]);

            $uploadedFiles[] = asset('storage/' . $path); // Simpan URL untuk response
        }
    }

    public function download($id)
    {
        $file = FileUpload::findOrFail($id);

        // Path yang benar
        $filePath = 'storage/' . $file->file_path;

        // Cek apakah file ada di storage/public
        if (!Storage::disk('public')->exists(str_replace('storage/', '', $filePath))) {
            return redirect()->back()->with('error', 'File tidak ditemukan.');
        }

        // Download file dengan nama asli
        return Storage::disk('public')->download(str_replace('storage/', '', $filePath), $file->file_name);
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $fileUpload = FileUpload::findOrFail($id);
            Storage::disk('public')->delete(str_replace('storage/', '', $fileUpload->file_path));
            $fileUpload->delete();
            DB::commit();
            notyf()->success('File berhasil dihapus');
            return redirect()->route('file-manager');
        } catch (\Exception $e) {
            DB::rollBack();
            notyf()->error('Terjadi Kesalahan: ' . $e->getMessage());
            return redirect()->route('file-manager');
        }
    }
}
