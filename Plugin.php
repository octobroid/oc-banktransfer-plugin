<?php namespace Octobro\BankTransfer;

use Backend;
use RainLab\User\Models\User;
use System\Classes\PluginBase;
use Responsiv\Pay\Models\Invoice;
use Responsiv\Pay\Models\PaymentMethod;
use Octommerce\Octommerce\Models\Order;

/**
 * payment Plugin Information File
 */
class Plugin extends PluginBase
{
    public $require = ['Octommerce.Octommerce', 'RainLab.User'];

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
        \Event::listen('backend.menu.extendItems', function($manager) {
            $manager->addSideMenuItems('Octommerce.Octommerce', 'commerce', [
                'paymentconfirmation' => [
                    'label'       => 'Payment Confirmations',
                    'url'         => Backend::url('octobro/banktransfer/paymentconfirmation'),
                    'icon'        => 'icon-check',
                    'permissions' => ['octobro.banktransfer.*'],
                    'order'       => 500,
                ]
            ]);
        });

        Order::extend(function($model) {
            $model->belongsTo['payment_confirmation'] = [
                'Octobro\BankTransfer\Models\PaymentConfirmation',
                'key'      => 'order_no',
                'otherKey' => 'order_no'
            ];
        });

        User::extend(function($model) {
            $model->addDynamicMethod('getWaitingOrders', function() use ($model) {
                return $model->orders()->whereStatusCode('waiting')->get();
            });
        });
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
