<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Stripe;
use App\User;
use App\Donation;
use App\Shoutbox;
use App\Group;
use \Toastr;
use Cache;
use Carbon\Carbon;

class DonationController extends Controller
{
    /**
     * Display Donation Packages
     *
     * @access public
     * @return donation.packages View
     */
    public function packages()
    {
        return view('donation.packages');
    }

    /**
    * Charging the Card (STRIPE LOGIC)
    *
    */
    public function charge(Request $request)
    {
        try {
        // For initialize Stripe package (LIVE API SECRET KEY HERE)
        Stripe::setApiKey("");

        //For creating a customer in Stripe system
        $customer = Customer::create(array(
            'email' => $request->stripeEmail,
            'source' => $request->stripeToken
        ));

        // To charge money from customer
        $charge = Charge::create(array(
            'customer' => $customer->id,
            'amount' => $request->amount,
            'currency' => 'usd',
            'description' => $request->title
        ));

        // For storing payment information locally
        $current = new Carbon();
        $expires_on = $current->addDays($request->time);
        $storePayment = Donation::create([
            'stripe_payment_id' => $charge->id,
            'user_id' => auth()->user()->id,
            'amount' => $charge->amount,
            'plan' => $request->title,
            'time' => $request->time,
            'rank' => auth()->user()->group->name,
            'status' => 1,
            'active' => 1,
            'expires_on' => $expires_on
        ]);

        // Lets find proper group
        $group = Group::where('name', '=', 'Supporter')->first();

        // Lets change the users group now and mark as donor
        $user = User::findOrFail(auth()->user()->id);
                $user->group_id = $group->id;
                $user->is_donor = 1;
                $user->save();

        // Activity Log
        \LogActivity::addToLog("Member " . $user->username . " has made a " . $charge->amount . " donation.");

        // Post To Shoutbox
        $appurl = config('app.url');
        Shoutbox::create(['user' => "1", 'mentions' => "1", 'message' => ":blue_heart: Thank you [url={$appurl}/" . $user->username . "." . $user->id . "]" . $user->username . "[/url] for your generous donation. Enjoy the perks. Long live ". config('other.title') ."! :blue_heart:"]);
        Cache::forget('shoutbox_messages');

        return redirect()->route('home')->with(Toastr::success('Your Donation Was Successful! Thank you soooooo much!', 'Yay!', ['options']));

        } catch (\Exception $ex) {

        return redirect()->route('home')->with(Toastr::error($ex->getMessage(), 'Error!', ['options']));
    }
    }
}
