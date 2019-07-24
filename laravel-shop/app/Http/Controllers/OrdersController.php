<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Jobs\CloseOrder;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        $orders = $this->orderService->paginateOrders($request->user());

        return view('orders.index', compact('orders'));
    }

    /**
     * @param Order $order
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException exception 权限限制
     */
    public function show(Order $order)
    {
        $this->authorize('own', $order);

        return view('orders.show', compact('order'));
    }

    public function store(OrderRequest $request)
    {
        //新建订单
        $order = $this->orderService->store($request, $request->user());

        return $order;
    }
}
