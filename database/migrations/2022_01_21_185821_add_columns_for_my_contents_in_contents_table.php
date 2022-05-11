<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsForMyContentsInContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contents', function (Blueprint $table) {
            $table->bigInteger('user_id')->nullable()->after('group_id'); 
            $table->string('contract_type')->nullable()->after('category'); 
            $table->decimal('salary',10,2)->nullable()->after('description');
            $table->string('application')->nullable()->after('salary');
            $table->string('status')->nullable()->after('application');
            $table->date('published_at')->nullable()->after('status');
            $table->text('observation')->nullable()->after('published_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contents', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('contract_type');
            $table->dropColumn('salary');
            $table->dropColumn('application');
            $table->dropColumn('status');
            $table->dropColumn('published_at');
            $table->dropColumn('observation');
        });
    }
}
