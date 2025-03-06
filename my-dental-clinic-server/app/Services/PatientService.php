<?php 
namespace App\Services;

use App\Repositories\PatientRepository;
use App\Repositories\AppointmentRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;
use Illuminate\Support\Facades\Hash;

class PatientService extends BaseService
{
    protected $PatientRepository;
    protected $appointmentRepository;
    public function __construct(PatientRepository $patientRepository,AppointmentRepository $appointmentRepository)
    {
        $this->PatientRepository = $patientRepository;
        $this->appointmentRepository = $appointmentRepository;
        parent::__construct();
    }
    public function getRepository()
    {
        return $this->PatientRepository;
    }
    public function createAppointment($data)
    {
        DB::beginTransaction();
        try
        {
        $newPatient=$this->PatientRepository->create($data);
        $this->appointmentRepository->create([
           'idPatient' => $newPatient->id,
        ]);
        DB::commit();
        return [
            "success" => true,
            "message"=>"Appointment created successfully"
        ];
      }
        catch(Throwable $e)
        {
            Log::error($e->getMessage);
            DB::rollBack();
            return [
                "success"=> false,
                "message"=>"Failed to create"
            ];
        }

    }
}