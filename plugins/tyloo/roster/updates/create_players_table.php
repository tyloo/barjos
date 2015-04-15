<?php namespace Tyloo\Roster\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreatePlayersTable extends Migration
{

    public function up()
    {
        Schema::create('tyloo_roster_players', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable()->index();
            $table->string('title')->nullable();
            $table->string('slug')->index();
            $table->text('content')->nullable();
            $table->text('content_html')->nullable();
            $table->text('excerpt')->nullable();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('slot')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->boolean('published')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('tyloo_roster_players');
    }

}
