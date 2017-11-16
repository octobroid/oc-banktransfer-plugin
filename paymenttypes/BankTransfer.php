<?php namespace Octobro\BankTransfer\PaymentTypes;

use Mail;
use Backend;
use Responsiv\Pay\Classes\GatewayBase;
use System\Models\MailTemplate;

class BankTransfer extends GatewayBase
{

    /**
     * {@inheritDoc}
     */
    public function gatewayDetails()
    {
        return [
            'name'        => 'Bank Transfer',
            'description' => 'Transfer with unique code.'
        ];
    }

    public function getMailTemplateCodeOptions()
    {
        return MailTemplate::lists('code', 'code');
    }

    /**
     * Returns the payment instructions for offline payment
     * @param  Model $host
     * @param  Model $invoice
     * @return string
     */
    public function getPaymentInstructions($host, $invoice)
    {
        return $host->payment_instructions;
    }

    public function processPaymentForm($data, $host, $invoice) {
        if ($invoice->unique_number) {
            return;
        }

        $invoice->unique_number = $this->generateUniqueNumber($invoice->total);

        $invoice->save();

        $mailTemplate = MailTemplate::whereCode($host->mail_template_code)->first();

        if ($mailTemplate) {

            Mail::send($mailTemplate->code, compact('invoice', 'host'), function($message) use ($invoice) {
                $message->to($invoice->email, $invoice->name);
            });
        }
    }

    protected function generateUniqueNumber($total, $digit = 2) {
        // Get current last n digit
        $lastNumber = (int) substr($total, -$digit);

        // Get random number from 1 to 10^n-1
        $randomNumber = rand(1, pow(10, $digit) - 1);

        return $randomNumber - $lastNumber;
    }

}
