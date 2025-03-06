<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileUpload extends Model
{
    //
    use HasFactory;

    protected $table = 'file_uploads';

    protected $fillable = [
        'file_manager_id',
        'file_name',
        'file_path',
        'file_size',
        'file_type',
        'uploaded_at',
    ];

    public function fileManager()
    {
        return $this->belongsTo(FileManager::class);
    }
}
