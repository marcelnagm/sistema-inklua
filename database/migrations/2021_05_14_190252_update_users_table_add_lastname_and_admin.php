<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTableAddLastnameAndAdmin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `users`  ADD COLUMN `lastname` VARCHAR(250) NULL AFTER `name`;");
        DB::statement("ALTER TABLE `users` ADD COLUMN `admin` TINYINT(1) NULL DEFAULT 0 AFTER `updated_at`;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `users` DROP COLUMN `lastname`;");
        DB::statement("ALTER TABLE `users` DROP COLUMN `admin`;");
    }
}
