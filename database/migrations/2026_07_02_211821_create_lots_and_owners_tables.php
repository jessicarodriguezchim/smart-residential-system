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
        Schema::create('owners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('phone', 20)->nullable();
            $table->string('email', 255)->unique();
            $table->enum('status', ['activo', 'inactivo'])->default('activo');
            $table->timestamps();
        });

        Schema::create('lots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->nullable()->constrained('owners')->onDelete('set null');
            $table->string('number', 50)->unique();
            $table->string('street', 150);
            $table->decimal('surface_area', 8, 2)->nullable();
            $table->enum('status', ['disponible', 'vendido', 'apartado'])->default('disponible');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('status');
        });

        Schema::create('owner_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('owners')->onDelete('cascade');
            $table->string('document_type', 100);
            $table->string('file_path', 255);
            $table->string('file_name', 255);
            $table->integer('file_size')->unsigned();
            $table->string('mime_type', 100);
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('owner_documents');
        Schema::dropIfExists('lots');
        Schema::dropIfExists('owners');
    }
};
