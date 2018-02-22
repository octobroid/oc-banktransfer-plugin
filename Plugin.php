<?php namespace Octobro\BankTransfer;

use Backend;
use System\Classes\PluginBase;
use Responsiv\Pay\Models\Invoice;
use Responsiv\Pay\Models\PaymentMethod;

/**
 * payment Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'payment',
            'description' => 'Payment gateway for octommerce.',
            'author'      => 'octobro',
            'icon'        => 'icon-credit-card'
        ];
    }


    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {
    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return [
            'Octobro\BankTransfer\Components\PaymentConfirmation' => 'paymentConfirmation',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return [
            'octobro.banktransfer.access_payment_confirmation' => [
                'tab'   => 'Payments',
                'label' => 'Access Payment Confirmation'
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return []; // Remove this line to activate

        return [
            'payment' => [
                'label'       => 'payment',
                'url'         => Backend::url('octobro/banktransfer/mycontroller'),
                'icon'        => 'icon-leaf',
                'permissions' => ['octobro.banktransfer.*'],
                'order'       => 500,
            ],
        ];
    }

    /**
     * Registers any payment gateways implemented in this plugin.
     * The gateways must be returned in the following format:
     * ['className1' => 'alias'],
     * ['className2' => 'anotherAlias']
     */
    public function registerPaymentGateways()
    {
        return [
            'Octobro\BankTransfer\PaymentTypes\BankTransfer' => 'bank-transfer',
        ];
    }


    public function registerMailTemplates()
    {
        return [
            'octobro.banktransfer::mail.banktransfer_instructions' => 'Bank Transfer payment instructions.',
            'octobro.banktransfer::mail.payment_confirmation'      => 'Payment Confirmation',
        ];
    }

}
