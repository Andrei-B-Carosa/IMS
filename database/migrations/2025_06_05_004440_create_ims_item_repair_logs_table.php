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
        Schema::create('ims_item_repair_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('item_inventory_id')->nullable();
            $table->integer('issued_by')->nullable();

            $table->date('start_at');
            $table->date('end_at')->nullable();

            $table->integer('repair_type')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->tinyInteger('item_inventory_status')->nullable();
            $table->text('description')->nullable();

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
        Schema::dropIfExists('ims_item_repair_logs');
    }
};
