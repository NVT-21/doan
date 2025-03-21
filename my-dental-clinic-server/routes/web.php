<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\WorkScheduleController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\MedicalExamController;
use App\Http\Controllers\ServiceController;
use App\Models\Appointment;
use App\Models\Room;
use App\Models\Service;

//Patient
Route::get("/",[PatientController::class,'index'])->name("patient.home");
Route::get("/patient",[PatientController::class,'patient'])->name("patient");
Route::get("/login",[PatientController::class,'signIn'])->name("signIn");
Route::post("/create",[PatientController::class,'store'])->name("patient.store");
//Auth
Route::get("/login",[AuthController::class,'showLoginForm'])->name("Auth.viewLogin");
Route::prefix('api')->group(function () {
    //AUTH
    Route::post("/register", [AuthController::class, 'register'])->name("Auth.register");
    Route::post("/login",[AuthController::class,'login'])->name("Auth.login");
    // APPOINTMENT
    Route::get("/getAppointments",[AppointmentController::class,'getAppointments'])->name("Appointment.getAppointments");
    Route::patch("/getAppointments/{id}",[AppointmentController::class,'update'])->name("Appointment.update");
    Route::get("/work-schedule/paging",[WorkScheduleController::class,'paging'])->name("WorkSchedule.paging");
    //Employee
    Route::get("/doctor-without-room",[EmployeeController::class,'getDoctorWithoutRoom']);
    Route::get("/getDoctorWorking",[WorkScheduleController::class,'getDoctorWorking']);
    //Room
    Route::get("/doctor-in-room/{id}",[RoomController::class,'getDoctorInRoom']);
    //Medical-exam
    Route::get("/patientByPhone",[PatientController::class,'findByNumberPhone']);
    Route::get("/roomOfDoctor/{id}",[EmployeeController::class,'getRoomOfDoctor']);
    Route::post("/createMedicalExam",[MedicalExamController::class,'store']);
    Route::get("/medicalExams",[MedicalExamController::class,'getMedicalExam']);
    // Service
    Route::post("/createService",[ServiceController::class,'createOrUpdate']);
    Route::get("/services",[ServiceController::class,'paging']);
    Route::delete("/service/{id}",[ServiceController::class,'delete']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post("/changePassword", [AuthController::class, 'changePassword'])->name("Auth.changePassword");
        Route::get("/getUser",[AuthController::class,'getUser'])->name("Auth.getUser");
        Route::post("/createWorkSchedule",[WorkScheduleController::class,'createOrUpdate'])->name("WorkSchedule.createOrUpdate");
        //Room
        Route::get("/rooms",[RoomController::class,'paging'])->name("Room.paging");
        Route::post("/createRoom",[RoomController::class,'store'])->name("Room.store");
        Route::post("/add-doctors-room",[RoomController::class,'updateDoctorsToRoom'])->name("Room.addDoctorsToRoom");
  
     

        
    });
});