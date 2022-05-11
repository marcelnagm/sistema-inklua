<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFulltextIndexToContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        DB::statement("ALTER TABLE contents ADD FULLTEXT INDEX search (title, description)");
//        DB::statement("ALTER TABLE contents ADD FULLTEXT INDEX title (title)");
//        DB::statement("ALTER TABLE contents ADD FULLTEXT INDEX description (description)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE contents DROP INDEX search");
        DB::statement("ALTER TABLE contents DROP INDEX title");
        DB::statement("ALTER TABLE contents DROP INDEX description");
    }
}
