<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkerReviewUpdateRequest ;
use App\Http\Requests\WorkerReviewStoreRequest ;
use App\Models\WorkerReview;
use Illuminate\Support\Facades\Validator;
use App\Services\WorkerReviewService;


class WorkerReviewController extends Controller
{
    protected $workerReview;
    protected $workerReviewService;
    public function __construct(WorkerReview $workerReview,WorkerReviewService $workerReviewService){
        $this->workerReviewService=$workerReviewService;
        $this->workerReview=$workerReview;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $workerReviews=$this->workerReviewService->getAll();
        if($workerReviews){
            return response()->json(["workerReviews"=>$workerReviews]);
        }
        return response()->json(["error"=>"no workerReviews found"]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(WorkerReviewStoreRequest $request)
    {
                try{
            $validator=Validator::make($request->all(),$request->rules());
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }
            $data=$validator->validated();
            $workerReviews=$this->workerReviewService->create($data);
            if(!$workerReviews){
                return response()->json(['error' => 'workerReviews could not be created'], 500);
            }
            return response()->json(['success' => 'workerReviews added successfully'],200);
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $workerReview=$this->workerReviewService->getById($id);
        if(!$workerReview){
                return response()->json(['error' => 'workerReview not found'], 404);
        }
         return response()->json(['workerReview' => $workerReview ], 500);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(WorkerReviewUpdateRequest $request, string $id)
    {
        try{
            $validator=Validator::make($request->all(),$request->rules());
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }
            $data=$validator->validated();
            $workerReviews=$this->workerReviewService->update($data,$id);
            if(!$workerReviews){
                return response()->json(['error' => 'workerReviews could not be updated'], 500);
            }
            return response()->json(['success' => 'workerReviews updated successfully'],200);
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
    $workerReview=$this->show($id);
    if($workerReview){
              $this->workerReviewService->delete($id);
              return response()->json(["message"=>"workerReview deleted successfully"],200);
    }
                return response()->json(['error' => 'no workerReview to delete'], 404);
        }

    /**
     * Remove the all resources from storage.
     */
    public function deleteAll()
    {
        $workerReviews=$this->workerReviewService->getAll();
    if($workerReviews){
              $this->workerReviewService->deleteAll();
              return response()->json(["message"=>"workerReviews deleted successfully"],200);
    }
                return response()->json(['error' => 'no workerReviews to delete'], 404);
        }

}
