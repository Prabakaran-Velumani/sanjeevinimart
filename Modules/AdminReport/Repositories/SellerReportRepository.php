<?php

namespace Modules\AdminReport\Repositories;
use App\Models\Order;
use App\Models\OrderPackageDetail;
use App\Models\OrderPayment;
use Illuminate\Support\Facades\DB;
use Modules\Product\Entities\Product;
use Modules\Review\Entities\SellerReview;
use Modules\MultiVendor\Entities\SellerAccount;
use Modules\Seller\Entities\SellerProduct;

class SellerReportRepository
{
    public function topSeller()
    {
        return SellerAccount::orderBy('total_sale_qty', 'desc')->with('user');
    }
    public function topCustomer()
    {
        $orderPackage = OrderPackageDetail::where('seller_id', auth()->id())->pluck('order_id')->toArray();
        $customers = Order::whereIn('id', $orderPackage)->pluck('customer_id')->toArray();

        return OrderPayment::whereIn('user_id', $customers)
            ->where('status', 1)
            ->select(DB::raw('user_id as user_id'), DB::raw('sum(amount) as total'))
            ->groupBy(DB::raw('user_id'))
            ->with('user')
            ->orderBy('total', 'desc');
    }
    public function topSellingItem()
    {
        return SellerProduct::where('user_id', auth()->id())->where('status', 1)->with('product', 'seller')->orderBy('total_sale', 'desc');
    }
    public function review()
    {
        return SellerReview::where('seller_id', auth()->id())
            ->where('status', 1)
            ->select('seller_id','id', DB::raw('avg(rating) as rating'), DB::raw('count(*) as number_of_review'))
            ->groupBy('seller_id','id')
            ->with('seller');
    }
    public function products()
    {
        return Product::with('brand', 'category', 'seller')->where('is_approved', 1)->latest();
    }
    public function order()
    {
        return Order::whereHas('packages', function ($q) {
            $seller_id = getParentSellerId();
            $q->where('seller_id', $seller_id);
        })->with('packages', 'customer')->latest();
    }
}
