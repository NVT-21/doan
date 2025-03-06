<?php

namespace App\Http\Controllers;
use App\Services\WorkScheduleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkScheduleController extends ApiResponseController
{
    protected $WorkScheduleService ;
    public function __construct(WorkScheduleService $WorkScheduleService )
    {
        $this->WorkScheduleService = $WorkScheduleService;
    }
    public function store(Request $request)
    {   $employee=$this->getEmployee();
        if(!$employee)
        {
            return $this->error("Not Found Employee");
        }
        $idEmployee=$employee->id;
        $data=$request->all();
        $result=$this->WorkScheduleService->createWorkSchedule($idEmployee,$data);
        if ($result['success']) {
            return $this->success($result['message']);
         } else {
         
             return $this->error($result['message']);
         }

    }
    public function paging(Request $request)
    {
         $input =[
            'page' => $request->input('page', 1),
            'limit' => $request->input('limit', 10),
            '$conditions' => $request->input('conditions')
         ];
         return $this->WorkScheduleService->workSchedules();
    }
}
