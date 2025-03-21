<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['serviceName', 'description', 'base_price'];

    public function medicalExams() {
        return $this->belongsToMany(MedicalExam::class, 'medical_exam_service')
                    ->withPivot('quantity', 'content', 'price')
                    ->withTimestamps();
    }
}
