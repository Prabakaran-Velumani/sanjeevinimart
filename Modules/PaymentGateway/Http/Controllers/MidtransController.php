<?php

namespace Modules\PaymentGateway\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Traits\Accounts;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Repositories\OrderRepository;
use Modules\Account\Entities\Transaction;
use Illuminate\Contracts\Support\Renderable;
use Modules\UserActivityLog\Traits\LogActivity;
use Modules\Wallet\Repositories\WalletRepository;
use Modules\Account\Repositories\TransactionRepository;
use Modules\FrontendCMS\Entities\SubsciptionPaymentInfo;
use Modules\AuctionProducts\Entities\AuctionEntryAmountGatewayInfo;

class MidtransController extends Controller
{
    use Accounts;

    public function __construct()
    {
        $this->middleware('maintenance_mode');
    }

    public function paymentProcess($data)
    {
        $credential = $this->getCredential();
        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = @$credential->perameter_2;
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        if(@$credential->perameter_1 == 'sandbox'){
            \Midtrans\Config::$isProduction = false;
        }
        elseif(@$credential->perameter_1 == 'live'){
            \Midtrans\Config::$isProduction = true;
        }
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;


        try {

            if (session()->has('wallet_recharge')) {
                $walletService = new WalletRepository;
                $item = $walletService->walletRecharge($data['amount'], $credential->method->id, $data['ref_no']);
                $params = array(
                    'transaction_details' => array(
                        'order_id' => $item->txn_id,
                        'gross_amount' => $data['amount'],
                    )
                );

                LogActivity::successLog('Wallet recharge successful.');
            }
            if (session()->has('order_payment')) {
                $orderPaymentService = new OrderRepository;
                $item = $orderPaymentService->orderPaymentDone($data['amount'], $credential->method->id, $data['ref_no'], (auth()->check())?auth()->user():null);
                $params = array(
                    'transaction_details' => array(
                        'order_id' => $item->id,
                        'gross_amount' => $data['amount'],
                    )
                );

                LogActivity::successLog('Order payment successful.');
            }
            if (session()->has('subscription_payment')) {
                $orderPaymentService = new OrderRepository;
                $item = $orderPaymentService->orderPaymentDone($data['amount'], $credential->method->id, $data['ref_no']);

                $defaultIncomeAccount = $this->defaultIncomeAccount();
                $seller_subscription = getParentSeller()->SellerSubscriptions;
                $transactionRepo = new TransactionRepository(new Transaction);
                $transaction = $transactionRepo->makeTransaction(getParentSeller()->first_name." - Subsriction Payment", "in", "MidTrans", "subscription_payment", $defaultIncomeAccount, "Subscription Payment", $seller_subscription, $data['amount'], Carbon::now()->format('Y-m-d'), getParentSellerId(), null, null);
                $seller_subscription->update(['last_payment_date' => Carbon::now()->format('Y-m-d')]);
                $subscription_payment = SubsciptionPaymentInfo::create([
                    'transaction_id' => $transaction->id,
                    'txn_id' => $data['ref_no'],
                    'seller_id' => getParentSellerId(),
                    'subscription_type' => getParentSeller()->sellerAccount->subscription_type,
                    'commission_type' => @$seller_subscription->pricing->name
                ]);
                $params = array(
                    'transaction_details' => array(
                        'order_id' => $subscription_payment->id,
                        'gross_amount' => $data['amount'],
                    )
                );

                LogActivity::successLog('Subscription payment successful.');
            }

            if(session()->has('auction_entry_amount'))
            {

                AuctionEntryAmountGatewayInfo::create([
                    "gateway_id" => $data['payment_method'],
                    "entry_amount_payment_id" => $payment->id,
                    "payment_info" => json_encode($info),
                ]);
                $params = array(
                    'transaction_details' => array(
                        'order_id' => $subscription_payment->id,
                        'gross_amount' => $data['amount'],
                    )
                );
            }



            $paymentUrl = \Midtrans\Snap::createTransaction($params)->redirect_url;
            // Redirect to Snap Payment Page
            return redirect()->to($paymentUrl);
        }
        catch (Exception $e) {

            LogActivity::errorLog($e->getMessage());
            Toastr::error($e->getMessage());
            return redirect()->back();
        }
    }

    public function paymentSuccess(Request $request)
    {
        $credential = $this->getCredential();
        if ($request->transaction_status == "settlement" || $request->transaction_status == "capture") {
            if (session()->has('wallet_recharge')) {
                Toastr::success(__('payment_gatways.recharge_successfully'),__('common.success'));
                LogActivity::successLog('Wallet recharge successful.');
                return redirect()->route('my-wallet.index', auth()->user()->role->type);
            }
            if (session()->has('order_payment')) {
                $dat['payment_id'] = encrypt($request->order_id);
                $dat['gateway_id'] = encrypt($credential->method->id);
                $dat['step'] = 'complete_order';

                LogActivity::successLog('Order payment successful.');
                return redirect()->route('frontend.checkout', $dat);
            }
            if (session()->has('subscription_payment')) {
                auth()->user()->SellerSubscriptions->update(['last_payment_date' => Carbon::now()->format('Y-m-d')]);
                Toastr::success(__('common.payment_successfully'),__('common.success'));


                LogActivity::successLog('Subscription payment successful.');
                return redirect()->route('seller.dashboard');
            }
        }
    }

    public function paymentFailed(Request $request)
    {
        if (session()->has('wallet_recharge')) {
            $walletService = new WalletRepository;
            $item = $walletService->delete($request->order_id);

            Toastr::error(__('common.operation_failed'));
            return redirect()->route('my-wallet.index', auth()->user()->role->type);
        }
        if (session()->has('order_payment')) {
            $amount =  $data->payment->amount;
            $response = $data->payment->payment_id;
            $orderPaymentService = new OrderRepository;
            $order_payment = $orderPaymentService->orderPaymentDelete($request->order_id);
            Toastr::error(__('common.operation_failed'));

            LogActivity::successLog('Order payment failed.');
            return redirect()->route('frontend.checkout');
        }
        if (session()->has('subscription_payment')) {
            $subscription_info = SubsciptionPaymentInfo::findOrFail($request->order_id);

            $subscription_info->transaction->delete();
            $subscription_info->delete();
            Toastr::error(__('common.operation_failed'));

            LogActivity::successLog('Subscription payment failed.');
            return redirect()->route('seller.dashboard');
        }
    }

    private function getCredential(){
        $url = explode('?',url()->previous());
        if(isset($url[0]) && $url[0] == url('/checkout')){
            $is_checkout = true;
        }else{
            $is_checkout = false;
        }
        if(session()->has('order_payment') && app('general_setting')->seller_wise_payment && session()->has('seller_for_checkout') && $is_checkout){
            $credential = getPaymentInfoViaSellerId(session()->get('seller_for_checkout'), 'midtrans');
        }else{
            $credential = getPaymentInfoViaSellerId(1, 'midtrans');
        }
        return $credential;
    }
}
