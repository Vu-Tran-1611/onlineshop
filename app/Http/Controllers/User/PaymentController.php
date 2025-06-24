<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Jobs\SendOrderNotificationMailJob;
use App\Models\Order;
use App\Models\OrderProduct;
use Carbon\Carbon;
use Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Cashier\Cashier;

class PaymentController extends Controller
{
    // Cash Payment
    public function store($payment_status, $payment_method)
    {
        Cart::session("checked");
        $cartConditions = Cart::getConditions();
        $coupon = "";
        if (count($cartConditions) > 0) {
            foreach ($cartConditions as $con) {
                $coupon .= $con->getName();
            }
        }
        $current = Carbon::now();
        $invoice_id = Auth::user()->id;
        $invoice_id .= $current->isoFormat('YYMMDDkkmm');
        $order =  Order::create([
            'invoice_id' =>  $invoice_id,
            'user_id' => Auth::user()->id,
            'sub_total' => Cart::getSubTotal(),
            'total' => Cart::getTotal(),
            "product_qty" => Cart::getTotalQuantity(),
            "payment_method" => $payment_method,
            "payment_status" => $payment_status,
            "user_address_id" => getUserOrderAddress(),
            "coupon" =>  $coupon,
            "order_status" => "pending",
        ]);
        foreach (Cart::getContent() as $item) {
            $variants = array();
            $index = 0;
            foreach ($item->attributes as $key => $attr) {
                if ($index > 3)  $variants[$key] = $attr;
                $index++;
            }
            OrderProduct::create([
                'order_id' =>  $order->id,
                'product_id' => $item->attributes->product_id,
                'vendor_id' => $item->attributes->vendor_id,
                'product_name' => $item->name,
                "variants" => json_encode($variants),
                "variant_total" => $index - 4,
                "unit_price" => $item->price,
                "qty" => $item->quantity,
            ]);
        };
        $ids = array();
        foreach (Cart::getContent() as $item) {
            $ids[] = $item->id;
        }
        Cart::clear();

        Cart::session(Auth::user()->id);
        foreach ($ids as $id) {
            Cart::remove($id);
        };
        SendOrderNotificationMailJob::dispatch(
            $order,
            auth()->user(),
            'order-pending'
        );
        session()->forget('user_delivery_address');
        return view("frontend.pages.payment-success", [
            "title" => "Payment Success"
        ]);
    }


    //  Stripe Payment 
    public function makePayment(Request $request)
    {

        if ($request->payment_method == 'cash') return self::store(0, 'cash');
        Cart::session("checked");
        $priceIDs = array();
        $stripe = new \Stripe\StripeClient(config('stripe.secret_key'));
        foreach (Cart::getContent() as $item) {
            $product =  $stripe->products->create([
                'name' => $item->name,
                'images' => [$item->attributes->imageURL],

            ]);
            $price = $stripe->prices->create([
                'product' => $product->id,
                'unit_amount' => $item->price * 100,
                'currency' => 'usd',
            ]);
            $priceIDs[$price->id] =  $item->quantity;
        };
        return $request->user()->checkout($priceIDs, [
            'success_url' => route('user.payment.payment-success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('user.payment.payment-cancel'),
        ]);
    }

    public function paymentSuccess(Request $request)
    {
        $sessionId = $request->session_id;

        if ($sessionId === null) {
            return redirect()->route('not-found');
        }

        $session = Cashier::stripe()->checkout->sessions->retrieve($sessionId);

        if ($session->payment_status !== 'paid') {
            return redirect()->route('not-found');
        }

        return $this->store(1, 'card');
    }

    public function paymentCancel()
    {
        return view("frontend.pages.payment-cancel", [
            "title" => "Payment Cancel"
        ]);
    }
}
