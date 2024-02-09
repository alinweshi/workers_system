<?php
namespace App\Services\OrderService;

use App\Models\Order;
use App\Models\Post;
use App\Repositories\OrderRepository;

class OrderService
{
    protected $orderRepository;
    protected $post;

    public function __construct(OrderRepository $orderRepository, Post $post)
    {
        $this->orderRepository = $orderRepository;
        $this->post = $post;
    }

    public function addOrder($request)
    {
        $data = $request->all();
        $post = $this->post->with('worker')->find($request->post_id);
        $clientId = auth()->guard("client")->user()->id;
        $data['worker_id'] = $post->worker->id;
        $data['post_title'] = $post->title;
        $data['client_id'] = $clientId;

        $order = Order::where('post_id', $this->post->with('worker')->find($request->post_id)->id)->where('client_id', auth()->guard("client")->user()->id)->exists();
        if ($order) {
            return false;
        }
        $this->create($data);
        return true;
    }

    public function checkIfExists($request)
    {
        $order = Order::where('post_id', $this->post->with('worker')->find($request->post_id)->id)->where('client_id', auth()->guard("client")->user()->id)->exists();
        if ($order) {
            return response()->json(["message" => "can not duplicate order"]);
        }
    }

    protected function create($data)
    {
        // Retrieve the post along with the worker relationship
        $this->orderRepository->create($data);
    }
    public function getAllOrders()
    {
        $orders = $this->orderRepository->all();
        return $orders;
    }

    public function getOrderById($id)
    {
        $order = $this->orderRepository->show($id);
        return $order;
    }

    public function getOrdersByClientId($clientId)
    {
        $result = $this->orderRepository->showByClientId($clientId);

        return $result;
    }
    public function updateOrder( $orderId,$request)
    {
        // $data = $request->all();
        $order = $this->orderRepository->update($request, $orderId);

    }
    public function deleteOrder($orderId)
    {
        $this->orderRepository->delete($orderId);
        return true;
    }
    public function deleteAll()
    {
        $this->orderRepository->deleteAll();
        return true;
    }
    public function isPaid($id, array $data)
    {
        $order = $this->orderRepository->find($id);
    
        if (!$order) {
            return false; // Or handle this case accordingly
        }
    
        $order = $this->orderRepository->is_paid($id, $data);
    
        return $order;
    }
    
    

}
