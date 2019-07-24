<?php

namespace App\Services;

use App\Exceptions\InvalidRequestException;
use App\Http\Requests\OrderRequest;
use App\Jobs\CloseOrder;
use App\Models\Order;
use App\Models\ProductSku;
use App\Models\UserAddress;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrderService
{
    /**
     * @param object       $user   当前操作用户
     * @param null|integer $limit  每页条数，可不传
     * @return mixed       $orders 订单分页集合
     */
    public function paginateOrders($user, $limit = null)
    {
        $orders = Order::query()
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate($limit);

        return $orders;
    }

    /**
     * 开启事务保存订单保存商品sku信息更新订单并返回订单
     * @param  OrderRequest $request
     * @param  object       $user     当前操作用户
     * @return mixed                  返回新建的order对象
     */
    public function store(OrderRequest $request, $user)
    {
        //开启事务
        $order = DB::transaction(function () use($user, $request) {
            $address = UserAddress::query()->find($request->input('address_id'));
            //更新该地址最后使用时间
            $address->update(['last_used_at' => Carbon::now()]);
            //创建订单
            $order   = new Order([
                'address'      => [
                    'address'       => $address->full_address,
                    'zip'           => $address->zip,
                    'contact_name'  => $address->contact_name,
                    'contact_phone' => $address->contact_phone,
                ],
                'remark'       => $request->input('remark'),
                'total_amount' => 0,
            ]);
            //关联用户
            $order->user()->associate($user);
            $order->save();

            $totalAmount = 0;
            $items       = $request->input('items');
            //遍历提交sku
            foreach ($items as $data) {
                $sku  = ProductSku::query()->find($data['sku_id']);
                // 创建一个 OrderItem 并直接与当前订单关联
                $item = $order->items()->make([
                    'amount' => $data['amount'],
                    'price'  => $sku->price,
                ]);
                $item->product()->associate($sku->product_id);
                $item->productSku()->associate($sku);
                $item->save();
                $totalAmount += $sku->price * $data['amount'];
                if ($sku->decreaseStock($data['amount']) <= 0) {
                    throw new InvalidRequestException('该商品库存不足');
                }
            }

            // 更新订单总金额
            $order->update(['total_amount' => $totalAmount]);

            // 将下单的商品从购物车中移除
            $skuIds = collect($items)->pluck('sku_id');
            app(CartService::class)->deleteItems($user, $skuIds);

            return $order;
        });

        //延时任务，关闭未支付订单
        dispatch(new CloseOrder($order, config('app.order_ttl')));

        return $order;
    }
}
