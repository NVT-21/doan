<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\WorkScheduleController;
use App\Models\Appointment;

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
    // WorkSchedule
    Route::middleware('auth:sanctum')->group(function () {
        Route::post("/changePassword", [AuthController::class, 'changePassword'])->name("Auth.changePassword");
        Route::get("/getUser",[AuthController::class,'getUser'])->name("Auth.getUser");
        Route::post("/createWorkSchedule",[WorkScheduleController::class,'store'])->name("WorkSchedule.store");
    });
});