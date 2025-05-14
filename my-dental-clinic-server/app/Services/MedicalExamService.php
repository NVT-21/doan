<?php 
namespace App\Services;

use App\Repositories\MedicalExamRepository;
use Illuminate\Support\Facades\Hash;

class MedicalExamService extends BaseService
{
    protected $MedicalExamRepository;

    public function __construct(MedicalExamRepository $MedicalExamRepository)
    {
        $this->MedicalExamRepository = $MedicalExamRepository;
        parent::__construct();
    }
    public function getRepository()
    {
        return $this->MedicalExamRepository;
    }
    public function createMedicalExam($data)
    {
        return $this->MedicalExamRepository->createMedicalExam($data);
    }
    public function getMedicalExam($perPage,$status,$statusPayment,$idEmployee)
    {
        return $this->MedicalExamRepository->getMedicalExam($perPage,$status,$statusPayment,$idEmployee);
    }
    public function saveDoctorConclusion($data)
    {
        return $this->MedicalExamRepository->saveDoctorConclusion($data);
    }
    public function getPrescriptionAndService($idMedicalExam)
{
    return $this->MedicalExamRepository->getPrescriptionAndService($idMedicalExam);
}
}