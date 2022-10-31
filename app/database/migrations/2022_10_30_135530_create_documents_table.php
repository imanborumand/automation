<?php

use App\Enums\Document\Priority;
use App\Enums\Document\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150);


            $table->enum('priority', array_column( Priority::cases(), 'value'))
                  ->default( Priority::NORMAL->value)
                  ->index();

            $table->enum('status', array_column( Status::cases(), 'value'))
                ->default( Status::OPEN->value)
                ->index();

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
        Schema::dropIfExists('documents');
    }
};
