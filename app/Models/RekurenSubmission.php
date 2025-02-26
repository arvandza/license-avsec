<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekurenSubmission extends Model
{
    protected $table = 'rekuren_submission';

    protected $fillable = [
        'license_id',
        'status',
        'requested'
    ];

    public function license()
    {
        return $this->belongsTo(License::class);
    }
}
