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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignee_id');

            $table->string(column: 'name');
            $table->string('description');
            $table->date('dueDate',);
            $table->string('status');
            $table->string(column: 'workflow');
            $table->timestamps();

            $table->index('name');
            $table->index('dueDate');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
