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

namespace App\Console\Commands;

use App\Models\DonationItem;
use App\Models\DonationSubscription;
use App\Models\DonationTransaction;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use PrevailExcel\Nowpayments\Facades\Nowpayments;
use Symfony\Component\Console\Exception\RuntimeException;

class AutoVerifyDonationTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:donation_transactions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for unconfirmed transactions and confirm them if they are paied (but user closed browser window before DB update).';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $curDate = Carbon::now()->toDateString();

        $unconfirmedTransactions = DonationTransaction::where('confirmed', '=', 0)->whereDate('created_at', '<=', $curDate)->get();

        foreach ($unconfirmedTransactions as $transaction) {
            // Get array of payment status and details by invoice_id
            $data = "limit=100&page=0&sortBy=created_at&orderBy=asc&dateFrom=".Carbon::now()->format('Y-m-d')."&invoiceId=".$transaction->invoice_id;
            $paymentStatus = Nowpayments::getListOfPayments($data);

            // Verify we filtered for the correct Payment from the API
            if ($paymentStatus['data'][0]['invoice_id'] != $transaction->invoice_id) {
                throw new RuntimeException('The gathered invoice id does not match the DB invoice id!');
            }

            // Check if Payment was successfully
            if ($paymentStatus['data'][0]['payment_status'] === "finished") {
                $transaction->update([
                    'payment_id' => $paymentStatus['data'][0]['payment_id'],
                    'confirmed'  => 1,
                ]);

                // Add User to donation_subscription table
                // Check if user has active or upcoming subscriptions
                $activeSubscriptionsEndDate = DonationSubscription::where('user_id', '=', $transaction->user_id)->where('donation_item_id', '>=', 4)->orderBy('end_at', 'DESC')->value('end_at');
                // Set start date accordingly
                if ($activeSubscriptionsEndDate !== null) {
                    $startDate = $activeSubscriptionsEndDate;
                } else {
                    $startDate = $curDate;
                }

                $donationItem = DonationItem::find($transaction->donation_item_id);
                $subscription = DonationSubscription::create([
                    'user_id'          => $transaction->user_id,
                    'donation_item_id' => $transaction->donation_item_id,
                    'is_active'        => 0,
                    'is_gifted'        => 0,
                    'start_at'         => $startDate,
                    'end_at'           => Carbon::parse($startDate)->addDays($donationItem->days_active)->toDateString(),
                ]);
            }
        }

        $this->comment('Automated transaction verification command complete');
    }
}
