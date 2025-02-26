<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiklatHistory extends Model
{
    protected $table = 'diklat_history';

    protected $fillable = [
        'employee_id',
        'license_id',
        'old_license',
        'date',
        'notes',
        'result',
        'status',
        'certificate_url'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function license()
    {
        return $this->belongsTo(License::class);
    }
}
