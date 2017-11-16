<?php namespace Octobro\BankTransfer\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class AddUniqueNumberToInvoicesTable extends Migration
{
    public function up()
    {
        Schema::table('responsiv_pay_invoices', function(Blueprint $table) {
            $table->integer('unique_number')->unsigned()->nullable()->after('hash');
        });
    }

    public function down()
    {
        Schema::table('responsiv_pay_invoices', function(Blueprint $table) {
            $table->dropColumn('unique_number');
        });
    }
}
