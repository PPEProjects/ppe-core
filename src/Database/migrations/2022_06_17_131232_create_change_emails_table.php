<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChangeEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    protected $schema;
    public function __construct()
    {
        $this->schema = Schema::connection(config('ppe.core_db_connections'));
    }
    public function up()
    {
        $this->schema->create('change_emails', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("user_id");
            $table->string("old_email");
            $table->string("new_email");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema->dropIfExists('change_emails');
    }
}
