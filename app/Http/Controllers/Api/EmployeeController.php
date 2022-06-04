<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class EmployeeController extends Controller
{
    public function __construct()  
    {
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        $employees = User::whereHas('roles' ,function($role) {
            return $role->where('name' ,'EMPLOYEE');
        })->get();

        return response()->json([
            'data' => $employees
        ]);
    }
}
