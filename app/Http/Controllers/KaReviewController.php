<?php

namespace App\Http\Controllers;

use App\Entity\DocumentAttachment;
use App\Entity\FeasibilityTestTeam;
use App\Entity\FormulatorTeam;
use App\Entity\FormulatorTeamMember;
use App\Entity\KaReview;
use App\Entity\Project;
use App\Entity\ProjectMapAttachment;
use App\Entity\ProjectRonaAwal;
use App\Entity\PublicConsultationDoc;
use App\Laravue\Models\User;
use App\Notifications\KaReview as AppKaReview;
use App\Utils\Document;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class KaReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->pdf) {
            return $this->exportPDF($request->idProject, $request->isAndal);
        }

        if($request->attachment) {
            return $this->attachment($request->idProject, $request->isAndal);
        }

        $document_type = $request->documentType;
        $id_project = $request->idProject;

        $review = KaReview::where([['id_project', $id_project],['document_type', $document_type]])->with('project')->orderBy('id', 'desc')->first();

        if($review) {
            $review_penyusun = KaReview::where([['id_project', $id_project],['document_type', $document_type],['status', 'submit-to-pemrakarsa']])
                ->orderBy('id', 'desc')
                ->first();

            $review->setAttribute('formulator_notes', $review_penyusun->notes);
        }

        return $review;
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
        if($request->type == 'penyusun') {
            $review = new KaReview();
            $review->id_project = $request->idProject;
            $review->status = 'submit-to-pemrakarsa';
            $review->notes = $request->notes;
            $review->document_type = $request->documentType;
            $review->save();

            // === NOTIFICATIONS === //
            $project = Project::findOrFail($request->idProject);
            $email = $project->initiator->email;
            $user = User::where('email', $email)->count();

            $document_type = '';
            if($request->documentType == 'ka') {
                $document_type = 'KA';
            } else if($request->documentType == 'andal-rkl-rpl') {
                $document_type = 'ANDAL RKL RPL';
            } else if($request->documentType == 'ukl-upl') {
                $document_type = 'UKL UPL';
            }

            if($user > 0) {
                $user = User::where('email', $email)->first();
                Notification::send([$user], new AppKaReview($review, $document_type));
            }

            // workflow

            // === WORKFLOW === //
            if($document_type == 'KA') {
                if($project->marking == 'amdal.form-ka-drafting') {
                    //$project->workflow_apply('submit-amdal-form-ka');
                    // $project->save();
                } /* else if($project->marking == 'amdal.form-ka-submitted') {
                    $project->workflow_apply('review-amdal-form-ka');
                    $project->save();
                }*/
            } else if($document_type == 'ANDAL RKL RPL') {
                if($project->marking == 'amdal.form-rklrpl-drafting') {
                    $project->workflow_apply('submit-amdal');
                    // $project->workflow_apply('review-amdal-adm');
                    $project->save();
                }
            } else if($document_type == 'UKL UPL') {
                if($project->marking == 'uklupl-mt.matrix-upl') {
                    $project->workflow_apply('submit-uklupl');
                    // $project->workflow_apply('review-uklupl-adm');
                    $project->save();
                } else if($project->marking == 'uklupl-mt.submitted') {
                    // $project->workflow_apply('review-uklupl-adm');
                    // $project->save();
                }
            }

        }

        if($request->type == 'pemrakarsa') {
            $project = Project::findOrFail($request->idProject);

            $review = new KaReview();
            $review->id_project = $request->idProject;
            $review->status = $request->status;
            $review->document_type = $request->documentType;

            if($request->status == 'revisi') {
                $review->notes = $request->notes;
            } else if($request->status == 'submit') {
                if($request->file) {
                    $file = $this->base64ToFile($request->file);
                    $fileName = 'ka-reviews/' . uniqid() . '.' . $file['extension'];
                    Storage::disk('public')->put($fileName, $file['file']);
                    $review->application_letter = $fileName;
                }
            }

            $review->save();

            $document_type = '';
            if($request->documentType == 'ka') {
                $document_type = 'KA';
            } else if($request->documentType == 'andal-rkl-rpl') {
                $document_type = 'ANDAL RKL RPL';
            } else if($request->documentType == 'ukl-upl') {
                $document_type = 'UKL UPL';
            }

            if($request->status == 'revisi') {
                $formulator_team = FormulatorTeam::where('id_project', $request->idProject)->first();
                if($formulator_team) {
                    $ketua = FormulatorTeamMember::where([['id_formulator_team', $formulator_team->id],['position', 'Ketua']])->first();
                    if($ketua) {
                        if($ketua->formulator) {
                            $email = $ketua->formulator->email;
                            $user = User::where('email', $email)->count();
                            if($user > 0) {
                                $user = User::where('email', $email)->first();
                                Notification::send([$user], new AppKaReview($review, $document_type));
                            }
                        }
                    }
                }

                // === WORKFLOW === //
                if($document_type == 'KA') {
                    /*
                    if($project->marking == 'amdal.form-ka-adm-review') {
                        $project->workflow_apply('return-amdal-form-ka-review');
                        $project->save();
                    } else if($project->marking == 'amdal.form-ka-submitted') {
                        $project->workflow_apply('review-amdal-form-ka');
                        $project->workflow_apply('return-amdal-form-ka-review');
                        $project->save();
                    }*/
                } else if($document_type == 'ANDAL RKL RPL') {
                    if($project->marking == 'amdal.form-rklrpl-drafting') {
                        $project->workflow_apply('submit-amdal');
                        $project->workflow_apply('review-amdal-adm');
                        $project->save();
                    }
                } else if($document_type == 'UKL UPL') {
                    if($project->marking == 'uklupl-mt.matrix-upl') {
                        $project->workflow_apply('submit-uklupl');
                        $project->workflow_apply('review-uklupl-adm');
                        $project->workflow_apply('return-uklupl-adm');
                        $project->save();
                    } else if($project->marking == 'uklupl-mt.submitted') {
                        $project->workflow_apply('review-uklupl-adm');
                        $project->workflow_apply('return-uklupl-adm');
                        $project->save();
                    }
                }


            } else {
                $feasibility_test_team = null;

                if(strtolower($project->authority) == 'pusat') {
                    $feasibility_test_team = FeasibilityTestTeam::where('authority', 'Pusat')->with(['member' => function($q) {
                        $q->where('position', 'Kepala Sekretariat');
                        $q->with('lukMember', 'expertBank');
                    }])->first();
                } else if(strtolower($project->authority) == 'provinsi') {
                    $feasibility_test_team = FeasibilityTestTeam::where([['authority', 'Provinsi'], ['id_province_name', $project->auth_province]])->with(['member' => function($q) {
                        $q->where('position', 'Kepala Sekretariat');
                    }])->first();
                } else if(strtolower($project->authority) == 'kabupaten') {
                    $feasibility_test_team = FeasibilityTestTeam::where([['authority', 'Kabupaten/Kota'], ['id_district_name', $project->auth_district]])->with(['member' => function($q) {
                        $q->where('position', 'Kepala Sekretariat');
                    }])->first();
                }

                if($feasibility_test_team) {
                    if($feasibility_test_team->member->first()) {
                        $email = null;
                        if($feasibility_test_team->member->first()->lukMember) {
                            $email = $feasibility_test_team->member->first()->lukMember->email;
                        } else if($feasibility_test_team->member->first()->expertBank) {
                            $email = $feasibility_test_team->member->first()->expertBank->email;
                        }
                        if($email) {
                            $user = User::where('email', $email)->first();
                            if($user) {
                                Notification::send([$user], new AppKaReview($review, $document_type));
                            }
                        }
                    }
                }

                // === WORKFLOW === //
                if($document_type == 'KA') {
                    if($project->marking == 'amdal.form-ka-drafting') {
                        $project->workflow_apply('submit-amdal-form-ka');
                        // $project->workflow_apply('review-amdal-form-ka');
                        $project->save();
                    } /* else if($project->marking == 'amdal.form-ka-submitted') {
                        // $project->workflow_apply('review-amdal-form-ka');
                        // $project->save();
                    }
                    if($project->marking == 'amdal.form-ka-review') {
                        $project->workflow_apply('approve-amdal-form-ka');
                        $project->save();
                    }*/

                } else if($document_type == 'ANDAL RKL RPL') {
                    if($project->marking == 'amdal.form-rklrpl-drafting') {
                        $project->workflow_apply('submit-amdal');
                        $project->workflow_apply('review-amdal-adm');
                        $project->save();
                    }
                } else if($document_type == 'UKL UPL') {
                    if($project->marking == 'uklupl-mt.matrix-upl') {
                        $project->workflow_apply('submit-uklupl');
                        $project->workflow_apply('review-uklupl-adm');
                        $project->save();
                    } else if($project->marking == 'uklupl-mt.submitted') {
                        $project->workflow_apply('review-uklupl-adm');
                        $project->save();
                    }
                }
            }
        }

        return response()->json(['message' => 'Data saved successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function attachment($id_project, $is_andal)
    {
        $project = Project::findOrFail($id_project);
        $peta_lokasi_kegiatan = ProjectMapAttachment::where([['id_project', $id_project],['file_type', 'PDF'],['attachment_type', 'tapak']])
                                                        ->first();
        $peta_batas_wilayah_studi = ProjectMapAttachment::where([['id_project', $id_project],['file_type', 'PDF'],['attachment_type', 'study'],['step','ka']])->first();
        $bagan_alir_pelingkupan = DocumentAttachment::where([['id_project', $id_project], ['type', 'Bagan Alir Pelingkupan KA']])->first();

        // === FILE PUBLIC CONSULTATION DOCS === //
        $public_consultation_docs = PublicConsultationDoc::whereHas('publicConsultation', function($q) use($id_project) {
            $q->where('project_id', $id_project);
        })->get();
        $file_berita_acara_pelaksanaan = ['file' => null, 'file_name' => null];
        $file_berita_acara_penunjukan_wakil_masyarakat = ['file' => null, 'file_name' => null];
        $file_daftar_hadir = ['file' => null, 'file_name' => null];
        $file_pengumuman = ['file' => null, 'file_name' => null];
        $file_undangan = ['file' => null, 'file_name' => null];

        foreach($public_consultation_docs as $doc) {
            $file_array = json_decode($doc->doc_json, true);
            $explode_extension = explode('.', $file_array['original_filename']);
            if($file_array['doc_type'] == 'Berita Acara Pelaksanaan') {
                $file_berita_acara_pelaksanaan = [
                    'file' => Storage::url(str_replace('storage/', '', $file_array['filepath']) ),
                    'file_name' => 'Berita Acara Pelaksanaan.' . $explode_extension[count($explode_extension) - 1] 
                ];
            } else if($file_array['doc_type'] == 'Berita Acara Penunjukan Wakil Masyarakat') {
                $file_berita_acara_penunjukan_wakil_masyarakat = [
                    'file' => Storage::url(str_replace('storage/', '', $file_array['filepath']) ),
                    'file_name' => 'Berita Acara Penunjukan Wakil Masyarakat.' . $explode_extension[count($explode_extension) - 1] 
                ];
            } else if($file_array['doc_type'] == 'Daftar Hadir') {
                $file_daftar_hadir = [
                    'file' => Storage::url(str_replace('storage/', '', $file_array['filepath']) ),
                    'file_name' => 'Daftar Hadir.' . $explode_extension[count($explode_extension) - 1] 
                ];
            } else if($file_array['doc_type'] == 'Pengumuman') {
                $file_pengumuman = [
                    'file' => Storage::url(str_replace('storage/', '', $file_array['filepath']) ),
                    'file_name' => 'Pengumuman.' . $explode_extension[count($explode_extension) - 1] 
                ];
            } else if($file_array['doc_type'] == 'Undangan') {
                $file_undangan = [
                    'file' => Storage::url(str_replace('storage/', '', $file_array['filepath']) ),
                    'file_name' => 'Undangan.' . $explode_extension[count($explode_extension) - 1] 
                ];
            }
        }
        

        // === PDF RONA AWAL === //
        $rona_awal = ProjectRonaAwal::where([['id_project', $id_project],['file', '!=', null],['is_andal', false]])->get();
        $geofisik_kimia = [];
        $biologi = [];
        $sosial_budaya = [];
        $kesehatan_masyakarat = [];
        $lain_lain = [];

        foreach($rona_awal as $rona) {
            switch ($rona->rona_awal->componentType->name) {
                case 'Geofisik Kimia':
                    $geofisik_kimia[] = [
                        'file' => $rona->file,
                        'name' => $rona->rona_awal->name
                    ];
                    break;
                case 'Biologi':
                    $biologi[] = [
                        'file' => $rona->file,
                        'name' => $rona->rona_awal->name
                    ];
                    break;
                case 'Sosial, Ekonomi, dan Budaya':
                    $sosial_budaya[] = [
                        'file' => $rona->file,
                        'name' => $rona->rona_awal->name
                    ];
                    break;
                case 'Kesehatan Masyarakat':
                    $kesehatan_masyakarat[] = [
                        'file' => $rona->file,
                        'name' => $rona->rona_awal->name
                    ];
                    break;
                case 'Lain Lain':
                    $lain_lain[] = [
                        'file' => $rona->file,
                        'name' => $rona->rona_awal->name
                    ];
                    break;
                default:
                    break;
            }
        }

        
        $attachment = [
            [
                'no' => 1,
                'name' => 'Peta Lokasi Kegiatan',
                'file' => $peta_lokasi_kegiatan ? Storage::url('map/' . $peta_lokasi_kegiatan->stored_filename) : null,
            ],
            [
                'no' => 2,
                'name' => 'Bukti Kesesuaian Tata Ruang',
                'file' => $project->pre_agreement_file ?? null,
            ],
            [
                'no' => 3,
                'name' => 'Izin Persetujuan Awal',
                'file' => $project->pre_agreement_file,
            ],
            [
                'no' => 4,
                'name' => 'Bagan Alir Pelingkupan',
                'file' => $bagan_alir_pelingkupan ? $bagan_alir_pelingkupan->attachment : null,
            ],
            [
                'no' => 5,
                'name' => 'Hasil Pelibatan Masyarakat',
                'file' => 'undefined',
            ],
            [
                'no' => null,
                'name' => 'a. Saran, Pendapat dan Tanggapan Masyarakat',
                'file' => true,
                'generate' => true,
            ],
            [
                'no' => null,
                'name' => 'b. Konsultasi Publik',
                'file' => true,
                'generate' => true,
            ],
            [
                'no' => null,
                'name' => 'c. Berita Acara Pelaksanaan',
                'file' => $file_berita_acara_pelaksanaan['file'],
                'file_name' => $file_berita_acara_pelaksanaan['file_name'],
            ],
            [
                'no' => null,
                'name' => 'd. Berita Acara Penunjukan Wakil Masyarakat',
                'file' => $file_berita_acara_penunjukan_wakil_masyarakat['file'],
                'file_name' => $file_berita_acara_penunjukan_wakil_masyarakat['file_name'],
            ],
            [
                'no' => null,
                'name' => 'e. Daftar Hadir',
                'file' => $file_daftar_hadir['file'],
                'file_name' => $file_daftar_hadir['file_name'],
            ],
            [
                'no' => null,
                'name' => 'f. Pengumuman',
                'file' => $file_pengumuman['file'],
                'file_name' => $file_pengumuman['file_name'],
            ],
            [
                'no' => null,
                'name' => 'g. Undangan',
                'file' => $file_undangan['file'],
                'file_name' => $file_undangan['file_name'],
            ],
            [
                'no' => 6,
                'name' => 'Rona Lingkungan Awal',
                'file' => 'undefined',
            ],
        ];

        $no_rona = 'a';
        if(count($geofisik_kimia) > 0) {
            $attachment = $this->getRonaFileArray($attachment, $geofisik_kimia, 'Geofisik Kimia', $no_rona);
            $no_rona++;
        }
        if(count($biologi) > 0) {
            $attachment = $this->getRonaFileArray($attachment, $biologi, 'Biologi', $no_rona);
            $no_rona++;
        }
        if(count($sosial_budaya) > 0) {
            $attachment = $this->getRonaFileArray($attachment, $sosial_budaya, 'Sosial, Ekonomi, dan Budaya', $no_rona);
            $no_rona++;
        }
        if(count($kesehatan_masyakarat) > 0) {
            $attachment = $this->getRonaFileArray($attachment, $kesehatan_masyakarat, 'Kesehatan Masyarakat', $no_rona);
            $no_rona++;
        }
        if(count($lain_lain) > 0) {
            $attachment = $this->getRonaFileArray($attachment, $lain_lain, 'Lain-Lain', $no_rona);
        }

        array_push($attachment,  [
            'no' => 7,
            'name' => 'Data Pendukung Deskripsi Kegiatan',
            'file' => null,
        ],
        [
            'no' => 8,
            'name' => 'Peta Batas Wilayah Studi',
            'file' => $peta_batas_wilayah_studi ? Storage::url('map/' . $peta_batas_wilayah_studi->stored_filename) : null,
        ]);

        return response()->json($attachment);
    }

    private function base64ToFile($file_64)
    {
        $extension = explode('/', explode(':', substr($file_64, 0, strpos($file_64, ';')))[1])[1];   // .jpg .png .pdf

        $replace = substr($file_64, 0, strpos($file_64, ',')+1);

        // find substring fro replace here eg: data:image/png;base64,

        $file = str_replace($replace, '', $file_64);

        $file = str_replace(' ', '+', $file);

        return [
            'extension' => $extension,
            'file' => base64_decode($file)
        ];
    }

    private function getRonaFileArray($attachment, $data, $name, $no) {
        $attachment[] = [
            'no' => null,
            'name' => $no . '. ' . $name,
            'file' => 'undefined'
        ];
        for($i = 0; $i < count($data); $i++) {
            $attachment[] = [
                'no' => null,
                'name' => $data[$i]['name'],
                'file' => $data[$i]['file']
            ];
        }

        return $attachment;
    }

    private function exportPDF($id_project, $is_andal)
    {
        $document_type = $is_andal ? 'Formulir KA Andal' : 'Formulir KA';
        $document_attachment = DocumentAttachment::where([['id_project', $id_project],['type', $document_type]])->first();
        $downloadUri = url(Storage::url(str_replace('/storage/', '', $document_attachment->attachment)));
        $key = Document::GenerateRevisionId($downloadUri);
        $convertedUri;
        $download_url = Document::GetConvertedUri($downloadUri, 'docx', 'pdf', $key, FALSE, $convertedUri);
        return $convertedUri;
    }
}
