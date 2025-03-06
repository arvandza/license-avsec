<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileManager extends Model
{
    use HasFactory;

    protected $table = 'file_manager';

    protected $fillable = ['employee_id', 'folder_name'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function files()
    {
        return $this->hasMany(FileUpload::class, 'file_manager_id');
    }
}
