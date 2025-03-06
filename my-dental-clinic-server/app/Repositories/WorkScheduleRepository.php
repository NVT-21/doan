<?php

namespace App\Repositories;
use App\Models\WorkSchedule;
 class WorkScheduleRepository extends BaseRepository
 {
    function getModel(){
        return WorkSchedule::class ;
    }
    public function getWorkSchedules($pageSize = 2, $year = null, $month = null, $name = null)
    {
        // Query cơ bản
        $query = WorkSchedule::with(['employee', 'workScheduleDetails.workShift']);
    
        // ✅ Lọc theo năm & tháng (nếu có)
        if ($year && $month) {
            $query->whereYear('registerDate', $year)
                  ->whereMonth('registerDate', $month);
        }
    
        // ✅ Lọc theo tên bác sĩ (nếu có)
        if ($name) {
            $query->whereHas('employee', function ($q) use ($name) {
                $q->where('fullName', 'LIKE', "%$name%");
            });
        }
    
        // ✅ Phân trang và format dữ liệu
        $workSchedules = $query->paginate($pageSize)
            ->through(function ($schedule) {
                return [
                    "name" => optional($schedule->employee)->fullName,
                    "registerDate" => $schedule->registerDate,
                    "work_schedule_details" => optional($schedule->workScheduleDetails)
                        ->map(function ($detail) {
                            if ($detail->status == "working") {
                                return optional($detail->workShift)->startTime . '-' . optional($detail->workShift)->endTime;
                            }
                            return null;
                        })
                        ->filter() 
                        ->join(', ')
                ];
            });
    
        return $workSchedules;
    }
    
    

   
   
 }