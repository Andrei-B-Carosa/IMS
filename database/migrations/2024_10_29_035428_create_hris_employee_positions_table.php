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
        Schema::create('hris_employee_positions', function (Blueprint $table) {
            $table->id();
            $table->integer('emp_id');
            $table->integer('position_id')->nullable();
            $table->integer('department_id');
            $table->integer('company_id')->nullable();
            $table->integer('company_location_id')->nullable();
            $table->integer('section_id')->nullable();
            $table->integer('classification_id')->nullable();
            $table->integer('employment_id')->nullable();
            $table->date('date_employed')->nullable();
            $table->tinyInteger('is_active')->nullable();
            $table->tinyInteger('work_status')->nullable();

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
        Schema::dropIfExists('hris_employee_positions');
    }
};
