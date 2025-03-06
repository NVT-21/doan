<?php

namespace App\Repositories;
use App\Models\Patient;
 class PatientRepository extends BaseRepository
 {
    function getModel(){
        return Patient::class ;
    }
 }