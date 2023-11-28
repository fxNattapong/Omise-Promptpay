<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Omise;
use OmiseSource;
use OmiseCharge;
use App\Models\Charge;

class PaymentController extends Controller
{
    public function showHomePage() {
        return view('welcome');
    }

    public function showPaymentForm() {
        $charge = null;
        return view('payment_form', compact('charge'));
    }

    public function processPayment(Request $request) {
        define('OMISE_PUBLIC_KEY', Config::get('services.omise.public_key'));
        define('OMISE_SECRET_KEY', Config::get('services.omise.secret_key'));

        $charge = OmiseCharge::create([
            'amount' => $request->input('totalPrice') * 100,
            'currency' => 'thb',
            'return_uri' => 'http://127.0.0.1:8000/',
            'source' => $request->input('omiseSource'),
            // 'expires_at' => time() + (5 * 60)
        ]);
        $InsertRow = new Charge;
        $InsertRow->charge_id = $charge['id'];
        $InsertRow->save();

        return view('payment_form', compact('charge'));
    }

    public function handleOmiseWebhook(Request $request) {
        $payload = $request->all();

        \Log::info('Webhook Payload: ' . json_encode($payload));

        if (isset($payload['key']) && $payload['key'] === 'charge.complete') {
            Charge::where('charge_id', $payload['data']['id'])
                    ->update([
                        'webhook_status' => '1',
                        'updated_at' => now()
                    ]);
        }

        return response()->json(['status' => 'success']);
    }

    public function checkWebhookStatus(Request $request) {
        $charge_id = ($request->has('charge_id')) ? ($request->input('charge_id')) : null;

        $charge = Charge::select('webhook_status')
                        ->where('charge_id', $charge_id)
                        ->first();
    
        if ($charge->webhook_status === 1) {
            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'pending']);
        }
    }
    
}
