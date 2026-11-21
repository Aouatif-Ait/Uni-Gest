<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('NoteEtudiant', function (Blueprint $table) {

            $table->integer('IDETUD');
            $table->integer('IDEXAMEN');
            $table->integer('IDNOTE');

            // Un étudiant : une seule note par examen
            $table->primary(['IDETUD', 'IDEXAMEN']);

            // Une note ne peut être affectée qu'une seule fois
            $table->unique('IDNOTE');

            $table->foreign('IDETUD')
                ->references('IDETUD')
                ->on('Etudiant')
                ->restrictOnDelete();

            $table->foreign('IDEXAMEN')
                ->references('IDEXAMEN')
                ->on('Examen')
                ->restrictOnDelete();

            $table->foreign('IDNOTE')
                ->references('IDNOTE')
                ->on('Note')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('NoteEtudiant');
    }
};
