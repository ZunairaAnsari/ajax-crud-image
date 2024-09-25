<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    //
    public function index(){
        return view('addEmployees');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
          'name' =>'required|string|max:255',
          'email' =>'required|string|email|max:255|unique:employees',
          'image' =>'required|image|mimes:jpeg, jpg, png|max:2048'
        ]);
  
        if($validator->fails())
          {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages()
            ]);
          }
        else
        {
          $employee = New Employee;
          $employee->name = $request->name;
          $employee->email = $request->email;
  
        if($request->hasFile('image'))
            {
                $file = $request->file('image');
                $extension =$file->getClientOriginalExtension();
                $fileName =  time().'.'. $extension;
                $file->move('uploads/employeesImages', $fileName);
                $employee->image = $fileName;
            }
        }

        $employee->save();

        return response()->json([
           'status' => 200,
           'message' => 'Employee added successfully',
            'employee' => $employee
        ]);
     

    }

    public function fetchEmployees(){
        $employees = Employee::all();
        return response()->json([
           'status' => 200,
            'employees' => $employees
        ]);
    }

    public function edit($id) {
        $employee = Employee::find($id);
        if (!$employee) {
            return response()->json(['status' => 404, 'message' => 'Employee not found']);
        }
        return response()->json(['status' => 200, 'employee' => $employee]);
    }
    

}