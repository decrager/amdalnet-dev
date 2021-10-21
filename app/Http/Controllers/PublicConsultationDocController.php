<?php

namespace App\Http\Controllers;

use App\Entity\PublicConsultationDoc;
use Illuminate\Http\Request;

class PublicConsultationDocController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // doc_json:
        // - doc_type: Foto Dokumentasi, Berita Acara, Daftar Hadir, Pengumuman
        // - original_filename
        // - file_extension
        // - filepath
        // - uploaded_by
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Entity\PublicConsultationDoc  $publicConsultationDoc
     * @return \Illuminate\Http\Response
     */
    public function show(PublicConsultationDoc $publicConsultationDoc)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity\PublicConsultationDoc  $publicConsultationDoc
     * @return \Illuminate\Http\Response
     */
    public function edit(PublicConsultationDoc $publicConsultationDoc)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entity\PublicConsultationDoc  $publicConsultationDoc
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PublicConsultationDoc $publicConsultationDoc)
    {
        // doc_json:
        // - doc_type: Foto Dokumentasi, Berita Acara, Daftar Hadir, Pengumuman
        // - original_filename
        // - file_extension
        // - filepath
        // - uploaded_by
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity\PublicConsultationDoc  $publicConsultationDoc
     * @return \Illuminate\Http\Response
     */
    public function destroy(PublicConsultationDoc $publicConsultationDoc)
    {
        //
    }
}
