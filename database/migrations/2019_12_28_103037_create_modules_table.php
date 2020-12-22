<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('Name');
            $table->boolean('response_plugin')->default(false);
            $table->boolean('timeline_plugin')->default(false);
            $table->timestamps();
        });

        \Illuminate\Support\Facades\DB::table('modules')->insert([
            ['Name' => 'Login Server', 'response_plugin' => false, 'timeline_plugin' => true, 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['Name' => 'Game Server', 'response_plugin' => true, 'timeline_plugin' => false, 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['Name' => 'Web Site', 'response_plugin' => false, 'timeline_plugin' => true, 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modules');
    }
}
