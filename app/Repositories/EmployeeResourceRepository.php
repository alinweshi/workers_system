<?php
namespace EmployeeResource;
use App\Interfaces\EmployeeResourceRepositoryInterface;
use App\Models\EmployeeResource;


class EmployeeResourceRepository implements EmployeeResourceRepositoryInterface{
    public function getAllEmployeeResources() 
    {
        return EmployeeResource::all();
    }

    public function getEmployeeResourceById($EmployeeResourceId) 
    {
        return EmployeeResource::findOrFail($EmployeeResourceId);
    }

    public function createEmployeeResource(array $EmployeeResourceDetails) 
    {
        return EmployeeResource::create($EmployeeResourceDetails);
    }

    public function updateEmployeeResource($EmployeeResourceId, array $newDetails) 
    {
        return EmployeeResource::whereId($EmployeeResourceId)->update($newDetails);
    }

    public function deleteEmployeeResource($EmployeeResourceId) 
    {
        EmployeeResource::destroy($EmployeeResourceId);
    }
    public function deleteAll(){
        return EmployeeResource::all()->delete();
    }

    public function getFulfilledEmployeeResources() 
    {
        return EmployeeResource::where('is_fulfilled', true);
    }
}
