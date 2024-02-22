<?php
namespace App\Repositories;

use App\Interfaces\WorkerReviewRepositoryInterface;
use App\Models\WorkerReview;

class WorkerReviewRepository implements WorkerReviewRepositoryInterface
{
    public function getAllWorkerReviews()
    {
        return WorkerReview::all();
    }

    public function getWorkerReviewById($WorkerReviewId)
    {
        return WorkerReview::findOrFail($WorkerReviewId);
    }

    public function createWorkerReview(array $WorkerReviewDetails)
    {
        return WorkerReview::create($WorkerReviewDetails);
    }

    public function updateWorkerReview($WorkerReviewId, array $newDetails)
    {
        return WorkerReview::whereId($WorkerReviewId)->update($newDetails);
    }

    public function deleteWorkerReview($WorkerReviewId)
    {
        WorkerReview::destroy($WorkerReviewId);
    }
    public function deleteAll()
    {
        $workerReviews = WorkerReview::all();

        foreach ($workerReviews as $workerReview) {
            $workerReview->delete();
        }
    }

    public function getFulfilledWorkerReviews()
    {
        return WorkerReview::where('is_fulfilled', true);
    }
}
