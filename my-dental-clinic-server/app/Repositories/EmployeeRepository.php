<?php

namespace App\Repositories;
use App\Models\Employee;
 class EmployeeRepository extends BaseRepository
 {
    function getModel(){
        return Employee::class ;
    }
  
 }