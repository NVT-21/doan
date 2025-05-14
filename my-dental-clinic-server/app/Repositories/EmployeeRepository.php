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
    public function getEmployees($input)
    {
        $query = Employee::query();
    
        if (!empty($input['keyword'])) {
            $query->where('fullName', 'LIKE', '%' . $input['keyword'] . '%');
        }
    
        if (!empty($input['status']) && $input['status'] !== 'all') {
            $query->where('status', $input['status']);
        }
    
        $perPage = $input['per_page'] ?? 10;
        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }
    
  
 }