<?php namespace Octobro\BankTransfer\Contracts;

Interface Order
{
    public static function findOrder($id);

    public function isPaid();
}
