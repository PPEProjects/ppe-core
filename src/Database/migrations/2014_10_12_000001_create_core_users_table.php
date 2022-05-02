<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoreUsersTable extends Migration
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
        $this->schema->dropIfExists('users');
        $this->schema->create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('name');
            $table->string('platform')->default("email");
            $table->string('email')->unique();
            $table->json("roles")->nullable();
            $table->string('role_label')->nullable();
            $table->string('country')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->text('access_token_social')->nullable();
            $table->text('social_id')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('quotes')->nullable();
            $table->json('avatar_attachment')->nullable();
            $table->json('background_attachment')->nullable();
            $table->boolean('is_flag')->nullable();
            $table->string('banner')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
        Schema::connection("mysql")->dropIfExists('users');
        Schema::connection("mysql")->create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Illuminate\Support\Facades\Log::channel('single')->info('nvt 2', []);
        
        $this->schema->dropIfExists('users');
        Schema::connection("mysql")->dropIfExists('users');
    }
}
