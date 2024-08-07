<?php

namespace App\Http\Controllers\Frontend;

use App\Events\OrderPaymentUpdateEvent;
use App\Events\OrderPlacedNotificationEvent;
use App\Events\RTOrderPlacedNotificationEvent;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Srmklive\PayPal\Services\PayPal as PayPalClient;



class PaymentController extends Controller
{
    function index(): View
    {
        if (!session()->has('address')) {
            throw ValidationException::withMessages(['Something went wrong']);
        }

        $subtotal = cartTotal();
        $discount = session()->get('coupon')['discount'] ?? 0;
        $grandTotal = grandCartTotal();
        return view('frontend.pages.payment', compact(
            'subtotal',
            'discount',
            'grandTotal'
        ));
    }

    function paymentSuccess() : View {
        return view('frontend.pages.payment-success');
    }

    function paymentCancel() : View {
        return view('frontend.pages.payment-cancel');
    }

    function makePayment(Request $request, OrderService $orderService)
    {
        $request->validate([
            'payment_gateway' => ['required', 'string', 'in:paypal']
        ]);

        /** Create Order */
        if ($orderService->createOrder()) {
            // redirect user to the payment host
            switch ($request->payment_gateway) {
                case 'paypal':
                    return response(['redirect_url' => route('paypal.payment')]);
                    break;

                default:
                    break;
            }
        }
    }


    /** Paypal Payment  */
    function setPaypalConfig(): array
    {
        $config = [
            'mode'    => config('gatewaySettings.paypal_account_mode'),
            'sandbox' => [
                'client_id'         => config('gatewaySettings.paypal_api_key'),
                'client_secret'     => config('gatewaySettings.paypal_secret_key'),
                'app_id'            => 'APP-80W284485P519543T',
            ],
            'live' => [
                'client_id'         => config('gatewaySettings.paypal_api_key'),
                'client_secret'     => config('gatewaySettings.paypal_secret_key'),
                'app_id'            => config('gatewaySettings.paypal_app_id'),
            ],

            'payment_action' => 'Sale', // Can only be 'Sale', 'Authorization' or 'Order'
            'currency'       => config('gatewaySettings.paypal_currency'),
            'notify_url'     => env('PAYPAL_NOTIFY_URL', ''), // Change this accordingly for your application.
            'locale'         => 'en_US', // force gateway language  i.e. it_IT, es_ES, en_US ... (for express checkout only)
            'validate_ssl'   => true, // Validate SSL when creating api client.
        ];

        return $config;
    }

    function payWithPaypal()
    {
        $config = $this->setPaypalConfig();
        $provider = new PayPalClient($config);
        $provider->getAccessToken();

        /** calculate payable amount */
        $grandTotal = session()->get('grand_total');
        $payableAmount = round($grandTotal * config('gatewaySettings.paypal_rate'));

        $response = $provider->createOrder([
            'intent' => "CAPTURE",
            'application_context' => [
                'return_url' => route('paypal.success'),
                'cancel_url' => route('paypal.cancel')
            ],
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => config('gatewaySettings.paypal_currency'),
                        'value' => $payableAmount
                    ]
                ]
            ]
        ]);

        if(isset($response['id']) && $response['id'] != NULL){
            foreach($response['links'] as $link){
                if($link['rel'] === 'approve'){
                    return redirect()->away($link['href']);
                }
            }
        }else {
            return redirect()->route('payment.cancel')->withErrors(['error' => $response['error']['message']]);
        }
    }

    function paypalSuccess(Request $request, OrderService $orderService)
    {
        $config = $this->setPaypalConfig();
        $provider = new PayPalClient($config);
        $provider->getAccessToken();

        $response = $provider->capturePaymentOrder($request->token);


        if(isset($response['status']) && $response['status'] === 'COMPLETED'){

            $orderId = session()->get('order_id');

            $capture = $response['purchase_units'][0]['payments']['captures'][0];
            $paymentInfo = [
                'transaction_id' => $capture['id'],
                'currency' => $capture['amount']['currency_code'],
                'status' => 'completed'
            ];

            OrderPaymentUpdateEvent::dispatch($orderId, $paymentInfo, 'PayPal');
            OrderPlacedNotificationEvent::dispatch($orderId);
            RTOrderPlacedNotificationEvent::dispatch(Order::find($orderId));

            /** Clear session data */
            $orderService->clearSession();

            return redirect()->route('payment.success');
        }else {
            return redirect()->route('payment.cancel')->withErrors(['error' => $response['error']['message']]);
        }
    }

    function paypalCancel()
    {
        return redirect()->route('payment.cancel');
    }
    



}
