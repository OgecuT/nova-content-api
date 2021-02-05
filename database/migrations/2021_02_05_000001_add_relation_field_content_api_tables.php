<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationFieldContentApiTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('content_blocks', function (Blueprint $table) {
            $table->json('relation')->nullable()->after('fields')->comment('Хранит настройку связи с другой моделью');
        });
        
        Schema::table('content_blocks_items', function (Blueprint $table) {
            $table->string('relation_type')->nullable()->after('content')->comment('Тип связи');
            $table->integer('relation_id')->nullable()->after('relation_type')->comment('Ид связи');
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('content_blocks', function (Blueprint $table) {
            $table->dropColumn('relation');
        });
        
        Schema::table('content_blocks_items', function (Blueprint $table) {
            $table->dropColumn('relation_type');
            $table->dropColumn('relation_id');
        });
    }
}
