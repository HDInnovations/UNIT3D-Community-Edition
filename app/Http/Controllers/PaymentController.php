<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\DonationTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use PrevailExcel\Nowpayments\Facades\Nowpayments;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{

    /**
     * Collect Order data and create Payment
     * @return Url
     */
    public function createCryptoPayment(Request $request)
    {
        $user = $request->user();
        $price = null;

        try {
            if ($request->tier == 1) {
                $price = 5.20;
            }
            elseif ($request->tier == 2) {
                $price = 10.40;
            }
            elseif ($request->tier == 3) {
                $price = 20.70;
            }
            elseif ($request->tier == 4) {
                $price = 41.40;
            }

            $data = [
                'price_amount'      => $price ?? 100,
                'price_currency'    => strtolower(request()->fiat) ?? 'usd',
                'order_id'          => request()->order_id ?? uniqid(),
                'pay_currency'      => strtolower(request()->coin) ?? 'btc',
                'success_url'       => config('app.url') . '/pages/donate?crypto=true&success=true',
                'cancel_url'        => config('app.url') . '/pages/donate?crypto=true&success=false',
            ];

            $paymentDetails = Nowpayments::createInvoice($data);

           $transaction = DonationTransaction::create([
                'user_id'       => $user->id,
                'item_id'       => $request->tier,
                'invoice_id'    => $paymentDetails['id'],
                'order_id'      => $paymentDetails['order_id'],
                'currency'      => $paymentDetails['pay_currency'],
                'confirmed'     => 0,
            ]);

            return Redirect::to($paymentDetails['invoice_url']);
        } 
        catch(Exception $e) {
            return to_route('donate')
                ->withError('Theres an error in the data!');
        }
    }
}
