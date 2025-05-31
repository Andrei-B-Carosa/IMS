<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ims_material_issuances', function (Blueprint $table) {
            $table->id();

            $table->integer('form_no')->nullable();
            $table->string('mrs_no')->nullable();

            $table->dateTime('issued_at')->nullable();
            $table->integer('issued_by')->nullable();

            $table->integer('received_by')->nullable();
            $table->binary('signature')->nullable();

            $table->text('remarks')->nullable();
            $table->tinyInteger('status')->nullable();

            $table->tinyInteger('is_deleted')->nullable();
            $table->tinyInteger('deleted_by')->nullable();
            $table->dateTime('deleted_at')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ims_material_issuances');
    }
};
