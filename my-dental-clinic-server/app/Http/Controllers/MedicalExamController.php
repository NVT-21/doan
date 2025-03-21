<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MedicalExamService;
class MedicalExamController extends ApiResponseController
{
    protected $MedicalExamService ;
    public function __construct(MedicalExamService $MedicalExamService )
    {
        $this->MedicalExamService = $MedicalExamService;
    }
    public function store(Request $request)
    {
        $data=$request->all();
        $result=$this->MedicalExamService->createMedicalExam($data);
        if ($result['success']) {
            return $this->success($result['message']);
         } else {
         
             return $this->error($result['message']);
         }
    }
    public function getMedicalExam(Request $request)
        {   $status = $request->input('status', null); 
        $perPage = $request->input('perPage', 10);
        $paymentStatus = $request->input('paymentStatus', null);
        return $this->MedicalExamService->getMedicalExam($perPage,$status,$paymentStatus);
    }
}
