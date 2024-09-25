<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    public function index()
    {
        return view('addEmployees');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:employees',
            'image' => 'required|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        }

        $employee = new Employee;
        $employee->name = $request->name;
        $employee->email = $request->email;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '.' . $extension;
            $file->move('uploads/employeesImages', $fileName);
            $employee->image = $fileName;
        }

        $employee->save();

        return response()->json([
            'status' => 200,
            'message' => 'Employee added successfully',
            'employee' => $employee,
        ]);
    }

    public function fetchEmployees(Request $request) {
        $query = Employee::query();
    
        // Search
        if ($request->has('search') && $request->search !== '') {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
    
        // Sort
        if ($request->has('sort_by') && $request->has('sort_order')) {
            $allowedSortFields = ['name', 'email', 'id']; // Specify allowed fields
            if (in_array($request->sort_by, $allowedSortFields)) {
                $query->orderBy($request->sort_by, $request->sort_order);
            }
        }
    
        // Pagination
        $employees = $query->paginate(5); // Adjust the number as needed
    
        return response()->json([
            'status' => 200,
            'employees' => $employees
        ]);
    }
    
    public function edit($id)
    {
        $employee = Employee::find($id);
        if (!$employee) {
            return response()->json(['status' => 404, 'message' => 'Employee not found']);
        }
        return response()->json(['status' => 200, 'employee' => $employee]);
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::find($id);
        if (!$employee) {
            return response()->json(['status' => 404, 'message' => 'Employee not found']);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:employees,email,' . $employee->id,
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        }

        $employee->name = $request->name;
        $employee->email = $request->email;

        if ($request->hasFile('image')) {
            if ($employee->image) {
                unlink(public_path('uploads/employeesImages/' . $employee->image));
            }
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '.' . $extension;
            $file->move('uploads/employeesImages', $fileName);
            $employee->image = $fileName;
        }

        $employee->save();

        return response()->json([
            'status' => 200,
            'message' => 'Employee updated successfully',
        ]);
    }

    public function destroy($id)
    {
        $employee = Employee::find($id);
        if (!$employee) {
            return response()->json(['status' => 404, 'message' => 'Employee not found']);
        }

        // Delete the image file from the server
        if ($employee->image) {
            unlink(public_path('uploads/employeesImages/' . $employee->image));
        }

        $employee->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Employee deleted successfully',
        ]);
    }
}
