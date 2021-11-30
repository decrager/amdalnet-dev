<?php

namespace App\Http\Controllers;

use App\Entity\PublicConsultation;
use App\Entity\PublicConsultationDoc;
use App\Http\Resources\PublicConsultationResource;
use DateInterval;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PublicConsultationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return PublicConsultationResource::collection(PublicConsultation::all());
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
        $validator = Validator::make(
            $request->all(),
            [
                'announcement_id' => 'required',
                'project_id' => 'required',
                'event_date' => 'required',
                'participant' => 'required',
                'location' => 'required',
                'address' => 'required',
                'positive_feedback_summary' => 'required',
                'negative_feedback_summary' => 'required',
                'doc_files' => 'required',
                'doc_metadatas' => 'required',
                'doc_berita_acara' => 'required',
                'doc_berita_acara_2' => 'required',
                'doc_daftar_hadir' => 'required',
                'doc_pengumuman' => 'required',
                'doc_undangan' => 'required',
            ]
        );
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $validated = $request->all();
            try {
                $datetime = DateTime::createFromFormat('Y-m-d\TH:i:s+', $validated['event_date']);
                $datetime->add(new DateInterval('PT7H'));
                $event_date = $datetime->format('Y-m-d\TH:i:s');
                $validated['event_date'] = $event_date;
            } catch (Exception $e){
                return response()->json(['errors' => 'Invalid event_data'], 400);
            }
            DB::beginTransaction();
            $parent = PublicConsultation::create([
                'announcement_id' => $validated['announcement_id'],
                'project_id' => $validated['project_id'],
                'event_date' => $validated['event_date'],
                'participant' => $validated['participant'],
                'location' => $validated['location'],
                'address' => $validated['address'],
                'positive_feedback_summary' => $validated['positive_feedback_summary'],
                'negative_feedback_summary' => $validated['negative_feedback_summary'],
            ]);

            $metadatas = null;
            $doc_files = null;
            try {
                //upload docs
                $metadatas = json_decode($validated['doc_metadatas']);
                $doc_files = json_decode($validated['doc_files']);
            } catch (Exception $e){
                DB::rollBack();
                return response()->json(['errors' => 'Invalid doc metadata'], 400);
            }
            if (count($metadatas) != count($doc_files)){
                DB::rollBack();
                return response()->json(['errors' => 'Metadata mismatch'], 400);
            }
            $doc_inserted = 0;
            foreach ($doc_files as $i => $doc_file){
                //upload file
                $metadata = $metadatas[$i];
                $file_extension = '';
                $filepath = '';
                try {
                    if (property_exists($doc_file, 'dataURL')){
                        // Foto Dokumentasi (base64)
                        $data = $doc_file->dataURL;
                        list($type, $data) = explode(';', $data);
                        list(,$extension) = explode('/',$type);
                        list(,$data) = explode(',', $data);
                        $decoded_data = base64_decode($data);
                        // save img
                        $img_filepath = 'docs/pubcons/img/' . uniqid() . '.' . $extension;
                        Storage::put($img_filepath, $decoded_data);
                        $filepath = 'storage/' . $img_filepath;
                        $file_extension = $extension;
                    } else {
                        // Dokumen Lampiran
                        $file_field_name = sprintf('doc_%s', 
                                            str_replace(' ', '_', strtolower($metadata->doc_type)));
                        $file = $request->file($file_field_name);
                        $filename = uniqid() . '.' . $file->extension();
                        // create subfolder: ba/dh/p/u
                        $exp = explode(' ', $metadata->doc_type);
                        $f = $exp[0][0];
                        $r = count($exp) == 2 ? $exp[1][0] : '';
                        $subfolder = strtolower(sprintf('%s%s', $f, $r));
                        $folder = sprintf('docs/pubcons/%s', $subfolder);
                        // save file
                        $file->storePubliclyAs($folder, $filename);
                        $filepath = sprintf('storage/%s/%s', $folder, $filename);
                        $file_extension = $file->extension();
                    }
                } catch (Exception $e){
                    DB::rollBack();
                    return response()->json(['errors' => 'Error uploading file'], 500);
                }
                $pubconsDoc = PublicConsultationDoc::create([
                    'public_consultation_id' => $parent->id,
                    'doc_json' => json_encode([
                        'doc_type' => $metadata->doc_type,
                        'original_filename' => $metadata->filename,
                        'file_extension' => $file_extension,
                        'filepath' => $filepath,
                        'uploaded_by' => $metadata->uploaded_by,
                    ]),
                ]);
                if ($pubconsDoc){
                    $doc_inserted++;
                }
            }
            if ($parent && ($doc_inserted == count($metadatas))){
                DB::commit();
            } else {
                DB::rollBack();
            }
            $created = PublicConsultation::with('docs')->get()->where('id', '=', $parent->id)->first();
            return new PublicConsultationResource($created);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Entity\PublicConsultation  $publicConsultation
     * @return \Illuminate\Http\Response
     */
    public function show(PublicConsultation $publicConsultation)
    {
        return PublicConsultation::with('docs')->get()->where('id', '=', $publicConsultation->id)->first();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity\PublicConsultation  $publicConsultation
     * @return \Illuminate\Http\Response
     */
    public function edit(PublicConsultation $publicConsultation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entity\PublicConsultation  $publicConsultation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PublicConsultation $publicConsultation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity\PublicConsultation  $publicConsultation
     * @return \Illuminate\Http\Response
     */
    public function destroy(PublicConsultation $publicConsultation)
    {
        //
    }
}
