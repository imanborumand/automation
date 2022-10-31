<?php

use App\Enums\User\Roles;
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
        Schema::create('document_user', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')->index();
            $table->foreign('user_id')
                  ->on('users')
                  ->references('id')
                  ->cascadeOnDelete();

            $table->enum('role_type', array_column(Roles::cases(), 'value'))
                  ->default(Roles::REVIEWER->value);

            $table->unsignedBigInteger('document_id')->index();
            $table->foreign('document_id')
                  ->on('documents')
                  ->references('id')
                  ->cascadeOnDelete();

            $table->timestamp('appointment_end_time')->index();

            $table->boolean('refuse_of_review')
                  ->default(false)
                  ->index()
                  ->comment('This flag will be activated if the user opts out of checking the document');

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
        Schema::dropIfExists('document_user');
    }
};
