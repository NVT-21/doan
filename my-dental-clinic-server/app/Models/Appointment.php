<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = ['idPatient', 'bookingDate', 'appointmentTime', 'status'];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'idPatient');
    }
    public function medicalExam()
    {
        return $this->hasOne(MedicalExam::class, 'idAppointment'); // Một cuộc hẹn chỉ có một ca khám
    }

}
