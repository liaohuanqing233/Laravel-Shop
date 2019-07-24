<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCartRequest;
use App\Models\ProductSku;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartService;

    //注册CartService类
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $cartItems = $this->cartService->getItems($user);
        $addresses = $this->cartService->getAddresses($user);

        return view('cart.index', compact('cartItems', 'addresses'));
    }

    public function add(AddCartRequest $request)
    {
        $this->cartService->add($request->user(), $request->input('sku_id'), $request->input('amount'));

        return [];
    }

    public function remove(ProductSku $sku, Request $request)
    {
        $this->cartService->deleteItems($request->user(), $sku->id);

        return [];
    }
}
