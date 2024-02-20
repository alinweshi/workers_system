<?php

namespace App\Interfaces;


interface WorkerReviewRepositoryInterface
{
    public function getAllWorkerReviews();
    public function getWorkerReviewById($workerReviewId);
    public function createWorkerReview(array $workerReviewDetails);
    public function updateWorkerReview($workerReviewId, array $newDetails);
    public function deleteWorkerReview($workerReviewId);
    public function deleteAll();
    public function getFulfilledWorkerReviews();
}