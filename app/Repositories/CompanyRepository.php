<?php
namespace App\Repositories;
use App\Interfaces\CompanyRepositoryInterface;
use App\Models\Company;


class CompanyRepository implements CompanyRepositoryInterface{
public function getAllCompanies() 
    {
        return Company::all();
    }

    public function getCompanyById($CompanyId) 
    {
        return Company::findOrFail($CompanyId);
    }

    public function deleteCompany($CompanyId) 
    {
        Company::destroy($CompanyId);
    }

    public function createCompany(array $CompanyDetails) 
    {
        return Company::create($CompanyDetails);
    }

    public function updateCompany($CompanyId, array $newDetails) 
    {
        return Company::whereId($CompanyId)->update($newDetails);
    }

    public function getFulfilledCompanies() 
    {
        return Company::where('is_fulfilled', true);
    }
}
