<?php
namespace OrganizationService;
use App\Interfaces\OrganizationServiceRepositoryInterface;
use App\Models\OrganizationService;


class OrganizationServiceRepository implements OrganizationServiceRepositoryInterface{
    public function getAllOrganizationServices() 
    {
        return OrganizationService::all();
    }

    public function getOrganizationServiceById($OrganizationServiceId) 
    {
        return OrganizationService::findOrFail($OrganizationServiceId);
    }

    public function createOrganizationService(array $OrganizationServiceDetails) 
    {
        return OrganizationService::create($OrganizationServiceDetails);
    }

    public function updateOrganizationService($OrganizationServiceId, array $newDetails) 
    {
        return OrganizationService::whereId($OrganizationServiceId)->update($newDetails);
    }

    public function deleteOrganizationService($OrganizationServiceId) 
    {
        OrganizationService::destroy($OrganizationServiceId);
    }
    public function deleteAll(){
        return OrganizationService::all()->delete();
    }

    public function getFulfilledOrganizationServices() 
    {
        return OrganizationService::where('is_fulfilled', true);
    }
}
