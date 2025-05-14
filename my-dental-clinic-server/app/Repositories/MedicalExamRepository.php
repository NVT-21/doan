<?php

namespace App\Repositories;
use App\Models\MedicalExam;
use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
 class MedicalExamRepository extends BaseRepository
 {
    function getModel(){
        return MedicalExam::class ;
    }
    public function createMedicalExam($data)
{
    DB::beginTransaction();
    try {
        $phoneNumber = $data['phoneNumber'];
        $name = $data['fullname'];

        // Tìm bệnh nhân theo số điện thoại và tên
        $patient = Patient::where('phoneNumber', $phoneNumber)
                          ->where('fullname', $name)
                          ->first();

        if (!$patient) {
            // Nếu không tìm thấy, tạo bệnh nhân mới
            $patient = Patient::create([
                'fullname' => $name,
                'phoneNumber' => $phoneNumber,
                'email' => $data['email'] ?? null,
                'birthdate' => $data['birthdate'] ?? null,
            ]);
        }

        // Kiểm tra xem cuộc hẹn đã tồn tại chưa
        $appointment = null;
        if (!empty($data['idAppointment'])) {
            $appointment = Appointment::where('id', $data['idAppointment'])->first();
        }

        if (!$appointment) {
            // Nếu chưa có, tạo cuộc hẹn mới
            $appointment = Appointment::create([
                'idPatient' => $patient->id,
                'bookingDate' => null,
                'appointmentTime' => null,
                'status' => null,
            ]);
        }
        $medicalExam = MedicalExam::create([
            'idEmployee' => $data['doctorId'], 
            'idAppointment' => $appointment->id,
            'symptoms' => $data['symptoms'] ?? null,
            'status' => "pending",
        ]);
        DB::commit();

        return [
            "success" => true,
            "message" => "Medical exam created successfully",
            "data" => $medicalExam ,
        ];
    } catch (\Throwable $e) {
        DB::rollBack();
        Log::error($e->getMessage());

        return [
            "success" => false,
            "message" => "Failed to create medical exam",
        ];
    }
}
public function getMedicalExam($perPage=5,$status="Pending",$statusPayment,$idEmployee)
{
    $query = MedicalExam::with(['appointment.patient','employee']) // Lấy thông tin appointment + patient
    ->leftJoin('appointments', 'medical_exams.idAppointment', '=', 'appointments.id')
    ->orderByRaw("CASE 
                    WHEN appointments.status = 'Confirmed' THEN 1 
                    WHEN appointments.status IS NULL THEN 2 
                    ELSE 3 
                  END") 
    ->orderBy('appointments.created_at', 'asc')
    ->select('medical_exams.*'); 
if ($status && $status!=="all") {
 
    $query->where('medical_exams.status', $status);
}
if ($statusPayment && $statusPayment!=='all')
{
    $query->where('medical_exams.statusPayment', $statusPayment);
}
if ($idEmployee) {
    $query->where('medical_exams.idEmployee', $idEmployee);
}
return $query->paginate($perPage);
}
public function saveDoctorConclusion(array $data)
{
    // Validate presence of required fields
    if ( !isset($data['medical_exam_id'])) {
        throw new \InvalidArgumentException('Missing required data: medical_exam_id.');
    }

    // Find the medical exam by ID
    $exam = MedicalExam::findOrFail($data['medical_exam_id']);

    // Update the exam with diagnosis and advice
    $exam->diagnosis = $data['diagnosis'];
    $exam->advice = $data['advice'];
    $exam->save();

    return $exam;
}
public function getPrescriptionAndService($idMedicalExam)
{
    try {
        $medicalExam = MedicalExam::findOrFail($idMedicalExam);

        $services = $medicalExam->services;

        $prescription = $medicalExam->prescription;

        $medicines = $prescription ? $prescription->medicines : collect();

        return response()->json([
            'success' => true,
            'services' => $services,
            'medicines' => $medicines
        ], 200);

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Medical exam not found.'
        ], 404);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'An error occurred: ' . $e->getMessage()
        ], 500);
    }
}





   
   
 }