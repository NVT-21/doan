<?php

namespace App\Repositories;

use App\Models\Appointment;
use App\Models\Patient;
 class PatientRepository extends BaseRepository
 {
    function getModel(){
        return Patient::class ;
    }
    public function getByPhoneNumber($phoneNumber)
    {
        return Patient::with('Appointment')->where("phoneNumber",$phoneNumber)->first();
    }
    public function searchPatientByPhone($phoneNumber)
    {
        $patient = Patient::where('phoneNumber', $phoneNumber)
            ->with(['latestAppointment' => function ($query) {
                $query->whereDate('bookingDate', '>=', now()->toDateString()) 
                      ->where('status', 'Confirmed') // Lọc thêm status = 'confirm'
                      ->latest('bookingDate'); // Lấy cuộc hẹn mới nhất
            }])
            ->first();
    
        return $patient;
    }
    public function findByPhoneAndName($name,$phoneNumber)
    {
        $patient = Patient::where('phoneNumber', $phoneNumber)
        ->where('fullname', $name)
        ->first();
        return $patient;
    }

 }