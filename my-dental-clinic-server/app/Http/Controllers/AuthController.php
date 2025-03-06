<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Services\EmployeeService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class AuthController extends ApiResponseController
{
    protected $AuthService ;
    protected $EmployeeService ;
    public function __construct(AuthService $AuthService ,EmployeeService $EmployeeService)
    {
        $this->AuthService = $AuthService;
        $this->EmployeeService = $EmployeeService;
    }
    public function showLoginForm()
    {
        return view ('Auth.login');
    }
        public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $result = $this->AuthService->login($credentials);

        if ($result['success']) {
           return $this->success($result['message'], $result['token']);
        } else {
        
            return $this->error($result['message']);
        }
    }
    public function register (Request $request)
    {
        $credential = $request->only('email', 'password');
        $role = $request->only('role');
        $employee = $request->only('fullName', 'phoneNumber', 'gender', 'birthday');
        if ($this->AuthService->isEmail($credential['email'])) {
            return response()->json(['message' => 'Email already exists'], 400); 
        }
        DB::beginTransaction();
    
        try {
            // Gán vai trò cho user và tạo mới user
            $newUser = $this->AuthService->assignRole($credential, $role);
    
            if ($newUser) {
                // Thêm user_id vào mảng employee
                $employee['user_id'] = $newUser->id;
    
                // Tạo mới employee
                $this->EmployeeService->createEmployee($employee);
    
                // Commit transaction nếu mọi thứ thành công
                DB::commit();
    
                return response()->json(['message' => 'Successfully registered'], 200);
            } else {
                // Nếu không thể tạo user, rollback
                DB::rollBack();
                return response()->json(['message' => 'Registration failed'], 400);
            }
        } catch (\Exception $e) {
            // Nếu có lỗi xảy ra trong quá trình tạo user hoặc employee, rollback
            DB::rollBack();
    
            Log::error("Error during registration: " . $e->getMessage());
    
            return response()->json(['message' => 'An error occurred'], 500);
        }
    }
    public function changePassword (Request $request)
    {
        $credential=$request->all();
        $result = $this->AuthService->changePassword($credential);
        if ($result['success']) {
            return $this->success($result['message']);
        } else {
            return $this->error($result['message']);
        }
    }
    public function getUser ()
    {
        return $this->AuthService->getUser() ;
    }

}
