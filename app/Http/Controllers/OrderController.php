<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest\UpdateOrderRequest;
use App\Http\Requests\OrderCancellationStatus;
use App\Http\Requests\OrderIsCompleteRequest;
use App\Http\Requests\OrderIsPaidRequest;
use App\Http\Requests\OrderRequest;
use App\Services\OrderService\OrderService;
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
    public function isPaid($id, OrderIsPaidRequest $request)
    {
        $validatedData = $this->OrderService->validateOrder($request);

        try {
            $order = $this->OrderService->isPaid($id, $validatedData);
            $message = $validatedData['is_paid'] == 0 ? 'Order not paid' : 'Order paid successfully';
            return response()->json(['message' => $message], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
    public function isCompleted($id, OrderIsCompleteRequest $request)
    {

        $validatedData = $this->OrderService->validateOrder($request);

        try {
            $order = $this->OrderService->isCompleted($id, $validatedData);
            $message = $validatedData['is_completed'] == 0 ? 'you have changed status successfully,Complete status is 0' : 'you have changed status successfully,Complete status is 1';
            return response()->json(['message' => $message], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }

    }
    public function is_cancelled($id, OrderCancellationStatus $request)
    {
        try {
            $validatedData = $this->OrderService->validateOrder($request);
            if ($this->OrderService->isCancelled($id, $validatedData)) {
                $message = $validatedData['is_cancelled'] == 0 ? 'You have changed status successfully. Cancellation status is 0.' . "Cancellation reason is: " . $validatedData['cancellation_reason'] :
                'you have changed status successfully,cancellation status is 1';
                return response()->json(['message' => $message], 200);}
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

}
