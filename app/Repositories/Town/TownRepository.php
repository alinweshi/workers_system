<?php
namespace Town;
use App\Interfaces\TownRepositoryInterface;
use App\Models\Town\Town;


class TownRepository implements TownRepositoryInterface{
    public function getAllTowns() 
    {
        return Town::all();
    }

    public function getTownById($TownId) 
    {
        return Town::findOrFail($TownId);
    }

    public function createTown(array $TownDetails) 
    {
        return Town::create($TownDetails);
    }

    public function updateTown($TownId, array $newDetails) 
    {
        return Town::whereId($TownId)->update($newDetails);
    }

    public function deleteTown($TownId) 
    {
        Town::destroy($TownId);
    }
    public function deleteAll(){
        return Town::all()->delete();
    }

    public function getFulfilledTowns() 
    {
        return Town::where('is_fulfilled', true);
    }
}
