<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\Playlist;
use App\Models\Payment as Pay;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Agreement;
use PayPal\Api\Payer;
use PayPal\Api\Plan;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Payment;
use PayPal\Api\PaymentDefinition;
use PayPal\Api\PayerInfo;
use PayPal\Api\PaymentExecution;
use Redirect;

class PaymentController extends Controller
{   
    private $apiContext;

    public function __construct() {
        $paypalConf = config('paypal');
        $this->apiContext = new ApiContext(new OAuthTokenCredential(
            $paypalConf['client_id'],
            $paypalConf['secret'])
        );
        $this->apiContext->setConfig($paypalConf['settings']);
    }

    public function payWithpaypal(Request $request) {
        if(! $request->has('id')) {
            return abort('404');
        }
        $playlist = Playlist::find($request->input('id'));
        if(! $playlist) {
            return abort('404');
        }

        $price = $playlist->price;
        $itemName = $playlist->title;
        if($playlist->price == 0) {
            $data = array (
                'playlist_id' => $playlist->id,
                'user_id' => auth()->user()->id,
                'access' => true,
                'payment_id' => null
            );
            Subscription::create($data);
            session()->put('success', 'masseges.subscription-success');
            return Redirect::route('playlist.show', $playlist->id);
        }
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $item_1 = new Item();
        $item_1->setName($itemName)
                    ->setCurrency('USD')
                    ->setQuantity(1)
                    ->setPrice($price);
        
        $item_list = new ItemList();
        $item_list->setItems(array($item_1));
        
        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal($price);
        
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription('');
        
        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(route('pay.status'))
            ->setCancelUrl(route('pay.status'));
        
        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));

        try {
            session()->put('playlist_id', $playlist->id);
            $payment->create($this->apiContext);
        } catch (\PayPal\Exception\PPConnectionException $ex) {
            if (\Config::get('app.debug')) {
                session()->put('error', __('masseges.Connection-timeout'));
                return Redirect::route('paywithpaypal');
            } else {
                session()->put('error', __('masseges.general-error'));
                return Redirect::route('paywithpaypal');
            }
        }
        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }
        session()->put('paypal_payment_id', $payment->getId());
        if (isset($redirect_url)) {
            return Redirect::away($redirect_url);
        }
        session()->put('error', __('general-error'));
        return Redirect::route('paywithpaypal');
    }

    public function getPaymentStatus() {

        $payment_id =  session()->get('paypal_payment_id');
        $playlist_id =  session()->get('playlist_id');

        session()->forget('paypal_payment_id');
        session()->forget('playlist_id');
        if(empty(request()->get('PayerID')) || empty(request()->get('token')) || empty(request()->get('paymentId'))) {
            session()->put('error', 'masseges.Payment-failed');
            return Redirect::route('home');
        }
        if($payment_id != request()->get('paymentId')) {
            session()->put('error', 'masseges.Payment-failed');
            return Redirect::route('home');
        }
        $payment = Payment::get(request()->get('paymentId'), $this->apiContext);
        $execution = new PaymentExecution();
        $execution->setPayerId(request()->get('PayerID'));
        
        $result = $payment->execute($execution, $this->apiContext);
        if ($result->getState() == 'approved') {
            session()->put('success', 'masseges.Payment-success');
            $data = array (
                'pay_id' => $result->getId()
            );
            $pay = Pay::create($data);
            $pay_id = $pay->id;
            $data = array (
                'playlist_id' => $playlist_id,
                'user_id' => auth()->user()->id,
                'access' => true,
                'payment_id' => $pay_id
            );
            Subscription::create($data);
            return Redirect::route('playlist.show', $playlist_id);
        }
        session()->put('error', 'masseges.Payment-failed');
        return Redirect::route('home');
    }
}
