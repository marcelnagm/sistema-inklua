<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableContentsCreateFulltext extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE contents ADD FULLTEXT search(title, description)");
        DB::statement("ALTER TABLE contents ADD FULLTEXT title(title)");
        DB::statement("ALTER TABLE contents ADD FULLTEXT description(description)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
