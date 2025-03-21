<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PatientService;
class PatientController extends ApiResponseController
{
    protected $PatientService ;
    public function __construct(PatientService $PatientService )
    {
        $this->PatientService = $PatientService;
    }
    public function index()
    {
        return view('Customer.home');
    }


    public function store(Request $request)
    {
        $data = $request->all();
         $result= $this->PatientService->createAppointment($data);
        if($result['success']){
            return redirect()->route('patient.home')->with("success","Successfully created");
        }
        else {
            return back()->with("error","Failed to create");
        }
    }


    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function patient()
    {
        return view ('Admin.list-patient');
    }
    public function findByNumberPhone(Request $request)
    {
        $phoneNumber=$request->input('phoneNumber');
        $result= $this->PatientService->searchPatientByPhone($phoneNumber);
        if($result['success'])
        {
            return $this->success($result['message'],$result['data']);
        }
        else {
            return $this->error($result['message']);
        }
    }
    
}
