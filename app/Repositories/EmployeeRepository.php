<?php
namespace App\Repositories;
use App\Interfaces\employeeRepositoryInterface;
use App\Models\employee;


class EmployeeRepository implements employeeRepositoryInterface{
public function getAllEmployees() 
    {
        return employee::all();
    }

    public function getemployeeById($employeeId) 
    {
        return employee::findOrFail($employeeId);
    }

    public function deleteemployee($employeeId) 
    {
        employee::destroy($employeeId);
    }

    public function createemployee(array $employeeDetails) 
    {
        return employee::create($employeeDetails);
    }

    public function updateemployee($employeeId, array $newDetails) 
    {
        return employee::whereId($employeeId)->update($newDetails);
    }

    public function getFulfilledEmployees() 
    {
        return employee::where('is_fulfilled', true);
    }
}
