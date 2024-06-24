<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        $addresses = Address::where('user_id', auth()->user()->id)->get();

        return view('frontend.pages.checkout', compact('addresses'));
    }

    public function checkoutRedirect(Request $request)
    {
        $request->validate([
            'id' => ['required', 'integer']
        ]);

        // Retrieve the selected address by ID
        $address = Address::find($request->id);
        $selectedAddress = $address->address;

        // Ensure that the address is found
        if (!$address) {
            return response()->json(['error' => 'Address not found'], 404);
        }

        // Store the selected address and its ID in the session
        session()->put('address', $selectedAddress);
        session()->put('address_id', $address->id);

        // Return the redirect URL
        return response()->json(['redirect_url' => route('payment.index')]);
    }

    public function fetchAddress(Request $request)
    {
        $request->validate([
            'id' => ['required', 'integer']
        ]);

        // Retrieve the address by ID
        $address = Address::find($request->id);

        // Ensure that the address is found
        if (!$address) {
            return response()->json(['error' => 'Address not found'], 404);
        }

        // Calculate any additional details about the address if needed

        // Return the address details
        return response()->json($address);
    }
}
