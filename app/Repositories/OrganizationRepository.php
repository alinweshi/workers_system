<?php
namespace Organization;
use App\Interfaces\OrganizationRepositoryInterface;
use App\Models\Organization;


class OrganizationRepository implements OrganizationRepositoryInterface{
    public function getAllOrganizations() 
    {
        return Organization::all();
    }

    public function getOrganizationById($OrganizationId) 
    {
        return Organization::findOrFail($OrganizationId);
    }

    public function createOrganization(array $OrganizationDetails) 
    {
        return Organization::create($OrganizationDetails);
    }

    public function updateOrganization($OrganizationId, array $newDetails) 
    {
        return Organization::whereId($OrganizationId)->update($newDetails);
    }

    public function deleteOrganization($OrganizationId) 
    {
        Organization::destroy($OrganizationId);
    }
    public function deleteAll(){
        return Organization::all()->delete();
    }

    public function getFulfilledOrganizations() 
    {
        return Organization::where('is_fulfilled', true);
    }
}
