<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublicConsultationDocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('public_consultation_docs', function (Blueprint $table) {
            $table->id();
            $table->integer('public_consultation_id');
            $table->json('doc_json');
            // doc_json:
            // - doc_type: Foto Dokumentasi, Berita Acara, Daftar Hadir, Pengumuman
            // - original_filename
            // - file_extension
            // - filepath
            // - uploaded_by
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('public_consultation_docs');
    }
}
