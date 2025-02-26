<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekurenSchedule extends Model
{
    protected $table = 'rekuren_schedule';

    protected $fillable = [
        'license_id',
        'date',
        'status'
    ];

    public function license()
    {
        return $this->belongsTo(License::class);
    }
}
