<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Slider;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;


class FrontendController extends Controller
{
    function index(): View {

        $sliders = Slider::where('status', 1)->get();

        $categories = Category::where(['show_at_home' => 1, 'status' =>1])->get();

        return view('frontend.home.index',
        compact(
            'sliders',
            'categories'

    ));
    }

    function showProduct(string $slug) : View{
        $product = Product::where(['slug' => $slug, 'status'=>1])->firstOrFail();
        return view('frontend.pages.product-view', compact('product'));
   }

   function loadProductModal($productId) {
        $product = Product::findOrFail($productId);
        return view('frontend.layouts.ajax-files.product-popup-modal', compact('product'))->render();

   }


function applyCoupon(Request $request) {

    $subtotal = $request->subtotal;
    $code = $request->code;

    $coupon = Coupon::where('code', $code)->first();

    if(!$coupon) {
        return response(['message' => 'Invalid Coupon Code.'], 422);
    }
    if($coupon->quantity <= 0){
        return response(['message' => 'Coupon has been fully redeemed.'], 422);
    }
    if($coupon->expire_date < now()){
        return response(['message' => 'Coupon has expired.'], 422);
    }

    if($coupon->discount_type === 'percent') {
        $discount = number_format($subtotal * ($coupon->discount / 100), 2);
    }elseif ($coupon->discount_type === 'amount'){
        $discount = number_format($coupon->discount, 2);
    }

    $finalTotal = $subtotal - $discount;

    session()->put('coupon', ['code' => $code, 'discount' => $discount]);

    return response(['message' => 'Coupon Applied Successfully.', 'discount' => $discount, 'finalTotal' => $finalTotal, 'coupon_code' => $code]);

}

function destroyCoupon() {
    try{
        session()->forget('coupon');
        return response(['message' => 'Coupon Removed!', 'grand_cart_total' => grandCartTotal()]);
    }catch(\Exception $e){
        logger($e);
        return response(['message' => 'Something went wrong']);

    }
}

}
