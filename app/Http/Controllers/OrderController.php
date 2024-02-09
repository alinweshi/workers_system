<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest\UpdateOrderRequest;
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

}
