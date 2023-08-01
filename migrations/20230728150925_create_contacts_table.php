<?php

use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Schema\Blueprint;
use Phpmig\Migration\Migration;

class CreateContactsTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        Manager::schema()->create('contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->nullable();
            $table->unsignedBigInteger('account_id');

            $table->foreign('account_id')->references('id')->on('accounts');
        });
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Manager::schema()->dropIfExists('contacts');
    }
}
