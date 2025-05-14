<?php
namespace App\Repositories;

use App\Models\WorkSchedule;
use Illuminate\Pagination\LengthAwarePaginator;

class WorkScheduleRepository extends BaseRepository
{
    function getModel()
    {
        return WorkSchedule::class;
    }

    public function getWorkSchedules($input)
    {
        $year=$input['year'];
        $month=$input['month'];
        $name=$input['keyword'];
        $pageSize=$input['pageSize'];
        $employeeId = $input['employeeId'] ?? null; 
        $query = WorkSchedule::with(['employee', 'workScheduleDetails.workShift']);
        if ($employeeId ) {
            $query->where('idEmployee', $employeeId );
        }
        if ($year && $month) {
            $query->whereYear('registerDate', $year)
                  ->whereMonth('registerDate', $month);
        }
        if ($name) {
            $query->whereHas('employee', function ($q) use ($name) {
                $q->where('fullName', 'LIKE', "%$name%");
            });
        }
        $workSchedules = $query->paginate($pageSize);
        $transformed = collect($workSchedules->items())->map(function ($schedule) {
            return [
                "id"=>$schedule->id,
                "name" => optional($schedule->employee)->fullName,
                "registerDate" => $schedule->registerDate,
                "work_schedule_details" => $schedule->workScheduleDetails
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
        return new LengthAwarePaginator(
            $transformed,
            $workSchedules->total(),
            $workSchedules->perPage(),
            $workSchedules->currentPage(),
            ['path' => request()->url()]
        );
    }
    public function getDoctorsByShiftWithExamCount($date, $time)
    {
        // Xác định shiftId dựa trên thời gian
        $shiftId = null;
        if ($time >= 7 && $time < 13) {
            $shiftId = 1; // Ca sáng
        } elseif ($time >= 13 && $time < 17) {
            $shiftId = 2; // Ca chiều
        } elseif ($time >= 17 && $time < 23) {
            $shiftId = 3; // Ca tối
        }
    
        if (!$shiftId) {
            return collect([]); // Trả về collection rỗng
        }
    
        // Truy vấn danh sách bác sĩ làm việc trong ca + Đếm medicalExams có status = 'pending'
        $doctors = WorkSchedule::with([
            'employee' => function ($query) {
                $query->where('status', 'working') // Thêm điều kiện lọc theo status của employee
                      ->withCount([
                          'medicalExams as pending_exams_count' => function ($q) {
                              $q->where('status', 'pending');
                          }
                      ]);
            },
            'workScheduleDetails.workShift'
        ])
        ->where('registerDate', $date)
        ->whereHas('workScheduleDetails', function ($q) use ($shiftId) {
            $q->where('shiftId', $shiftId)
              ->where('status', 'working');
        })
        ->get()
        ->filter(fn($schedule) => $schedule->employee !== null) // Đảm bảo chỉ lấy các bác sĩ còn tồn tại sau khi lọc status
        ->sortBy(fn($schedule) => $schedule->employee->pending_exams_count ?? 0)
        ->pluck('employee');
    
        
    
        return $doctors;
    }
    
    public function getByDateAndEmployee($date, $employeeId)
{
    return WorkSchedule::whereDate('registerDate', $date)
        ->where('idEmployee', $employeeId)
        ->first();
}

  
   
}