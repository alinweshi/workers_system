<?php
namespace App\Services\TownService ;//App\Services\OrderService
use App\Models\TownService;//App\Models\Order
use App\Repositories\TownServiceRepository;//App\Repositories\OrderRepository
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
class TownServiceService{
    protected $townServiceRepository ;
    public function __construct(TownServiceRepository $townServiceRepository){
        $this->townServiceRepository =$townServiceRepository;
    }
    public function getAll(){
        return $this->townServiceRepository->getAll();
    }
    public function getById($id){
        return $this->townServiceRepository->getById($id);
    }
    public function create($data){
        return $this->townServiceRepository->create($data);
    }
    public function update($id, $data){
        return $this->townServiceRepository->update($id, $data);
    }
    public function delete($id){
        return $this->townServiceRepository->delete($id);
    }
    public function deleteAll(){
        return $this->townServiceRepository->deleteAll();
    }
}


