<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCnpjPhoneFantasyNameColumnsInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('cnpj')->nullable()->after('password');
            $table->string('fantasy_name')->nullable()->after('cnpj');
            $table->string('phone')->nullable()->after('fantasy_name');
            $table->string('type')->nullable()->after('accepted_terms');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('cnpj');
            $table->dropColumn('fantasy_name');
            $table->dropColumn('phone');
            $table->dropColumn('type');
        });
    }
}
