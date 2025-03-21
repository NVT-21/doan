<?php

namespace App\Models;

use GuzzleHttp\Psr7\Request;
use Illuminate\Database\Eloquent\Model;

class MedicalExam extends Model
{
    protected $fillable = ['idEmployee', 'idAppointment', 'symptoms', 'status','statusPayment' 
    ,'statusPayment', 'ExamDate'];

  
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'idEmployee');
    }

  
    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'idAppointment');
    }
    public function services() {
        return $this->belongsToMany(Service::class, 'medical_exam_service')
                    ->withPivot('quantity', 'content', 'price')
                    ->withTimestamps();
    }
    
}
