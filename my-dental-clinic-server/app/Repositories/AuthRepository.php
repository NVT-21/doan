<?php

namespace App\Repositories;
use App\Models\User;
 class AuthRepository extends BaseRepository
 {
    function getModel(){
        return User::class ;
    }
    public function findByEmail(string $email)
    {
        return User::where('email', $email)->first();
    }
   
 }