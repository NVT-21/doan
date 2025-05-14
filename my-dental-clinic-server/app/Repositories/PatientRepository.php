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
        ->with('latestAppointment')
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
    public function getMedicalExamsOfPatient($phoneNumber)
    {
        return Patient::with(['appointments.medicalExam' => function($query) {
            // Lọc theo status của MedicalExam
            $query->where('medical_exams.status', 'Completed');
        }])
        ->where('phoneNumber', $phoneNumber)
        ->first();  
    }
    

    

 }