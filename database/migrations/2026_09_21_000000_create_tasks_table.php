<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Creates the tasks table with the fields required by the project spec
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id(); // auto-incrementing task ID
            $table->string('task_name');            // name of the task
            $table->text('description')->nullable(); // task details
            $table->enum('status', ['Pending', 'Completed'])->default('Pending'); // task status
            $table->date('due_date')->nullable();    // task deadline
            $table->timestamps(); // created_at / updated_at, useful for sorting/debugging
        });
    }

    // Drops the tasks table when rolling back
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
