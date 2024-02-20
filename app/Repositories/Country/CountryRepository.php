<?php
namespace Country;
use App\Interfaces\CountryRepositoryInterface;
use App\Models\Country\Country;


class CountryRepository implements CountryRepositoryInterface{
    public function getAllCountries() 
    {
        return Country::all();
    }

    public function getCountryById($CountryId) 
    {
        return Country::findOrFail($CountryId);
    }

    public function createCountry(array $CountryDetails) 
    {
        return Country::create($CountryDetails);
    }

    public function updateCountry($CountryId, array $newDetails) 
    {
        return Country::whereId($CountryId)->update($newDetails);
    }

    public function deleteCountry($CountryId) 
    {
        Country::destroy($CountryId);
    }
    public function deleteAll(){
        return Country::all()->delete();
    }

    public function getFulfilledCountries() 
    {
        return Country::where('is_fulfilled', true);
    }
}
