<?php

namespace App\Repositories;
use App\Models\Employee;
 class EmployeeRepository extends BaseRepository
 {
    function getModel(){
        return Employee::class ;
    }
    public function getDoctorsWithoutRoom()
    {
        return Employee::whereNull('idRoom')
            ->where('role', 'Doctor') 
            ->get();
    }
    public function getRoomOfDoctor($idDoctor){
        $employee = Employee::with("room")->where('id', $idDoctor)->first();

    if (!$employee) {
        return response()->json(["message" => "Doctor not found"], 404);
    }

    return response()->json($employee->room, 200);
    }
   
    
 }