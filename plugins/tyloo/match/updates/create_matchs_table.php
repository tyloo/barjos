<?php namespace Tyloo\Match\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateMatchsTable extends Migration
{

    public function up()
    {
        Schema::create('tyloo_match_matchs', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable()->index();
            $table->string('title')->nullable();
            $table->string('slug')->index();
            $table->text('excerpt')->nullable();
            $table->text('content')->nullable();
            $table->text('content_html')->nullable();
            $table->string('team1')->nullable();
            $table->string('team2')->nullable();
            $table->integer('team1_score')->unsigned()->nullable();
            $table->integer('team2_score')->unsigned()->nullable();
            $table->timestamp('match_date')->nullable();
            $table->boolean('match_played')->default(0);
            $table->string('match_location')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->boolean('published')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('tyloo_match_matchs');
    }

}
