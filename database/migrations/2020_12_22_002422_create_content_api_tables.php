<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContentApiTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_blocks', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Названия');
            $table->string('code')->comment('Код');
            $table->integer('sort')->default(1)->comment('Порядок');
            $table->text('description')->nullable()->comment('Описание');
            $table->json('fields')->nullable()->comment('Набор полей');
            $table->timestamps();
        });
        
        Schema::create('content_blocks_items', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Описание/Название контента который внутри');
            $table->unsignedInteger('block_id')->comment('ИБ блока которому преналежит элемент');
            $table->boolean('visible')->default(1)->comment('Флаг показа элемента');
            $table->integer('sort')->default(1)->comment('Порядок');
            $table->json('content')->nullable()->comment('Хранит значения динамических полей');
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('content_blocks');
        Schema::dropIfExists('content_blocks_items');
    }
}
