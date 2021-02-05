<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContentApiGroupTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_block_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });
        
        Schema::table('content_blocks', function (Blueprint $table) {
            $table->integer('group_id')->nullable()->after('id')->comment('Ид группы');
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('content_blocks_group');
        
        Schema::table('content_blocks', function (Blueprint $table) {
            $table->dropColumn('group_id');
        });
    }
}
