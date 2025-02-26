<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = 'employees';

    protected $fillable = [
        'user_id',
        'fullname',
        'nip',
        'place_of_birth',
        'date_of_birth',
        'education',
        'competence',
        'rank',
        'position',
        'email',
        'contact',
        'photo_url'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function license()
    {
        return $this->hasOne(License::class, 'employee_id', 'id');
    }

    public function rekurenHistory()
    {
        return $this->hasMany(RekurenHistory::class);
    }


    public function diklatHistory()
    {
        return $this->hasMany(DiklatHistory::class);
    }

    public function diklatSchedule()
    {
        return $this->hasManyThrough(DiklatSchedule::class, License::class);
    }

    public function rekurenSchedule()
    {
        return $this->hasManyThrough(RekurenSchedule::class, License::class, 'employee_id', 'license_id');
    }

    public function rekurenSubmission()
    {
        return $this->hasManyThrough(RekurenSubmission::class, License::class, 'employee_id', 'license_id');
    }
}
