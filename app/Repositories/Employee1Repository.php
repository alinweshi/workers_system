<?php
namespace App\Repositories;
use App\Interfaces\employee1RepositoryInterface;
use App\Models\employee1;


class Employee1Repository implements employee1RepositoryInterface{
public function getAllemployee1s() 
    {
        return employee1::all();
    }

    public function getemployee1ById($employee1Id) 
    {
        return employee1::findOrFail($employee1Id);
    }

    public function deleteemployee1($employee1Id) 
    {
        employee1::destroy($employee1Id);
    }

    public function createemployee1(array $employee1Details) 
    {
        return employee1::create($employee1Details);
    }

    public function updateemployee1($employee1Id, array $newDetails) 
    {
        return employee1::whereId($employee1Id)->update($newDetails);
    }

    public function getFulfilledemployee1s() 
    {
        return employee1::where('is_fulfilled', true);
    }
}
