<?php

namespace App\Repositories;
use App\Models\Appointment;
 class AppointmentRepository extends BaseRepository
 {
    function getModel(){
        return Appointment::class ;
    }
    public function getAppointments($perPage , $keyword = null, $status = null)
    {
        $query = Appointment::with('patient'); // Lấy thông tin bệnh nhân
        
        if (!empty($keyword)) {
            $query->whereHas('patient', function ($q) use ($keyword) {
                $q->where('fullname', 'LIKE', "%$keyword%");
            });
        }
        if (!empty($status) && $status !== "all") {
            $query->where('status', $status);
        }
        return $query->paginate($perPage);
    }
    

   
   
 }