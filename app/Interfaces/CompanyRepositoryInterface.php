<?php

namespace App\Interfaces\Company;


interface CompanyRepositoryInterface
{
    public function getAllCompanies();
    public function getCompanyById($companyId);
    public function createCompany(array $companyDetails);
    public function updateCompany($companyId, array $newDetails);
    public function deleteCompany($companyId);
    public function deleteAll();
    public function getFulfilledCompanies();
}