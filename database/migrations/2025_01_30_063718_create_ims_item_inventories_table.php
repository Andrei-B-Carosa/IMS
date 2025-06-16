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
        Schema::create('ims_item_inventories', function (Blueprint $table) {
            $table->id();
            $table->integer('item_brand_id')->nullable();
            $table->integer('item_type_id')->nullable();
            $table->string('name')->nullable();
            $table->string('tag_number')->nullable();
            $table->text('description')->nullable();
            $table->text('serial_number')->nullable();

            $table->float('price')->nullable();

            $table->dateTime('received_at')->nullable();
            $table->integer('received_by')->nullable();

            $table->integer('supplier_id')->nullable();
            $table->date('warranty_end_at')->nullable();
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
        Schema::dropIfExists('ims_item_inventories');
    }
};
