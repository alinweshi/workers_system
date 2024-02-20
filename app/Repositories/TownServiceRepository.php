<?php
namespace TownService;
use App\Interfaces\TownServiceRepositoryInterface;
use App\Models\TownService;


class TownServiceRepository implements TownServiceRepositoryInterface{
    public function getAllTownServices() 
    {
        return TownService::all();
    }

    public function getTownServiceById($TownServiceId) 
    {
        return TownService::findOrFail($TownServiceId);
    }

    public function createTownService(array $TownServiceDetails) 
    {
        return TownService::create($TownServiceDetails);
    }

    public function updateTownService($TownServiceId, array $newDetails) 
    {
        return TownService::whereId($TownServiceId)->update($newDetails);
    }

    public function deleteTownService($TownServiceId) 
    {
        TownService::destroy($TownServiceId);
    }
    public function deleteAll(){
        return TownService::all()->delete();
    }

    public function getFulfilledTownServices() 
    {
        return TownService::where('is_fulfilled', true);
    }
}
