<?php
namespace Net;
use App\Interfaces\NetRepositoryInterface;
use App\Models\Net\Net;


class NetRepository implements NetRepositoryInterface{
    public function getAllNets() 
    {
        return Net::all();
    }

    public function getNetById($NetId) 
    {
        return Net::findOrFail($NetId);
    }

    public function createNet(array $NetDetails) 
    {
        return Net::create($NetDetails);
    }

    public function updateNet($NetId, array $newDetails) 
    {
        return Net::whereId($NetId)->update($newDetails);
    }

    public function deleteNet($NetId) 
    {
        Net::destroy($NetId);
    }
    public function deleteAll(){
        return Net::all()->delete();
    }

    public function getFulfilledNets() 
    {
        return Net::where('is_fulfilled', true);
    }
}
