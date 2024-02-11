<?php

namespace App\Interfaces;

interface EmployeeRepositoryInterface
{
    public function getAllEmployees();
    public function getemployeeById($employeeId);
    public function deleteemployee($employeeId);
    public function createemployee(array $employeeDetails);
    public function updateemployee($employeeId, array $newDetails);
    public function getFulfilledEmployees();
}