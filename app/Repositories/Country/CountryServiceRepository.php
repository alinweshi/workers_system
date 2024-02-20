<?php
namespace CountryService;
use App\Interfaces\CountryServiceRepositoryInterface;
use App\Models\Country\CountryService;


class CountryServiceRepository implements CountryServiceRepositoryInterface{
    public function getAllCountryServices() 
    {
        return CountryService::all();
    }

    public function getCountryServiceById($CountryServiceId) 
    {
        return CountryService::findOrFail($CountryServiceId);
    }

    public function createCountryService(array $CountryServiceDetails) 
    {
        return CountryService::create($CountryServiceDetails);
    }

    public function updateCountryService($CountryServiceId, array $newDetails) 
    {
        return CountryService::whereId($CountryServiceId)->update($newDetails);
    }

    public function deleteCountryService($CountryServiceId) 
    {
        CountryService::destroy($CountryServiceId);
    }
    public function deleteAll(){
        return CountryService::all()->delete();
    }

    public function getFulfilledCountryServices() 
    {
        return CountryService::where('is_fulfilled', true);
    }
}
