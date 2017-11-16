<?php namespace Octobro\BankTransfer\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreatePaymentConfirmationsTable extends Migration
{
    public function up()
    {
        Schema::create('octobro_banktransfer_payment_confirmations', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('order_no');
            $table->date('transfer_date')->nullable();
            $table->string('account_owner')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('transfer_amount')->nullable();
            $table->string('destination_account')->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('octobro_banktransfer_payment_confirmations');
    }
}
