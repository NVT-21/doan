<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = ['user_id', 'fullName', 'birthday', 'gender', 'phoneNumber'];

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function workSchedules()
    {
        return $this->hasMany(WorkSchedule::class, 'idEmployee');
    }
}
