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
    public function createWorkSchedule($idEmployee,$data)
    {
        try {
            return DB::transaction(function () use ($idEmployee, $data) {
                
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
   public function workSchedules()
   {
    return $this->WorkScheduleRepository->getWorkSchedules();
   }
}