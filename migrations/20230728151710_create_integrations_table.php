<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use Phpmig\Migration\Migration;

class CreateIntegrationsTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        Capsule::schema()->create('integrations', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->unique()->autoIncrement();
            $table->string('integrationId');

        });
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->dropIfExists('integrations');

    }
}
