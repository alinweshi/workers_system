<?php
namespace NetService;
use App\Interfaces\NetServiceRepositoryInterface;
use App\Models\Net\NetService;


class NetServiceRepository implements NetServiceRepositoryInterface{
    public function getAllNetServices() 
    {
        return NetService::all();
    }

    public function getNetServiceById($NetServiceId) 
    {
        return NetService::findOrFail($NetServiceId);
    }

    public function createNetService(array $NetServiceDetails) 
    {
        return NetService::create($NetServiceDetails);
    }

    public function updateNetService($NetServiceId, array $newDetails) 
    {
        return NetService::whereId($NetServiceId)->update($newDetails);
    }

    public function deleteNetService($NetServiceId) 
    {
        NetService::destroy($NetServiceId);
    }
    public function deleteAll(){
        return NetService::all()->delete();
    }

    public function getFulfilledNetServices() 
    {
        return NetService::where('is_fulfilled', true);
    }
}
