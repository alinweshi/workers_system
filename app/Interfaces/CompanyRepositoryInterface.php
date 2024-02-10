<?php

namespace App\Interfaces;

interface CompanyRepositoryInterface
{
    public function getAllCompanies();
    public function getCompanyById($CompanyId);
    public function deleteCompany($CompanyId);
    public function createCompany(array $CompanyDetails);
    public function updateCompany($CompanyId, array $newDetails);
    public function getFulfilledCompanies();
}
