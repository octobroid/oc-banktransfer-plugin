<?php namespace Octobro\BankTransfer\Components;

use Auth;
use Flash;
use Redirect;
use Mail;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use Octobro\BankTransfer\Models\PaymentConfirmation as Confirm;

class PaymentConfirmation extends ComponentBase
{

    public function componentDetails()
    {
        return [
            'name'        => 'PaymentConfirmation Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [
            'redirectPage' => [
                'title'       => 'Redirect Page',
                'description' => 'What page when the order is successfully submitted.',
                'type'        => 'dropdown',
            ],

            'successMessage' => [
                'title'       => 'Success Message',
                'description' => 'Success message if confirmation has done.',
                'type'        => 'text',
            ],
        ];
    }

    public function onRun()
    {

    }

    public function getRedirectPageOptions()
    {
        return Page::getNameList();
    }

    /**
     * Ajax handler triggered by payment confirmation form
     * @method onConfirmPayment
     * @return Void
     */
    public function onConfirmPayment()
    {
        $data = post();

        try{

            $order = $this->getOrderModel();

            if(!$order) {
                throw new \ApplicationException("Order doesn't exist");
            } else if(Confirm::whereOrderNo($data['order_no'])->exists()) {
                throw new \ApplicationException("You have confirmed your payment before, we will tell you if your payment has been confirmed.");
                // \Log::info(Confirm::whereOrderNo($data['order_no'])->exists());
            } else if($order->isPaid()) {
                throw new \ApplicationException("Your order has been paid");
            } else if($order->invoice->payment_method->name != "Bank Transfer") {
                throw new \ApplicationException("Your order didn't use bank transfer method");
            } else {
                if($confirm = Confirm::create($data)) {
                    $paymentConfirmation = $order->payment_confirmation()->orderBy('created_at', 'DESC')->first();
                    Mail::send('octobro.banktransfer::mail.payment_confirmation', compact('order', 'paymentConfirmation'), function($message) use($order) {
                        $message->to($order->email, $order->name);
                        $message->subject('Konfirmasi Pembayaran anda [#'.$order->order_no.']');
                    });
                }

                Flash::success($this->property("successMessage"));
                return Redirect::to(Page::url($this->property('redirectPage')));
            }

        } catch(Exception $e) {
            throw new \ApplicationException("Your order is not valid");
        }
    }

    protected function getOrderModel()
    {
        return (new Confirm)->order()->getRelated()->findOrder(post('order_no'));
    }
}
