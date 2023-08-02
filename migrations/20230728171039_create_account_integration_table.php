<?php

use Illuminate\Database\Schema\Blueprint;
use Phpmig\Migration\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class CreateAccountIntegrationTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        Capsule::schema()->create('account_integration', function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('integration_id');

        });
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->dropIfExists('account_integration');
    }
}
