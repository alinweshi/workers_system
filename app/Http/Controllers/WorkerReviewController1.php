<?php

namespace App\Http\Controllers;

use App\Models\Worker_Review1;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\CreateWorkerReviewRequest1;
// Corrected namespace
class WorkerReviewController1 extends Controller
{
    public function addReview(CreateWorkerReviewRequest1 $request)
    {
        try{
            $validator=Validator::make($request->all(),$request->rules());
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }
            $data=$validator->validated();
            $review = Worker_Review1::create($data);
            if (!$review) {
                return response()->json(['error' => 'Review could not be created'], 500);
            }
            return response()->json(['success' => 'Review added successfully']);
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
