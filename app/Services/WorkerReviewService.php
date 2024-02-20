<?php
namespace App\Services ;//App\Services\OrderService
use App\Models\WorkerReview;//App\Models\Order
use App\Repositories\WorkerReviewRepository;//App\Repositories\OrderRepository
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
class WorkerReviewService{
    protected $workerReviewRepository ;
    public function __construct(WorkerReviewRepository $workerReviewRepository){
        $this->workerReviewRepository =$workerReviewRepository;
    }
    public function getAll(){
        return $this->workerReviewRepository->getAllWorkerReviews();
    }
    public function getById($workerReviewId){
        return $this->workerReviewRepository->getWorkerReviewById($workerReviewId);
    }
    public function create(array $workerReviewDetails){
        return $this->workerReviewRepository->createWorkerReview( $workerReviewDetails);
    }
    public function update($workerReviewId, array $newDetails){
        return $this->workerReviewRepository->updateWorkerReview($workerReviewId,  $newDetails) ;
    }
    public function delete($workerReviewId){
        return $this->workerReviewRepository->deleteWorkerReview($workerReviewId);
    }
    public function deleteAll(){
        return $this->workerReviewRepository->deleteAll();
    }
}


