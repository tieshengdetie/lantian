
<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('username', 20)->default('')->comment('用户昵称');
            $table->char('password', 200)->default('')->comment('用户密码');
            $table->string('avatar')->default('')->comment('用户头像');
            $table->char('email', 50)->default('')->unique('email')->comment('用户邮箱');
            $table->char('phone', 15)->default('')->unique('phone')->comment('用户手机号');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
}
