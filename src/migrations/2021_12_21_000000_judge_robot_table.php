<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class JudgeRobotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('simple_judge_robot_sessions', function (Blueprint $table) {
            $table->char('session_id',32)->primary();
            $table->unsignedInteger('remaining')->nullable();
            $table->timestamp('expired_time')->nullable()->index();
        });
        Schema::create('simple_judge_robot_attempts', function (Blueprint $table) {
            $table->char('session_id',32)->index();
            $table->unsignedTinyInteger('status');
            $table->timestamp('add_time')->nullable()->index();
            $table->string('ip',40)->nullable()->index();

            $table->foreign('session_id')->references('session_id')
                ->on('simple_judge_robot_sessions')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('simple_judge_robot_attempts');
        Schema::dropIfExists('simple_judge_robot_sessions');
    }
}
