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
    try {
        $name = $data['fullname'];
        $phoneNumber = $data['phoneNumber'];
        $currentPatient = null;

        // Kiểm tra bệnh nhân có tồn tại hay không
        $patient = $this->PatientRepository->findByPhoneAndName($name, $phoneNumber);

        if (!$patient) {
            // Nếu không có, tạo bệnh nhân mới
            $currentPatient = $this->PatientRepository->create($data);
        } else {
            $currentPatient = $patient; // Nếu có thì dùng thông tin cũ
        }
        $this->appointmentRepository->create([
            'idPatient' => $currentPatient->id,
        ]);

        DB::commit();

        return [
            "success" => true,
            "message" => "Appointment created successfully"
        ];
    } catch (\Throwable $e) {
        Log::error($e->getMessage()); 

        DB::rollBack();
        return [
            "success" => false,
            "message" => "Failed to create appointment"
        ];
    }
}

    public function searchPatientByPhone($phoneNumber)
    {
        $patient = $this->PatientRepository->searchPatientByPhone($phoneNumber);
    
        // Nếu không tìm thấy bệnh nhân
        if (!$patient) {
            return [
                'success' => false,
                'message' => "Not Found Patient"
            ];
        }
    
        // Lấy cuộc hẹn mới nhất của bệnh nhân (nếu có)
        $latestAppointment = $patient->latestAppointment;
    
        // Chuẩn bị dữ liệu trả về
        $data = [
            "id" => $patient->id,
            "fullname" => $patient->fullname,
            "email" => $patient->email,
            "phoneNumber" => $patient->phoneNumber,
            "birthday"=>$patient->birthdate,
            "gender" => $patient->gender,
            "idAppointment"=>optional($latestAppointment)->id,
            "bookingDate" => optional($latestAppointment)->bookingDate, // 🛠 Tránh lỗi khi không có cuộc hẹn
            "appointmentTime" => optional($latestAppointment)->appointmentTime,
        ];
    
        return [
            'success' => true,
            'message' => "Find Patient Successfully",
            'data' => $data
        ];
    }
    
}