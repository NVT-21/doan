<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AppointmentService;
class AppointmentController extends ApiResponseController
{
    protected $AppointmentService ;
    public function __construct(AppointmentService $AppointmentService )
    {
        $this->AppointmentService = $AppointmentService;
    }
    public function getAppointments(Request $request)
    {
        $keyword=$request->input('keyword');
        $status=$request->input('status');
        $perPage = $request->input('per_page', 5); 
        $appointments = $this->AppointmentService->getAppointments($perPage, $keyword, $status);

        return response()->json($appointments);
    }
    public function update (Request $request,$id)
    {
        $data=$request->only('bookingDate', 'AppointmentTime', 'status');
        $result=$this->AppointmentService->update($id,$data);
        if ($result['success']) {
            return $this->success($result['message']);
         } else {
         
             return $this->error($result['message']);
         }
    }
}
