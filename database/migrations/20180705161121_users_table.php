<?php


use Klever\App\Database\Migrations\Migration;
use \Illuminate\Database\Schema\Blueprint;

class UsersTable extends Migration
{
    public function up()
    {
        $this->schema->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    function down()
    {
        $this->schema->drop('users');
    }
}
