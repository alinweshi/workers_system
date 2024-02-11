<?php
namespace App\Repositories;

use App\Models\Client;
use App\Models\Order;
use App\Repositories\AbstractOrderRepository;

class OrderRepository extends AbstractOrderRepository
{
    public function __construct(private Order $order, private Client $client)
    {
        // $this->setModel($order);
    }

    public function getOrder()
    {
        return $this->order;
    }
    public function setOrder(Order $order)
    {
        $this->order = $order;
    }
    public function show($orderId)
    {
        return $this->order->find($orderId);
    }
    public function showByClientId($clientId)
    {
        return $this->order->with('client')->where('client_id', $clientId)->get();
    }
    public function all()
    {
        return $this->order->all();

    }
    public function create($data)
    {$this->order->create($data);
    }
    public function update($orderId, $data)
    {
        $order = $this->order->find($orderId);
        if (!$order) {
            exit('no orders found');
            // return false; // Handle this case accordingly
        }
        $order->update([
            'post_id' => $data["post_id"],
        ]);
        return true;
    }

    public function delete($orderId)
    {
        $this->order->find($orderId)->delete();
    }
    public function deleteAll()
    {
        $orders = $this->order->all();
        foreach ($orders as $order) {
            $order->delete();
        }
    }
    public function find($id)
    {
        $order = $this->order->find($id);
        return $order;
    }

    public function is_paid($id, array $data)
    {
        $order = $this->order->find($id);

        if (!$order) {
            return false; // Or handle this case accordingly
        }

        $order->update($data);

        return $order;
    }
    public function is_completed($id, array $data)
    {
        $order = $this->order->find($id);

        if (!$order) {
            return false; // Or handle this case accordingly
        }

        $order->update($data);

        return $order;
    }
    public function is_cancelled($id, array $data)
    {
        $order = $this->order->find($id);

        if (!$order) {
            return false; // Or handle this case accordingly
        }

        $order->update($data);

        return $order;
    }

}
