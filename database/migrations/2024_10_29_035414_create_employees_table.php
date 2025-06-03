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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('emp_image')->nullable();
            $table->string('emp_no')->nullable();

            $table->string('fname',200)->nullable();
            $table->string('mname',200)->nullable();
            $table->string('lname',200)->nullable();
            $table->string('ext',50)->nullable();
            $table->string('title',50)->nullable();
            $table->string('p_email')->nullable();
            $table->date('birthday')->nullable();
            $table->string('birthplace')->nullable();
            $table->tinyInteger('sex')->nullable();
            $table->tinyInteger('civil_status')->nullable();
            $table->string('height',50)->nullable();
            $table->string('weight',50)->nullable();
            $table->string('blood_type')->nullable();
            $table->string('sss',50)->nullable();
            $table->string('pagibig',50)->nullable();
            $table->string('philhealth',50)->nullable();
            $table->string('gsis',50)->nullable();
            $table->string('tin',50)->nullable();
            $table->string('citizenship')->nullable();
            $table->text('dual_citizenship')->nullable();
            $table->longText('current_address')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('telephone_number')->nullable();

            $table->string('spouse_fname')->nullable();
            $table->string('spouse_lname')->nullable();
            $table->string('spouse_mname')->nullable();
            $table->string('spouse_occupation')->nullable();
            $table->string('spouse_employer')->nullable();
            $table->string('spouse_business_address')->nullable();

            $table->string('father_fname')->nullable();
            $table->string('father_lname')->nullable();
            $table->string('father_mname')->nullable();
            $table->string('father_ext',50)->nullable();

            $table->string('mother_fname')->nullable();
            $table->string('mother_lname')->nullable();
            $table->string('mother_mname')->nullable();

            $table->tinyInteger('is_active')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate()->nullable();

            $table->tinyInteger('is_deleted')->nullable();
            $table->tinyInteger('deleted_by')->nullable();
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
