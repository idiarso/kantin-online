<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->constrained('users')->onDelete('set null');
            $table->decimal('daily_limit', 10, 2)->nullable();
            $table->string('qr_code')->nullable()->unique();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('student_id')->nullable()->unique(); // NIS
            $table->string('employee_id')->nullable()->unique(); // NIP
            $table->string('address')->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn([
                'parent_id',
                'daily_limit',
                'qr_code',
                'status',
                'student_id',
                'employee_id',
                'address',
                'birth_date',
                'gender'
            ]);
        });
    }
}; 