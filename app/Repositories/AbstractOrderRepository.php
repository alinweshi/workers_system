<?php
namespace App\Repositories;

use App\Interfaces\OrderRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractOrderRepository implements OrderRepositoryInterface
{

    public function __construct(private Model $model)
    {
    }
    // public function getModel()
    // {
    //     return $this->model;
    // }
    // public function setModel(Model $model)
    // {
    //     $this->model = $model;
    // }
    public function all()
    {
        return $this->model->all();
    }
    public function show($id)
    {
        return $this->model->find($id);
    }
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $model = $this->model->find($id);

        if (!$model) {
            return null; // Or handle this case accordingly
        }

        $model->update($data);

        return $model;
    }
    public function delete($id)
    {
        // return $this->model->where('id',$id)->delete();
        $this->model->find($id)->delete();
    }
    public function deleteAll()
    {
        // return $this->model->where('id',$id)->delete();
        $models = $this->model->all();
        foreach ($models as $model) {
            $model->delete();
        }}
        public function is_paid($id, array $data)
        {
            $model = $this->model->find($id);
        
            if (!$model) {
                return false; // Or handle this case accordingly
            }
        
            $model->update($data);
        
            return $model;
        }
        
        public function is_completed($id, array $data){
            $model = $this->model->find($id);
            if (!$model) {
                return false; // Or handle this case accordingly
            }
            $model->update($data);
        
            return $model;

        }
        public function is_cancelled($id, array $data){
            $model = $this->model->find($id);
            if (!$model) {
                return false; // Or handle this case accordingly
            }
            $model->update($data);
            return $model;
        }
}
