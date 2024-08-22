<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\User;

class ProductController extends Controller
{
    

    public function index(){

        $porducts = Product::get();
        return view('welcome', ['porducts'=> $porducts]);
    }


    public function payment(Request $request, $product){
        if(empty(auth()->user())){
            return redirect()->route('login')->with('success', 'Please login first!');
        }
        $intent = auth()->user()->createSetupIntent();
        $product = Product::find($product);

        return view('payment-page', [
            'product'=> $product,
            'intent' => $intent,
            'user'=>auth()->user()
        ]);
    }

    public function processPayment(Request $request, String $product, $price)
    {
        if(empty(auth()->user())){
            return redirect()->route('login')->with('success', 'Please login first!');
        }
        $user = auth()->user();
        $paymentMethod = $request->input('payment_method');
        $user->createOrGetStripeCustomer();
        $user->addPaymentMethod($paymentMethod);
        try
        {
            $user->charge(
                $price*100, $paymentMethod,
                ['return_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',],
            );
        }
        catch (\Exception $e)
        {
            return back()->withErrors(['message' => 'Error creating subscription. ' . $e->getMessage()]);
        }
        return redirect()->route('product.index')->with('message', 'Payment successful!');
    }
    public function checkoutSuccess(Request $request)
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

        $session = $stripe->checkout->sessions->retrieve($request->session_id);
        info($session);

        echo "We have received your request and will let you know shortly.";

        exit;
    }
}
