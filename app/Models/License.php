<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    protected $table = 'license';

    protected $fillable = [
        'employee_id',
        'end_date',
        'license_type',
        'license_number',
        'notes',
        'license_status',
        'license_url',
        'status'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function rekurenSubmission()
    {
        return $this->hasOne(RekurenSubmission::class);
    }

    public function rekurenSchedule()
    {
        return $this->hasMany(RekurenSchedule::class);
    }

    public function diklatSchedule()
    {
        return $this->hasMany(DiklatSchedule::class);
    }

    public function rekurenHistory()
    {
        return $this->hasMany(RekurenHistory::class);
    }

    public function diklatHistory()
    {
        return $this->hasMany(DiklatHistory::class);
    }
}
