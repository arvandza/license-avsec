<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekurenHistory extends Model
{
    protected $table = 'rekuren_history';

    protected $fillable = [
        'employee_id',
        'license_id',
        'license_type',
        'date',
        'old_period_of_validity',
        'period_of_validity',
        'notes',
        'test_result',
        'status'
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
