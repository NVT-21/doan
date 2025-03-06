<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = ['idPatient', 'bookingDate', 'AppointmentTime', 'status'];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'idPatient');
    }
}
