<?php
namespace App\Repositories;
use App\Interfaces\EmployeeRepositoryInterface;
use App\Models\Employee\Employee;


class EmployeeRepository implements EmployeeRepositoryInterface{
    public function getAllEmployees() 
    {
        return Employee::all();
    }

    public function getEmployeeById($EmployeeId) 
    {
        return Employee::findOrFail($EmployeeId);
    }

    public function createEmployee(array $EmployeeDetails) 
    {
        return Employee::create($EmployeeDetails);
    }

    public function updateEmployee($EmployeeId, array $newDetails) 
    {
        return Employee::whereId($EmployeeId)->update($newDetails);
    }

    public function deleteEmployee($EmployeeId) 
    {
        Employee::destroy($EmployeeId);
    }
    public function deleteAll(){
        return Employee::all()->delete();
    }

    public function getFulfilledEmployees() 
    {
        return Employee::where('is_fulfilled', true);
    }
}
