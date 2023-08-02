<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use Phpmig\Migration\Migration;

class CreateAccessesTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        Capsule::schema()->create('accesses', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('account_id');
            $table->string('base_domain');
            $table->text('access_token');
            $table->text('refresh_token');
            $table->bigInteger('expires');
            $table->string('unisender_key')->nullable();

            $table->foreign('account_id')->references('id')->on('accounts');
        });
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->dropIfExists('accesses');
    }
}
