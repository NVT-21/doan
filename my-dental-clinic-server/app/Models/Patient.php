<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'fullname',
        'phoneNumber',
        'email',
        'birthdate',
        'message',
    ];
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'idPatient');
    }
}
