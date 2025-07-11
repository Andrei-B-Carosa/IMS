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
        Schema::create('ims_accountability_issuance_logs', function (Blueprint $table) {
            $table->id();

            $table->integer('accountability_id');
            $table->integer('emp_id');
            $table->integer('activity_type');
            $table->string('activity_log');

            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();

            $table->timestamp('created_at')->useCurrent();
            $table->unsignedBigInteger('created_by')->nullable();

            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ims_accountability_issuance_logs');
    }
};
