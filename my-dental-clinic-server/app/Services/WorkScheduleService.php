<?php 
namespace App\Services;

use App\Repositories\WorkScheduleRepository;
use App\Repositories\WorkScheduleDetailRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable; 

class WorkScheduleService extends BaseService
{
    protected $WorkScheduleRepository;
    protected $WorkScheduleDetailRepository;
    public function __construct(WorkScheduleRepository $WorkScheduleRepository,WorkScheduleDetailRepository $WorkScheduleDetailRepository)
    {
        $this->WorkScheduleRepository = $WorkScheduleRepository;
        $this->WorkScheduleDetailRepository = $WorkScheduleDetailRepository;
        parent::__construct();
    }
    public function getRepository()
    {
        return $this->WorkScheduleRepository;
    }
    public function createOrUpdateWorkSchedule($idEmployee,$data)
    {
        try {
            return DB::transaction(function () use ($idEmployee, $data) {
                if(!empty($data['id']))
                {
                    $workSchedule=$this->WorkScheduleDetailRepository->getById($data['id']);
                    if (!$workSchedule) {
                        return [
                            "success" => false,
                            "message" => "Work schedule not found!"
                        ];
                    }
                    foreach ($data['status'] as $shiftId => $status) {
                        $this->WorkScheduleDetailRepository->updateWorkScheduleDetail( $workSchedule->id, $shiftId, $status);
                    }
                    return [
                        "success" => true,
                        "message" => "Work schedule updated successfully",
                        "workSchedule" => $workSchedule
                    ];
                }
                $registerDate = $data['registerDate'];
                $workSchedule = $this->WorkScheduleRepository->create([
                    'registerDate' => $registerDate,
                    'idEmployee' => $idEmployee
                ]);
    
                foreach ($data['status'] as $shiftId => $status) {
                    $this->WorkScheduleDetailRepository->create([
                        'workScheduleId' => $workSchedule->id,
                        'shiftId' => $shiftId,
                        'status' => $status,
                    ]);
                }
    
                return [
                    "success" => true,
                    "message" => "Work schedule created successfully",
                    "workSchedule" => $workSchedule
                ];
            });
        } catch (Throwable $e) {
            Log::error("Error creating work schedule: " . $e->getMessage());
    
            return [
                "success" => false,
                "message" => "Failed to create work schedule"
            ];
        }
    }
   public function workSchedules($input)
   {
    return $this->WorkScheduleRepository->getWorkSchedules($input);
   }
   public function getDoctorWorking($date,$time)
   {
    return $this->WorkScheduleRepository->getDoctorsByShiftWithExamCount($date,$time);
   }
}