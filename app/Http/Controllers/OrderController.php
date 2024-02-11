<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest\UpdateOrderRequest;
use App\Http\Requests\OrderCancellationStatus;
use App\Http\Requests\OrderRequest;
use App\Services\OrderService\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    protected $OrderService;
    public function __construct(OrderService $OrderService)
    {

        $this->OrderService = $OrderService;
    }
    public function addOrder(OrderRequest $request)
    {
        if ($this->OrderService->checkIfExists($request)) {
            return response()->json(['message' => 'order already exists'], 400);
        }
        if ($this->OrderService->addOrder($request)) {
            return response()->json(['message' => 'order created successfully'], 201);
        }
        return response()->json(['message' => 'order not created'], 400);
    }
    public function getAllOrders()
    {
        $orders = $this->OrderService->getAllOrders();
        return $orders;
    }

    public function getOrderById($id)
    {
        $order = $this->OrderService->getOrderById($id);
        if ($order) {
            return response()->json(['orders' => $order], 200);
        }
        return response()->json(['message' => 'no orders found'], 400);
    }

    public function getOrdersByClientId($ClientId)
    {
        $orders = $this->OrderService->getOrdersByClientId($ClientId);
        if ($orders) {
            return response()->json(['orders' => $orders], 200);
        }
        return response()->json(['message' => 'no orders found'], 400);

    }

    public function update($orderId, UpdateOrderRequest $request)
    {
        try {
            $validatedData = $request->validate($request->rules());

            if ($this->OrderService->updateOrder($validatedData, $orderId)) {
                return response()->json(['message' => 'order updated successfully'], 200);
            }

            return response()->json(['message' => 'order not updated'], 400);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => $e->validator->errors()], 400);
        }
    }
    public function delete($orderId)
    {
        try {
            if ($this->OrderService->deleteOrder($orderId)) {
                return response()->json(['message' => 'order deleted successfully'], 200);
            }
            return response()->json(['message' => 'order not deleted '], 400);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }

    }
    public function deleteAll()
    {
        try {
            if ($this->OrderService->deleteAll()) {
                return response()->json(['message' => 'orders deleted successfully'], 200);
            }
            return response()->json(['message' => 'orders not deleted '], 400);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
    public function is_paid($id, Request $request)
    {
        // Validate the request data
        $data = $request->all();

        try {
            // Call the service method to mark the order as paid
            if ($this->OrderService->isPaid($id, $data)) {
                if ($data['is_paid'] == 0) {
                    return response()->json(['message' => 'Order not paid '], 200);
                }
                return response()->json(['message' => 'Order paid successfully'], 200);
            }
            return response()->json(['message' => 'Failed to mark order as paid.'], 400);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
    public function is_completed($id, Request $request)
    {
        $data = $request->all();
        if ($this->OrderService->isPaid($id, $data)) {
            if ($data['is_completed'] == 0) {
                return response()->json(['message' => 'you have changed status successfully,Complete status is 0 '], 200);
            }
            return response()->json(['message' => 'you have changed status successfully,Complete status is 1'], 200);
        }
        return response()->json(['message' => 'failed to change complete status.'], 400);
    }
    public function is_cancelled($id, OrderCancellationStatus $request)
    {
        $validatedData = Validator::make($request->all(), $request->rules());
        if ($validatedData->fails()) {
            return response()->json(['errors' => $validatedData->errors()], 400);
        }
        $validatedData = $validatedData->validated();

        
        // This will automatically perform validation based on the rules in OrderCancellationStatus

        // If validation fails, Laravel will automatically return a response with the validation errors
        // If validation passes, $validatedData will contain the validated input

        try {
            if ($this->OrderService->is_cancelled($id, $validatedData)) {
                if ($validatedData['is_cancelled'] == 0) {
                    return response()->json(['message' => 'you have changed status successfully,cancellation status is 0' . "\n\n" . "cancellation_reason is: " . $validatedData['cancellation_reason']], 200);
                }
                return response()->json(['message' => 'you have changed status successfully,cancellation status is 1'], 200);
            }
            return response()->json(['message' => 'failed to change cancellation status.'], 400);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => $e->validator->errors()], 400);
        }
    }

}
