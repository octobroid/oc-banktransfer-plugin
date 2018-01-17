<?php namespace Octobro\BankTransfer\Models;

use Backend\Models\ExportModel;

/**
 * Settings Model
 */
class ExportPaymentConfirmation extends ExportModel
{
	public function exportData($columns, $sessionKey = null)
	{
		$paymentConfirmations = PaymentConfirmation::all();
		$paymentConfirmations->each(function($paymentConfirmation) use ($columns) {
			$paymentConfirmation->addVisible($columns);
		});

		return $paymentConfirmations->toArray();
	}
}
