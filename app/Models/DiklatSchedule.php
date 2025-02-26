<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiklatSchedule extends Model
{
    protected $table = 'diklat_schedule';

    protected $fillable = [
        'license_id',
        'title',
        'date',
        'status'
    ];

    public function license()
    {
        return $this->belongsTo(License::class);
    }
}
