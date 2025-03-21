<?php

namespace App\Http\Controllers;
use App\Services\WorkScheduleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class WorkScheduleController extends ApiResponseController
{
    protected $WorkScheduleService ;
    public function __construct(WorkScheduleService $WorkScheduleService )
    {
        $this->WorkScheduleService = $WorkScheduleService;
    }
    public function createOrUpdate(Request $request)
    {   $employee=$this->getEmployee();
        if(!$employee)
        {
            return $this->error("Not Found Employee");
        }
        $idEmployee=$employee->id;
        $data=$request->all();
        $result=$this->WorkScheduleService->createOrUpdateWorkSchedule($idEmployee,$data);
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
            'pageSize' => $request->input('pageSize', 10),
            'month' => $request->input('month'),
            'keyword' => $request->input('keyword'),
            'year' => $request->input('year')
            
         ];
         return $this->WorkScheduleService->workSchedules($input);
    }
    public function getDoctorWorking()
    {
        $date = Carbon::now()->format('Y-m-d'); 
        $time = Carbon::now()->hour;
        return $this->WorkScheduleService->getDoctorWorking($date,$time);
    }
}
