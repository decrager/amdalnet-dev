<?php

namespace App\Http\Controllers;

use App\Entity\Component;
use App\Entity\ComponentType;
use App\Entity\DocumentAttachment;
use App\Entity\EnvManagePlan;
use App\Entity\EnvMonitorPlan;
use App\Entity\ExpertBankTeamMember;
use App\Entity\ImpactIdentification;
use App\Entity\ImpactStudy;
use App\Entity\MeetingReport;
use App\Entity\Project;
use App\Entity\ProjectFilter;
use App\Entity\ProjectStage;
use App\Utils\Document;
use App\Utils\Html;
use App\Utils\ListRender;
use App\Utils\TemplateProcessor;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\Element\Table;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class ExportDocument extends Controller
{
    public function index(Request $request) {
        if($request->version) {
            $data = array();
            for($i= 0; $i < 100; $i++) {
                $docVersi = DocumentAttachment::select('versi')->where('id_project', $request->id)->where('versi', $i)->orderBy('created_at', 'desc')->first();
                if($docVersi === null) {
                    continue;
                } else {
                    $data[] = $docVersi;
                }
            };

            return $data;
        }
        if($request->changeVersi) {
            Carbon::setLocale('id');
            $project = Project::with('listSubProject')->findOrFail($request->id_project);
            $document_attachment = DocumentAttachment::where([['id_project', $request->id_project],['type', 'Dokumen UKL UPL'], ['versi', $request->versi]])->orderBy('created_at', 'desc')->first();
            if($document_attachment) {
                $pdf_url = $this->docxToPdf($document_attachment->attachment);
                return [
                    'file_name' => 'ukl-upl-' . strtolower(str_replace('/', '-', $project->project_title)) . '.docx',
                    'project_title' => strtolower(str_replace('/', '-', $project->project_title)),
                    'pdf_url' => $pdf_url,
                    'docx_url' => $document_attachment->attachment,
                    'create_time' => $document_attachment->created_at->toTimeString(),
                    'versi_doc' => $document_attachment->versi,
                ];
            }
        }
    }

    public function KADocx($id)
    {
        $getInformation = DB::table('projects')
            ->select(
                'projects.project_title',
                'projects.description',
                'projects.location_desc',
                'initiators.name',
                'initiators.pic',
                'initiators.email',
                'initiators.phone',
                'initiators.address',
                'initiators.user_type'
            )
            ->leftJoin('initiators', 'initiators.id', '=', 'projects.id_applicant')
            ->where('projects.id', '=', $id)
            ->first();

        $praKonstruksi = DB::table('sub_project_components')
            ->select(
                'project_stages.name as project_stages_name',
                'components.name as component_name'
            )
            ->selectRaw('ROW_NUMBER () OVER (ORDER BY sub_project_components.id) as number')
            ->join('components', 'components.id', '=', 'sub_project_components.id_component')
            ->join('project_stages', 'project_stages.id', '=', 'components.id_project_stage')
            ->join('sub_projects', 'sub_project_components.id_sub_project', '=', 'sub_projects.id')
            ->where('sub_projects.id_project', '=', $id)
            ->where('project_stages.name', '=', 'Pra Konstruksi')
            ->get();

        $konstruksi = DB::table('sub_project_components')
            ->select(
                'project_stages.name as project_stages_name',
                'components.name as component_name'
            )
            ->selectRaw('ROW_NUMBER () OVER (ORDER BY sub_project_components.id) as number')
            ->join('components', 'components.id', '=', 'sub_project_components.id_component')
            ->join('project_stages', 'project_stages.id', '=', 'components.id_project_stage')
            ->join('sub_projects', 'sub_project_components.id_sub_project', '=', 'sub_projects.id')
            ->where('sub_projects.id_project', '=', $id)
            ->where('project_stages.name', '=', 'Konstruksi')
            ->get();

        $operasi = DB::table('sub_project_components')
            ->select(
                'project_stages.name as project_stages_name',
                'components.name as component_name'
            )
            ->selectRaw('ROW_NUMBER () OVER (ORDER BY sub_project_components.id) as number')
            ->join('components', 'components.id', '=', 'sub_project_components.id_component')
            ->join('project_stages', 'project_stages.id', '=', 'components.id_project_stage')
            ->join('sub_projects', 'sub_project_components.id_sub_project', '=', 'sub_projects.id')
            ->where('sub_projects.id_project', '=', $id)
            ->where('project_stages.name', '=', 'Operasi')
            ->get();

        $pascaOperasi = DB::table('sub_project_components')
            ->select(
                'project_stages.name as project_stages_name',
                'components.name as component_name'
            )
            ->selectRaw('ROW_NUMBER () OVER (ORDER BY sub_project_components.id) as number')
            ->join('components', 'components.id', '=', 'sub_project_components.id_component')
            ->join('project_stages', 'project_stages.id', '=', 'components.id_project_stage')
            ->join('sub_projects', 'sub_project_components.id_sub_project', '=', 'sub_projects.id')
            ->where('sub_projects.id_project', '=', $id)
            ->where('project_stages.name', '=', 'Pasca Operasi')
            ->get();

        $praKonstruksiDetail = DB::table('sub_project_components')
            ->select(
                'impact_identifications.initial_study_plan',
                'potential_impact_evaluations.text',
                'rona_awal.name as rona_awal_name',
                'impact_identifications.study_location',
                'impact_identifications.study_length_month',
                'impact_identifications.study_length_year',
                'change_types.name as change_type_name'
            )
            ->join('components', 'components.id', '=', 'sub_project_components.id_component')
            ->join('project_stages', 'project_stages.id', '=', 'components.id_project_stage')
            ->join('sub_projects', 'sub_project_components.id_sub_project', '=', 'sub_projects.id')
            ->leftJoin('impact_identifications', 'impact_identifications.id_sub_project_component', '=', 'sub_project_components.id')
            ->join('sub_project_rona_awals', 'sub_project_rona_awals.id', '=', 'impact_identifications.id_sub_project_rona_awal')
            ->join('rona_awal', 'rona_awal.id', '=', 'sub_project_rona_awals.id_rona_awal')
            ->join('change_types', 'change_types.id', '=', 'impact_identifications.id_change_type')
            ->join('potential_impact_evaluations', 'potential_impact_evaluations.id_impact_identification', '=', 'impact_identifications.id')
            ->where('sub_projects.id_project', '=', $id)
            ->where('project_stages.name', '=', 'Pra Konstruksi')
            ->get();

        $konstruksiDetail = DB::table('sub_project_components')
            ->select(
                'impact_identifications.initial_study_plan',
                'rona_awal.name as rona_awal_name',
                'potential_impact_evaluations.text',
                'impact_identifications.study_location',
                'impact_identifications.study_length_month',
                'impact_identifications.study_length_year',
                'change_types.name as change_type_name'
            )
            ->join('components', 'components.id', '=', 'sub_project_components.id_component')
            ->join('project_stages', 'project_stages.id', '=', 'components.id_project_stage')
            ->join('sub_projects', 'sub_project_components.id_sub_project', '=', 'sub_projects.id')
            ->leftJoin('impact_identifications', 'impact_identifications.id_sub_project_component', '=', 'sub_project_components.id')
            ->leftJoin('potential_impact_evaluations', 'potential_impact_evaluations.id_impact_identification', '=', 'impact_identifications.id')
            ->join('sub_project_rona_awals', 'sub_project_rona_awals.id', '=', 'impact_identifications.id_sub_project_rona_awal')
            ->join('rona_awal', 'rona_awal.id', '=', 'sub_project_rona_awals.id_rona_awal')
            ->join('change_types', 'change_types.id', '=', 'impact_identifications.id_change_type')
            ->where('sub_projects.id_project', '=', $id)
            ->where('project_stages.name', '=', 'Konstruksi')
            ->get();

        $operasiDetail = DB::table('sub_project_components')
            ->select(
                'impact_identifications.initial_study_plan',
                'rona_awal.name as rona_awal_name',
                'potential_impact_evaluations.text',
                'impact_identifications.study_location',
                'impact_identifications.study_length_month',
                'impact_identifications.study_length_year',
                'change_types.name as change_type_name'
            )
            ->join('components', 'components.id', '=', 'sub_project_components.id_component')
            ->join('project_stages', 'project_stages.id', '=', 'components.id_project_stage')
            ->join('sub_projects', 'sub_project_components.id_sub_project', '=', 'sub_projects.id')
            ->leftJoin('impact_identifications', 'impact_identifications.id_sub_project_component', '=', 'sub_project_components.id')
            ->leftJoin('potential_impact_evaluations', 'potential_impact_evaluations.id_impact_identification', '=', 'impact_identifications.id')
            ->join('sub_project_rona_awals', 'sub_project_rona_awals.id', '=', 'impact_identifications.id_sub_project_rona_awal')
            ->join('rona_awal', 'rona_awal.id', '=', 'sub_project_rona_awals.id_rona_awal')
            ->join('change_types', 'change_types.id', '=', 'impact_identifications.id_change_type')
            ->where('sub_projects.id_project', '=', $id)
            ->where('project_stages.name', '=', 'Operasi')
            ->get();

        $pascaOperasiDetail = DB::table('sub_project_components')
            ->select(
                'impact_identifications.initial_study_plan',
                'rona_awal.name as rona_awal_name',
                'potential_impact_evaluations.text',
                'impact_identifications.study_location',
                'impact_identifications.study_length_month',
                'impact_identifications.study_length_year',
                'change_types.name as change_type_name'
            )
            ->join('components', 'components.id', '=', 'sub_project_components.id_component')
            ->join('project_stages', 'project_stages.id', '=', 'components.id_project_stage')
            ->join('sub_projects', 'sub_project_components.id_sub_project', '=', 'sub_projects.id')
            ->leftJoin('impact_identifications', 'impact_identifications.id_sub_project_component', '=', 'sub_project_components.id')
            ->leftJoin('potential_impact_evaluations', 'potential_impact_evaluations.id_impact_identification', '=', 'impact_identifications.id')
            ->join('sub_project_rona_awals', 'sub_project_rona_awals.id', '=', 'impact_identifications.id_sub_project_rona_awal')
            ->join('rona_awal', 'rona_awal.id', '=', 'sub_project_rona_awals.id_rona_awal')
            ->join('change_types', 'change_types.id', '=', 'impact_identifications.id_change_type')
            ->where('sub_projects.id_project', '=', $id)
            ->where('project_stages.name', '=', 'Pasca Operasi')
            ->get();

        $getMetodeStudi = DB::table('impact_studies')
            ->select(
                'impact_studies.forecast_method',
                'impact_studies.required_information',
                'impact_studies.data_gathering_method',
                'impact_studies.analysis_method',
                'impact_studies.evaluation_method',
                'potential_impact_evaluations.text',
                'impact_identifications.is_hypothetical_significant'
            )
            ->selectRaw('ROW_NUMBER () OVER (ORDER BY impact_studies.id) as number')
            ->leftJoin('impact_identifications', 'impact_studies.id_impact_identification', '=', 'impact_identifications.id')
            ->leftJoin('potential_impact_evaluations', 'potential_impact_evaluations.id_impact_identification', '=', 'impact_identifications.id')
            ->leftJoin('projects', 'projects.id', '=', 'impact_identifications.id_project')
            ->where('projects.id', '=', $id)
            ->where('impact_identifications.is_hypothetical_significant', '=', 'true')
            ->get();

        return response()->json([
            'data' => $getInformation,
            'metode_studi' => $getMetodeStudi,
            'pra_konstruksi' => $praKonstruksi,
            'pra_konstruksi_detail' => $praKonstruksiDetail,
            'konstruksi' => $konstruksi,
            'konstruksi_detail' => $konstruksiDetail,
            'operasi' => $operasi,
            'operasi_detail' => $operasiDetail,
            'pasca_operasi' => $pascaOperasi,
            'pasca_operasi_detail' => $pascaOperasiDetail
        ]);
    }

    public function ExportKAPdf(Request $request)
    {
        $getProject = DocumentAttachment::where('id_project', '=', $request->id_project)->where('type', '=', 'Formulir KA')->first();

        $domPdfPath = base_path('vendor/dompdf/dompdf');
        Settings::setPdfRendererPath($domPdfPath);
        Settings::setPdfRendererName('DomPDF');

        //Load word file
        // $Content = IOFactory::load(storage_path('app/public/formulir/' . $getProject->attachment));
        $tmpName = tempnam(sys_get_temp_dir(),'');
        $tmpFile = Storage::disk('public')->get('formulir/' . $getProject->attachment);
        file_put_contents($tmpName, $tmpFile);
        $Content = IOFactory::load($tmpName);

        //Save it into PDF
        $getName = explode('.', $getProject->attachment);
        $PDFWriter = IOFactory::createWriter($Content, 'PDF');

        unlink($tmpName);

        // $PDFWriter->save(storage_path('app/public/formulir/' . $getName[0]) . '.pdf');
        $tmpName = tempnam(sys_get_temp_dir(),'');
        $PDFWriter->save($tmpName);
        Storage::disk('public')->put('formulir/' . $getName[0] . '.pdf', file_get_contents($tmpName));
        unlink($tmpName);

        // return response()->download(storage_path('app/public/formulir/' . $getName[0] . '.pdf'))->deleteFileAfterSend(false);
        return redirect()->away(Storage::disk('public')->temporaryUrl('formulir/' . $getName[0] . '.pdf', now()->addMinutes(env('TEMPORARY_URL_TIMEOUT'))));
    }

    public function ExportUklUpl($id)
    {
        $getData = DB::table('announcements')
            ->select('projects.kbli', 'projects.project_title', 'initiators.name as initiators_name', 'initiators.pic', 'initiators.email', 'initiators.phone', 'initiators.address', 'project_fields.name as project_fields_name', 'provinces.name as prov_name', 'districts.name as dis_name', 'announcements.project_scale as scale')
            ->leftJoin('projects', 'projects.id', '=', 'announcements.id')
            ->leftJoin('provinces', 'provinces.id', '=', 'projects.id_prov')
            ->leftJoin('districts', 'districts.id', '=', 'projects.id_district')
            ->leftJoin('initiators', 'initiators.id', '=', 'projects.id_applicant')
            ->leftJoin('project_fields', 'project_fields.id', '=', 'projects.field')
            ->where('projects.risk_level', '=', 'MR')
            ->where('announcements.id', '=', $id)
            ->first();

        $getSop = DB::table('projects')
            ->selectRaw('DISTINCT(P.ID)')
            ->select(
                'project_stages.name as stages',
                'components.name as component_name',
                'components.id_project_stage',
                'sops.id_component',
                'sops.mgmt_form',
                'sops.monitoring_form',
                'sops.monitoring_time',
                'sops.monitoring_freq',
                'sops.monitoring_date_field',
                'sops.name as sop_name',
                'sops.impact',
                'sops.other_impact',
                'sops.monitoring_period',
                'sops.impact_quantity'
            )
            ->leftJoin("kbli_clusters", function ($join) {
                $join->on('list_kbli', 'LIKE', DB::raw("'%''' || projects.kbli || '''%'"));
            })
            ->leftJoin('sop_kbli_clusters', 'kbli_clusters.id', '=', 'sop_kbli_clusters.id_kbli_cluster')
            ->leftJoin('sops', 'sops.id', '=', 'sop_kbli_clusters.id_sop')
            ->leftJoin('components', 'sops.id_component', '=', 'components.id')
            ->leftJoin('project_stages', 'components.id_project_stage', '=', 'project_stages.id')
            ->where('projects.risk_level', '=', 'MR')
            ->where('projects.id', '=', $id)
            ->orderBy('sops.code')
            ->get();

        $templateProcessor = new TemplateProcessor(storage_path('app/public/template/template_ukl-upl.docx'));

        $templateProcessor->setValue('project_title', $getData->project_title);
        $templateProcessor->setValue('kbli', $getData->kbli);
        $templateProcessor->setValue('initiators_name', $getData->initiators_name);
        $templateProcessor->setValue('address', $getData->address);
        $templateProcessor->setValue('dis_name', $getData->dis_name);
        $templateProcessor->setValue('prov_name', $getData->prov_name);
        $templateProcessor->setValue('pic', $getData->pic);
        $templateProcessor->setValue('scale', $getData->scale);
        $templateProcessor->setValue('phone', $getData->phone);

        $getComponent = DB::table('projects')
            ->select(
                'components.name as component_name',
                'components.id_project_stage',
                'components.id'
            )
            ->leftJoin("kbli_clusters", function ($join) {
                $join->on('list_kbli', 'LIKE', DB::raw("'%''' || projects.kbli || '''%'"));
            })
            ->leftJoin('sop_kbli_clusters', 'kbli_clusters.id', '=', 'sop_kbli_clusters.id_kbli_cluster')
            ->leftJoin('sops', 'sops.id', '=', 'sop_kbli_clusters.id_sop')
            ->leftJoin('components', 'sops.id_component', '=', 'components.id')
            ->leftJoin('project_stages', 'components.id_project_stage', '=', 'project_stages.id')
            ->where('projects.risk_level', '=', 'MR')
            ->where('projects.id', '=', $id)
            ->groupBy('components.name', 'components.id_project_stage', 'components.id')
            ->get();
        $collection = collect($getComponent);

        $praKonstruksi = $collection->where('id_project_stage', '=', 4);
        $konstruksi = $collection->where('id_project_stage', '=', 1);
        $operasi = $collection->where('id_project_stage', '=', 2);
        $pascaOperasi = $collection->where('id_project_stage', '=', 3);

        $collectionSop = collect($getSop);

        foreach ($praKonstruksi as $values) {
            $inline1 = new TextRun();
            $inline1->addText($values->component_name);
            $templateProcessor->cloneBlock('block_name_pra_konst', count($praKonstruksi));
            $templateProcessor->setComplexValue('pra_konst', $inline1);
            $sopPraKonstruksi = $collectionSop->where('id_component', '=', $values->id);
            foreach ($sopPraKonstruksi as $sop) {
                $inline2 = new TextRun();
                $inline2->addText($sop->sop_name);
                $templateProcessor->cloneBlock('block_name_sop_pra_konst', count($sopPraKonstruksi));
                $templateProcessor->setComplexValue('sop_pra_konst', $inline2);
            }
        }


        foreach ($konstruksi as $value) {
            $inline = new TextRun();
            $inline->addText($value->component_name);
            $templateProcessor->cloneBlock('block_name_konstruksi', count($konstruksi));
            $templateProcessor->setComplexValue('konstruksi', $inline);
            $sopKonstruksi = $collectionSop->where('id_component', '=', $value->id);
            foreach ($sopKonstruksi as $sop) {
                $inline2 = new TextRun();
                $inline2->addText($sop->sop_name);
                $templateProcessor->cloneBlock('block_name_sop_konstruksi', count($sopKonstruksi));
                $templateProcessor->setComplexValue('sop_konstruksi', $inline2);
            }
        }

        foreach ($operasi as $value) {
            $inline = new TextRun();
            $inline->addText($value->component_name);
            $templateProcessor->cloneBlock('block_name_operasi', count($operasi));
            $templateProcessor->setComplexValue('operasi', $inline);
            $sopOperasi = $collectionSop->where('id_component', '=', $value->id);
            foreach ($sopOperasi as $sop) {
                $inline2 = new TextRun();
                $inline2->addText($sop->sop_name);
                if (count($sopOperasi) > 0) {
                    $templateProcessor->cloneBlock('block_name_sop_operasi', count($sopOperasi));
                } else {
                    $templateProcessor->deleteBlock('block_name_sop_operasi');
                }

                $templateProcessor->setComplexValue('sop_operasi', $inline2);
            }
        }

        foreach ($pascaOperasi as $value) {
            $inline = new TextRun();
            $inline->addText($value->component_name);
            $templateProcessor->cloneBlock('block_name_pasca_ops', count($pascaOperasi));
            $templateProcessor->setComplexValue('pasca_ops', $inline);
            $sopPascaOperasi = $collectionSop->where('id_component', '=', $value->id);
            foreach ($sopPascaOperasi as $sop) {
                $inline2 = new TextRun();
                $inline2->addText($sop->sop_name);
                $templateProcessor->cloneBlock('block_name_sop_pasca_ops', count($sopPascaOperasi));
                $templateProcessor->setComplexValue('sop_pasca_ops', $inline2);
            }
        }

        $save_file_name = str_replace('/', '-', $getData->project_title) . ".docx";
        // $templateProcessor->saveAs($save_file_name);
        // return response()->download($save_file_name)->deleteFileAfterSend(false);
        $tmpName = $templateProcessor->save();
        Storage::disk('public')->put($save_file_name, file_get_contents($tmpName));
        unlink($tmpName);
        return redirect()->away(Storage::disk('public')->temporaryUrl($save_file_name,now()->addMinutes(env('TEMPORARY_URL_TIMEOUT'))));
    }

    public function ExportBA($id, $type)
    {
        $beritaAcara = MeetingReport::where([['id_project', $id], ['document_type', $type]])->first();
        $expert_bank_member = ExpertBankTeamMember::where([['id_expert_bank_team', $beritaAcara->expert_bank_team_id], ['position', 'Ketua']])->first();
        $ketua_tuk = $expert_bank_member->expertBank->name;
        $institution = $expert_bank_member->expertBank->institution;
        $templateProcessor = new TemplateProcessor(storage_path('app/public/template/template_berita_acara.docx'));

        $templateProcessor->setValue('title', strtoupper($beritaAcara->project->project_title));
        $templateProcessor->setValue('address', strtoupper($beritaAcara->project->address));
        $templateProcessor->setValue('district', strtoupper($beritaAcara->project->district->name));
        $templateProcessor->setValue('province', strtoupper($beritaAcara->project->province->name));
        $templateProcessor->setValue('initiator_name', strtoupper($beritaAcara->project->initiator->name));
        $templateProcessor->setValue('date_meet', Carbon::createFromFormat('Y-m-d', $beritaAcara->meeting_date)->locale('id')->isoFormat('dddd/D MMMM Y'));
        $templateProcessor->setValue('location', $beritaAcara->location);
        $templateProcessor->setValue('initiator_name_small', $beritaAcara->project->initiator->name);
        $templateProcessor->setValue('pic', $beritaAcara->project->initiator->pic);
        $templateProcessor->setValue('position', $beritaAcara->location);
        $templateProcessor->setValue('title_small', $beritaAcara->project->project_title);
        $templateProcessor->setValue('address_small', $beritaAcara->project->address);
        $templateProcessor->setValue('district_small', $beritaAcara->project->district->name);
        $templateProcessor->setValue('province_small', $beritaAcara->project->province->name);
        $templateProcessor->setValue('initiator_name_small', $beritaAcara->project->initiator->name);
        $templateProcessor->setValue('ketua_tuk', $ketua_tuk);
        $templateProcessor->setValue('institution', $institution);

        $save_file_name = 'berita-acara-ka-' . str_replace('/', '-', $beritaAcara->project->project_title) . '.docx';
        // if (!File::exists(storage_path('app/public/berita-acara/'))) {
        //     File::makeDirectory(storage_path('app/public/berita-acara/'));
        //     $templateProcessor->saveAs(storage_path('app/public/berita-acara/' . $save_file_name));
        // }

        // return response()->download(storage_path('app/public/berita-acara/' . $save_file_name))->deleteFileAfterSend(false);
        $tmpName = $templateProcessor->save();
        Storage::disk('public')->put('berita-acara/' . $save_file_name, file_get_contents($tmpName));
        unlink($tmpName);

        return redirect()->away(Storage::disk('public')->temporaryUrl('berita-acara/' . $save_file_name, now()->addMinutes(env('TEMPORARY_URL_TIMEOUT'))));
    }

    public function saveKADoc(Request $request)
    {
        $dokumenKa = '';
        if ($request->file('dokumenKa')) {
            $dokumenKa = $request->file('dokumenKa');
            $docName = 'form-ka-' . $request->project_name . '.docx';
            // $dokumenKa->storePubliclyAs('public/formulir/', $docName);
            $dokumenKa->storeAs('public/formulir', $docName);

            $getDocumentData = DocumentAttachment::where('attachment', '=', $docName)
                ->where('type', '=', 'Formulir KA')
                ->where('id_project', '=', $request->id_project)
                ->first();

            if (!$getDocumentData) {
                DocumentAttachment::create([
                    'id_project' => $request->id_project,
                    'attachment' => $docName,
                    'type' => 'Formulir KA'
                ]);
            }

            DocumentAttachment::where('attachment', '=', $docName)
                ->where('type', '=', 'Formulir KA')
                ->where('id_project', '=', $request->id_project)
                ->update(['updated_at' => now()]);
        }

        return response()->json(['status' => 'success', 'message' => 'Uploaded successfully'], 200);
    }

    public function getDocKA(Request $request)
    {
        return DocumentAttachment::where('id_project', '=', $request->id)->where('type', '=', 'Formulir KA')->first();
    }

    // public function getVersionUklUPl()

    public function uklUpl($id_project, Request $request)
    {
        // if (!File::exists(storage_path('app/public/workspace/'))) {
        //     File::makeDirectory(storage_path('app/public/workspace/'));
        // }

        Carbon::setLocale('id');
        $project = Project::with('listSubProject')->findOrFail($id_project);
        $listSubProject = array_values($project->listSubProject->toArray());
        $document_attachment = DocumentAttachment::where([['id_project', $id_project],['type', 'Dokumen UKL UPL'], ['versi', 0]])->orderBy('created_at', 'desc')->first();
        if(request()->has('pdfVersi')) {
            $document = DocumentAttachment::where([['id_project', $id_project],['type', 'Dokumen Versi UKL UPL']])->orderBy('created_at', 'desc')->first();
            $pdfName = 'workspace/' . 'document-' . strtolower(str_replace('/', '-', $project->project_title));

            $document_attachments = new DocumentAttachment();
            $document_attachments->id_project = $project->id;

            if($document === null) {
                $document_attachments->versi = 0;
                $document_attachments->attachment = $pdfName . '-versi-0-' . '.docx';
                //get file and copy it to local
                Storage::disk('local')->put($pdfName.'.docx', Storage::disk('public')->get('workspace/' .'ukl-upl-' . strtolower(str_replace('/', '-', $project->project_title . '.docx'))));

                //put it to s3 storage
                Storage::disk('public')->put($pdfName . '-versi-0-' . '.docx', Storage::disk('local')->get($pdfName.'.docx'));
            } else {
                $document_attachments->versi = $document->versi + 1;
                $document_attachments->attachment = $pdfName . '-versi-' . $document->versi + 1 . '-' . '.docx';
                //get file and copy it to local
                Storage::disk('local')->put($pdfName.'.docx', Storage::disk('public')->get('workspace/' .'ukl-upl-' . strtolower(str_replace('/', '-', $project->project_title . '.docx'))));

                //put it to s3 storage
                Storage::disk('public')->put($pdfName . '-versi-' . $document->versi + 1 . '-' . '.docx', Storage::disk('local')->get($pdfName.'.docx'));
            }

            $document_attachments->type = 'Dokumen Versi UKL UPL';
            $document_attachments->save();

            $pdf_url = $this->docxToPdf($document_attachment->attachment);
            return [
                'file_name' => 'ukl-upl-' . strtolower(str_replace('/', '-', $project->project_title)) . '.docx',
                'project_title' => strtolower(str_replace('/', '-', $project->project_title)),
                'pdf_url' => $pdf_url,
                'docx_url' => $document_attachment->attachment,
                'create_time' => $document_attachment->created_at->toTimeString(),
                'versi_doc' => $document_attachment->versi,
            ];
        }
        if($document_attachment && !request()->has('regenerate') && !request()->has('pdfVersi')) {
            $pdf_url = $this->docxToPdf($document_attachment->attachment);
            if ($project->marking === 'uklupl-mt.returned-examination') {
                $project = Project::findOrFail($id_project);
                // Workflow when first generate revisi
                $project->applyWorkFlowTransition('return-uklupl-examination', 'uklupl-mt.returned-examination', 'uklupl-mt.matrix-upl');
                $project->save();
            }
            return [
                'file_name' => 'ukl-upl-' . strtolower(str_replace('/', '-', $project->project_title)) . '.docx',
                'project_title' => strtolower(str_replace('/', '-', $project->project_title)),
                'pdf_url' => $pdf_url,
                'docx_url' => $document_attachment->attachment,
                'create_time' => $document_attachment->created_at->toTimeString(),
                'versi_doc' => $document_attachment->versi,
            ];
        }

        $save_file_name = 'ukl-upl-' . strtolower(str_replace('/', '-', $project->project_title)) . '.docx';

        $project_title_big = strtoupper($project->project_title);
        $pemrakarsa = htmlspecialchars($project->initiator->name);
        $pemrakarsa_address = $project->initiator->address;
        $pemrakarsa_phone = $project->initiator->phone;
        $pemrakarsa_nib = $project->initiator->nib;
        $pic = $project->initiator->pic;
        $pic_position = $project->initiator->pic_role;
        $district = '';
        $project_sector = $listSubProject[0]['sector_name'];
        $project_title = $project->project_title;
        $project_kbli = '';
        $project_scale = $project->scale .  ' ' . $project->scale_unit;
        $project_address = '';
        $project_address_single = '';
        $project_district = '';
        $project_province = '';
        $project_year = $project->project_year;

        if($project->kbli) {
            if($project->kbli == 'undefined') {
                $project_kbli = 'Non KBLI';
            } else {
                $project_kbli = $project->kbli;
            }
        } else {
            $project_kbli = 'Non KBLI';
        }


        if($project->address) {
            if($project->address->first()) {
                $district = ucwords(strtolower($project->address->first()->district));
                $project_district = ucwords(strtolower($project->address->first()->district));
                $project_province = 'Provinsi ' . ucwords(strtolower($project->address->first()->prov));
                $project_address_single = $project->address->first()->address;
                $project_address = $project->address->first()->address . ' ' . ucwords(strtolower($project->address->first()->district)) .
                                    ' Provinsi ' . ucwords(strtolower($project->address->first()->prov));
            }
        }

        // ======= IMPACT IDENTIFICATION ===== //
        $ids = [4, 1, 2, 3];
        $stages = ProjectStage::select('id', 'name')->get()->sortBy(function ($model) use ($ids) {
            return array_search($model->getKey(), $ids);
        });
        $impact_identification = ImpactIdentification::where('id_project', $id_project)->get();

        $dl_pasca_operasi = [];
        $com_pra_konstruksi = [];
        $com_konstruksi = [];
        $com_operasi = [];
        $pk = [];
        $kgk = [];
        $kb = [];
        $kse = [];
        $kkm = [];
        $kls = [];
        $kll = [];
        $ogk = [];
        $ob = [];
        $ose = [];
        $okm = [];
        $ols = [];
        $oll = [];
        $po = [];
        $pertek_block = [];
        $html_data = [];

        foreach($stages as $s) {
            foreach($impact_identification as $imp) {
                $ronaAwal = '';
                $component = '';

                $id_stages = null;

                if ($imp->component) {
                    $id_stages = $imp->component->component->id_project_stage;

                    if ($id_stages == $s->id) {
                        if ($imp->ronaAwal) {
                            $ronaAwal = $imp->ronaAwal->rona_awal->name;
                            $component = $imp->component->component->name;
                        } else {
                            continue;
                        }
                    } else {
                        continue;
                    }
                } else {
                    continue;
                }

                $change_type = $imp->id_change_type ? $imp->changeType->name : '';

                // component type
                $component_type = $this->getComponentType($imp);

                $komponen_desc = $this->renderHtmlTable($imp->component->description);

                if($s->name  == 'Pra Konstruksi') {
                    if(array_search($component, array_column($com_pra_konstruksi, 'com_pra_konstruksi_name')) === false) {
                        $com_pra_konstruksi[] = [
                            'com_pra_konstruksi_name' => $component,
                            'com_pra_konstruksi_desc' => '${pk_desc_' . $imp->id . '}',
                            'com_pra_konstruksi_unit' => htmlspecialchars($imp->ronaAwal->measurement)
                        ];

                        $html_data[] = [
                            'label' => '${pk_desc_' . $imp->id . '}',
                            'data' => $komponen_desc
                        ];
                    }

                    $pk[] = $this->getUklUplData($imp, 'pk', $component, $change_type, $ronaAwal);
                }

                if($s->name == 'Konstruksi') {
                    if(array_search($component, array_column($com_konstruksi, 'com_konstruksi_name')) === false) {
                        $com_konstruksi[] = [
                            'com_konstruksi_name' => $component,
                            'com_konstruksi_desc' => '${k_desc_' . $imp->id . '}',
                            'com_konstruksi_unit' => htmlspecialchars($imp->ronaAwal->measurement)
                        ];

                        $html_data[] = [
                            'label' => '${k_desc_' . $imp->id . '}',
                            'data' => $komponen_desc
                        ];
                    }

                    if(strtolower($component_type) == 'geofisik kimia') {
                        $kgk[] = $this->getUklUplData($imp, 'kgk', $component, $change_type, $ronaAwal);
                    } else if(strtolower($component_type) == 'biologi') {
                        $kb[] = $this->getUklUplData($imp, 'kb', $component, $change_type, $ronaAwal);
                    } else if(strtolower($component_type) == 'sosial, ekonomi, dan budaya') {
                        $kse[] = $this->getUklUplData($imp, 'kse', $component, $change_type, $ronaAwal);
                    } else if(strtolower($component_type) == 'kesehatan masyarakat') {
                        $kkm[] = $this->getUklUplData($imp, 'kkm', $component, $change_type, $ronaAwal);
                    } else if(strtolower($component_type) == 'kegiatan lain sekitar') {
                        $kls[] = $this->getUklUplData($imp, 'kls', $component, $change_type, $ronaAwal);
                    } else if(strtolower($component_type) == 'lain lain') {
                        $kll[] = $this->getUklUplData($imp, 'kll', $component, $change_type, $ronaAwal);
                    }

                }

                if($s->name == 'Operasi') {
                    if(array_search($component, array_column($com_operasi, 'com_operasi_name')) === false) {
                        $com_operasi[] = [
                            'com_operasi_name' => $component,
                            'com_operasi_desc' => '${o_desc_' . $imp->id . '}',
                            'com_operasi_unit' => $imp->ronaAwal->measurement
                        ];

                        $html_data[] = [
                            'label' => '${o_desc_' . $imp->id . '}',
                            'data' => $komponen_desc
                        ];
                    }

                    if(strtolower($component_type) == 'geofisik kimia') {
                        $ogk[] = $this->getUklUplData($imp, 'ogk', $component, $change_type, $ronaAwal);
                    } else if(strtolower($component_type) == 'biologi') {
                        $ob[] = $this->getUklUplData($imp, 'ob', $component, $change_type, $ronaAwal);
                    } else if(strtolower($component_type) == 'sosial, ekonomi, dan budaya') {
                        $ose[] = $this->getUklUplData($imp, 'ose', $component, $change_type, $ronaAwal);
                    } else if(strtolower($component_type) == 'kesehatan masyarakat') {
                        $okm[] = $this->getUklUplData($imp, 'okm', $component, $change_type, $ronaAwal);
                    } else if(strtolower($component_type) == 'kegiatan lain sekitar') {
                        $ols[] = $this->getUklUplData($imp, 'ols', $component, $change_type, $ronaAwal);
                    } else if(strtolower($component_type) == 'lain lain') {
                        $oll[] = $this->getUklUplData($imp, 'oll', $component, $change_type, $ronaAwal);
                    }
                }

                if($s->name == 'Pasca Operasi') {
                    $dl_pasca_operasi[] = [
                        'dl_pasca_operasi_name' => htmlspecialchars("$change_type $ronaAwal akibat $component" . ' dengan besaran ' . $imp->ronaAwal->measurement)
                    ];

                    $po[] = $this->getUklUplData($imp, 'po', $component, $change_type, $ronaAwal);
                }
            }
        }

        // ========= PERTEK ======= //
        $pertek_columns = Schema::getColumnListing('project_filters');
        $pertek_columns = array_filter($pertek_columns, function($x) {
            return !(
                        $x == 'id' ||
                        $x == 'id_project' ||
                        $x == 'nothing' ||
                        $x == 'created_at' ||
                        $x == 'updated_at' ||
                        $x == 'high_pollution' ||
                        $x == 'high_emission' ||
                        $x == 'low_traffic' ||
                        $x == 'mid_traffic' ||
                        $x == 'high_traffic'
                    );
        });

        $pertek_list = '';
        $pertek = ProjectFilter::where('id_project', $project->id)->first();
        if($pertek) {
            $pertek = $pertek->toArray();
            $pertek_no = 'a';
            foreach($pertek_columns as $key => $value) {
                if($pertek[$value]) {
                    $pertek_block[] = [
                        'pertek_name' => $pertek_no . '. ' . $this->pertekColumnIndoName($value)
                    ];
                    $pertek_list .= $this->pertekColumnIndoName($value) . ', ';
                    $pertek_no++;
                }
            }
        }

        if(strlen($pertek_list) > 0) {
            $pertek_list = strtolower(substr($pertek_list, 0, -2));
        }

        $exp_pertek_list = explode(', ', $pertek_list);
        if(count($exp_pertek_list) > 1) {
            if(count($exp_pertek_list) == 2) {
                $pertek_list = $exp_pertek_list[0] . ' dan ' . $exp_pertek_list[1];
            } else {
                $exp_pertek_list[count($exp_pertek_list) - 1] = 'dan ' . $exp_pertek_list[count($exp_pertek_list) - 1];
                $pertek_list = join(', ', $exp_pertek_list);
            }
        }

        $doc_date = Carbon::createFromFormat('Y-m-d', date('Y-m-d'))->isoFormat('D MMMM Y');

        $templateProcessor = new TemplateProcessor(storage_path('app/public/template/template_ukl_upl.docx'));
        $templateProcessor->setValue('project_title_big', $project_title_big);
        $templateProcessor->setValue('pemrakarsa', $pemrakarsa);
        $templateProcessor->setValue('pemrakarsa_address', $pemrakarsa_address);
        $templateProcessor->setValue('pemrakarsa_phone', $pemrakarsa_phone);
        $templateProcessor->setValue('pemrakarsa_nib', $pemrakarsa_nib);
        $templateProcessor->setValue('pic', $pic);
        $templateProcessor->setValue('pic_position', $pic_position);
        $templateProcessor->setValue('district', $district);
        $templateProcessor->setValue('project_sector', $project_sector);
        $templateProcessor->setValue('project_title', $project_title);
        $templateProcessor->setValue('project_kbli', $project_kbli);
        $templateProcessor->setValue('project_scale', $project_scale);
        $templateProcessor->setValue('project_address', $project_address);
        $templateProcessor->setValue('project_address_single', $project_address_single);
        $templateProcessor->setValue('project_district', $project_district);
        $templateProcessor->setValue('project_province', $project_province);
        $templateProcessor->setValue('project_year', $project_year);
        $templateProcessor->setValue('doc_date', $doc_date);
        $templateProcessor->setValue('pertek_list', $pertek_list);
        $templateProcessor->cloneBlock('pertek_block', count($pertek_block), true, false, $pertek_block);
        $templateProcessor->cloneBlock('dl_pasca_operasi_block', count($dl_pasca_operasi), true, false, $dl_pasca_operasi);
        $templateProcessor->cloneBlock('com_pra_konstruksi_block', count($com_pra_konstruksi), true, false, $com_pra_konstruksi);
        $templateProcessor->cloneBlock('com_konstruksi_block', count($com_konstruksi), true, false, $com_konstruksi);
        $templateProcessor->cloneBlock('com_operasi_block', count($com_operasi), true, false, $com_operasi);
        $templateProcessor->cloneRowAndSetValues('pk', $pk);
        $templateProcessor->cloneRowAndSetValues('kgk', $kgk);
        $templateProcessor->cloneRowAndSetValues('kb', $kb);
        $templateProcessor->cloneRowAndSetValues('kse', $kse);
        $templateProcessor->cloneRowAndSetValues('kkm', $kkm);
        $templateProcessor->cloneRowAndSetValues('kls', $kls);
        $templateProcessor->cloneRowAndSetValues('kll', $kll);
        $templateProcessor->cloneRowAndSetValues('ogk', $ogk);
        $templateProcessor->cloneRowAndSetValues('ob', $ob);
        $templateProcessor->cloneRowAndSetValues('ose', $ose);
        $templateProcessor->cloneRowAndSetValues('okm', $okm);
        $templateProcessor->cloneRowAndSetValues('ols', $ols);
        $templateProcessor->cloneRowAndSetValues('oll', $oll);
        $templateProcessor->cloneRowAndSetValues('po', $po);

        if(count($html_data) > 0) {
            for($i = 0; $i < count($html_data); $i++) {
                $templateProcessor->setComplexBlock($html_data[$i]['label'], $html_data[$i]['data']);
            }
        }

        // $templateProcessor->saveAs(storage_path('app/public/workspace/' . $save_file_name));
        $tmpName = $templateProcessor->save();
        Storage::disk('public')->put('workspace/' . $save_file_name, file_get_contents($tmpName));
        unlink($tmpName);

        $document_attachment = new DocumentAttachment();
        if($project->marking === 'uklupl-mt.returned-examination' && !request()->has('regenerate')) {
            $document = DocumentAttachment::where([['id_project', $id_project],['type', 'Dokumen UKL UPL']])->orderBy('created_at', 'desc')->first();
            $document_attachment->versi = $document->versi + 1;

            // Workflow when first generate revisi
            $project->applyWorkFlowTransition('return-uklupl-examination', 'uklupl-mt.returned-examination', 'uklupl-mt.matrix-upl');
            $project->save();
        } else {
            $document = DocumentAttachment::where([['id_project', $id_project],['type', 'Dokumen UKL UPL']])->orderBy('created_at', 'desc')->first();
            if($document === null) {
                $document_attachment->versi = 0;
            } else {
                $document_attachment->versi = $document->versi;
            }
        }
        $document_attachment->id_project = $project->id;
        $document_attachment->attachment = 'workspace/' . $save_file_name;
        $document_attachment->type = 'Dokumen UKL UPL';
        $document_attachment->save();

        // get pdf url
        $pdf_url = $this->docxToPdf($document_attachment->attachment);

        return [
            'file_name' => $save_file_name,
            'project_title' => strtolower(str_replace('/', '-', $project->project_title)),
            'pdf_url' => $pdf_url,
            'docx_url' => $document_attachment->attachment,
            'create_time' => $document_attachment->created_at->toTimeString()
        ];
    }

    public function matriksUklUpl($id_project) {
        $project = Project::with('listSubProject')->findOrFail($id_project);
        // ======= IMPACT IDENTIFICATION ===== //
        $templateProcessor = new TemplateProcessor(storage_path('app/public/template/template_matriks_ukl_upl.docx'));
        $ids = [4, 1, 2, 3];
        $stages = ProjectStage::select('id', 'name')->get()->sortBy(function ($model) use ($ids) {
            return array_search($model->getKey(), $ids);
        });
        $impact_identification = ImpactIdentification::where('id_project', $id_project)->get();

        $dl_pasca_operasi = [];
        $com_pra_konstruksi = [];
        $com_konstruksi = [];
        $com_operasi = [];
        $pk = [];
        $kgk = [];
        $kb = [];
        $kse = [];
        $kkm = [];
        $kls = [];
        $kll = [];
        $ogk = [];
        $ob = [];
        $ose = [];
        $okm = [];
        $ols = [];
        $oll = [];
        $po = [];
        $pertek_block = [];
        $html_data = [];

        foreach($stages as $s) {
            foreach($impact_identification as $imp) {
                $ronaAwal = '';
                $component = '';

                $id_stages = null;

                if ($imp->component) {
                    $id_stages = $imp->component->component->id_project_stage;

                    if ($id_stages == $s->id) {
                        if ($imp->ronaAwal) {
                            $ronaAwal = $imp->ronaAwal->rona_awal->name;
                            $component = $imp->component->component->name;
                        } else {
                            continue;
                        }
                    } else {
                        continue;
                    }
                } else {
                    continue;
                }

                $change_type = $imp->id_change_type ? $imp->changeType->name : '';

                // component type
                $component_type = $this->getComponentType($imp);

                $komponen_desc = $this->renderHtmlTable($imp->component->description);

                if($s->name  == 'Pra Konstruksi') {
                    if(array_search($component, array_column($com_pra_konstruksi, 'com_pra_konstruksi_name')) === false) {
                        $com_pra_konstruksi[] = [
                            'com_pra_konstruksi_name' => $component,
                            'com_pra_konstruksi_desc' => '${pk_desc_' . $imp->id . '}',
                            'com_pra_konstruksi_unit' => htmlspecialchars($imp->ronaAwal->measurement)
                        ];

                        $html_data[] = [
                            'label' => '${pk_desc_' . $imp->id . '}',
                            'data' => $komponen_desc
                        ];
                    }

                    $pk[] = $this->getUklUplData($imp, 'pk', $component, $change_type, $ronaAwal);
                }

                if($s->name == 'Konstruksi') {
                    if(array_search($component, array_column($com_konstruksi, 'com_konstruksi_name')) === false) {
                        $com_konstruksi[] = [
                            'com_konstruksi_name' => $component,
                            'com_konstruksi_desc' => '${k_desc_' . $imp->id . '}',
                            'com_konstruksi_unit' => htmlspecialchars($imp->ronaAwal->measurement)
                        ];

                        $html_data[] = [
                            'label' => '${k_desc_' . $imp->id . '}',
                            'data' => $komponen_desc
                        ];
                    }

                    if(strtolower($component_type) == 'geofisik kimia') {
                        $kgk[] = $this->getUklUplData($imp, 'kgk', $component, $change_type, $ronaAwal);
                    } else if(strtolower($component_type) == 'biologi') {
                        $kb[] = $this->getUklUplData($imp, 'kb', $component, $change_type, $ronaAwal);
                    } else if(strtolower($component_type) == 'sosial, ekonomi, dan budaya') {
                        $kse[] = $this->getUklUplData($imp, 'kse', $component, $change_type, $ronaAwal);
                    } else if(strtolower($component_type) == 'kesehatan masyarakat') {
                        $kkm[] = $this->getUklUplData($imp, 'kkm', $component, $change_type, $ronaAwal);
                    } else if(strtolower($component_type) == 'kegiatan lain sekitar') {
                        $kls[] = $this->getUklUplData($imp, 'kls', $component, $change_type, $ronaAwal);
                    } else if(strtolower($component_type) == 'lain lain') {
                        $kll[] = $this->getUklUplData($imp, 'kll', $component, $change_type, $ronaAwal);
                    }

                }

                if($s->name == 'Operasi') {
                    if(array_search($component, array_column($com_operasi, 'com_operasi_name')) === false) {
                        $com_operasi[] = [
                            'com_operasi_name' => $component,
                            'com_operasi_desc' => '${o_desc_' . $imp->id . '}',
                            'com_operasi_unit' => $imp->ronaAwal->measurement
                        ];

                        $html_data[] = [
                            'label' => '${o_desc_' . $imp->id . '}',
                            'data' => $komponen_desc
                        ];
                    }

                    if(strtolower($component_type) == 'geofisik kimia') {
                        $ogk[] = $this->getUklUplData($imp, 'ogk', $component, $change_type, $ronaAwal);
                    } else if(strtolower($component_type) == 'biologi') {
                        $ob[] = $this->getUklUplData($imp, 'ob', $component, $change_type, $ronaAwal);
                    } else if(strtolower($component_type) == 'sosial, ekonomi, dan budaya') {
                        $ose[] = $this->getUklUplData($imp, 'ose', $component, $change_type, $ronaAwal);
                    } else if(strtolower($component_type) == 'kesehatan masyarakat') {
                        $okm[] = $this->getUklUplData($imp, 'okm', $component, $change_type, $ronaAwal);
                    } else if(strtolower($component_type) == 'kegiatan lain sekitar') {
                        $ols[] = $this->getUklUplData($imp, 'ols', $component, $change_type, $ronaAwal);
                    } else if(strtolower($component_type) == 'lain lain') {
                        $oll[] = $this->getUklUplData($imp, 'oll', $component, $change_type, $ronaAwal);
                    }
                }

                if($s->name == 'Pasca Operasi') {
                    $dl_pasca_operasi[] = [
                        'dl_pasca_operasi_name' => htmlspecialchars("$change_type $ronaAwal akibat $component" . ' dengan besaran ' . $imp->ronaAwal->measurement)
                    ];

                    $po[] = $this->getUklUplData($imp, 'po', $component, $change_type, $ronaAwal);
                }
            }
        }

        $templateProcessor->cloneBlock('pertek_block', count($pertek_block), true, false, $pertek_block);
        $templateProcessor->cloneBlock('dl_pasca_operasi_block', count($dl_pasca_operasi), true, false, $dl_pasca_operasi);
        $templateProcessor->cloneBlock('com_pra_konstruksi_block', count($com_pra_konstruksi), true, false, $com_pra_konstruksi);
        $templateProcessor->cloneBlock('com_konstruksi_block', count($com_konstruksi), true, false, $com_konstruksi);
        $templateProcessor->cloneBlock('com_operasi_block', count($com_operasi), true, false, $com_operasi);
        $templateProcessor->cloneRowAndSetValues('pk', $pk);
        $templateProcessor->cloneRowAndSetValues('kgk', $kgk);
        $templateProcessor->cloneRowAndSetValues('kb', $kb);
        $templateProcessor->cloneRowAndSetValues('kse', $kse);
        $templateProcessor->cloneRowAndSetValues('kkm', $kkm);
        $templateProcessor->cloneRowAndSetValues('kls', $kls);
        $templateProcessor->cloneRowAndSetValues('kll', $kll);
        $templateProcessor->cloneRowAndSetValues('ogk', $ogk);
        $templateProcessor->cloneRowAndSetValues('ob', $ob);
        $templateProcessor->cloneRowAndSetValues('ose', $ose);
        $templateProcessor->cloneRowAndSetValues('okm', $okm);
        $templateProcessor->cloneRowAndSetValues('ols', $ols);
        $templateProcessor->cloneRowAndSetValues('oll', $oll);
        $templateProcessor->cloneRowAndSetValues('po', $po);

        $tmpName = $templateProcessor->save();

        $save_file_name = 'matriks-ukl-upl-' . strtolower(str_replace('/', '-', $project->project_title)) . '.docx';
        Storage::disk('public')->put('workspace/' . $save_file_name, file_get_contents($tmpName));
        unlink($tmpName);

        $document_attachment = new DocumentAttachment();
        $document_attachment->id_project = $project->id;
        $document_attachment->attachment = 'workspace/' . $save_file_name;
        $document_attachment->type = 'Matriks UKL UPL';
        $document_attachment->save();

        $document_attachment = DocumentAttachment::where([['id_project', $id_project], ['type', 'Matriks UKL UPL']])->orderBy('created_at', 'desc')->first();
        $pdf_url = $this->docxToPdf($document_attachment->attachment);
        return [
            'file_name' => $save_file_name,
            'pdf_url' => $pdf_url,
            'project_title' => strtolower(str_replace('/', '-', $project->project_title)),
            'docx_url' => Storage::disk('public')->temporaryUrl('workspace/'.$save_file_name, now()->addMinutes(120)),
            'create_time' => $document_attachment->created_at->toTimeString(),
            'versi_doc' => 0,
        ];
    }

    public function deskripsiUklUpl($id_project) {

    }

    public function exportUklUplPdf($idProject)
    {
        $domPdfPath = base_path('vendor/dompdf/dompdf');
        Settings::setPdfRendererPath($domPdfPath);
        Settings::setPdfRendererName('DomPDF');
        $project = Project::findOrFail($idProject);

        //Load word file
        // $Content = IOFactory::load(storage_path('app/public/ukl-upl/' . 'ukl-upl-' . strtolower(str_replace('/', '-', $project->project_title)) . '.docx'));
        $tmpName = tempnam(sys_get_temp_dir(),'');
        $tmpFile = Storage::disk('public')->get('ukl-upl/' . 'ukl-upl-' . strtolower(str_replace('/', '-', $project->project_title)) . '.docx');
        file_put_contents($tmpName, $tmpFile);
        $Content = IOFactory::load($tmpName);

        //Save it into PDF
        $PDFWriter = IOFactory::createWriter($Content, 'PDF');

        // $PDFWriter->save(storage_path('app/public/ukl-upl/' . 'ukl-upl-' . strtolower(str_replace('/', '-', $project->project_title)) . '.pdf'));
        // return response()->download(storage_path('app/public/ukl-upl/' . 'ukl-upl-' . strtolower(str_replace('/', '-', $project->project_title)) . '.pdf'))->deleteFileAfterSend(false);

        $tmpName = tempnam(sys_get_temp_dir(),'');
        $PDFWriter->save($tmpName);
        Storage::disk('public')->put('ukl-upl/' . 'ukl-upl-' . strtolower(str_replace('/', '-', $project->project_title)) . '.pdf', file_get_contents($tmpName));
        unlink($tmpName);

        return redirect()->away(Storage::disk('public')->temporaryUrl('formulir/' .'ukl-upl-' . strtolower(str_replace('/', '-', $project->project_title)) . '.pdf', now()->addMinutes(env('TEMPORARY_URL_TIMEOUT'))));
    }

    private function getComponentType($imp) {
        $component_type = '';
        if($imp->ronaAwal->rona_awal) {
            if($imp->ronaAwal->rona_awal->componentType) {
                return $imp->ronaAwal->rona_awal->componentType->name;
            }
        }

        return $component_type;
    }

    private function getUklUplData($imp, $st, $component, $change_type, $ronaAwal) {
        $ukl_bentuk = '';
        $ukl_lokasi = '';
        $ukl_periode = $imp->envManagePlan ? $imp->envManagePlan->period : '';
        $ukl_keterangan = $imp->envManagePlan ? $imp->envManagePlan->description : '';
        $upl_bentuk = '';
        $upl_lokasi = '';
        $upl_periode = $imp->envMonitorPlan ? $imp->envMonitorPlan->period : '';
        $upl_pelaksana = $imp->envManagePlan ? $imp->envManagePlan->executor : '';
        $upl_pengawas = $imp->envManagePlan ? $imp->envManagePlan->supervisor : '';
        $upl_pelaporan = $imp->envManagePlan ? $imp->envManagePlan->report_recipient : '';
        $upl_keterangan = $imp->envMonitorPlan ? $imp->envMonitorPlan->description : '';

        if($imp->envManagePlan) {
            if($imp->envManagePlan->forms) {
                if($imp->envManagePlan->forms->first()) {
                    foreach($imp->envManagePlan->forms as $uk) {
                        $desc = htmlspecialchars($uk->description);
                        $ukl_bentuk .= "- {$desc} </w:t><w:p/><w:t>";
                    }
                }
            }

            if($imp->envManagePlan->locations) {
                if($imp->envManagePlan->locations->first()) {
                    foreach($imp->envManagePlan->locations as $uk)  {
                        $desc = htmlspecialchars($uk->description);
                        $ukl_lokasi .= "- {$desc} </w:t><w:p/><w:t>";
                    }
                }
            }
        }

        if($imp->envMonitorPlan) {
            if($imp->envMonitorPlan->forms) {
               if($imp->envMonitorPlan->forms->first()) {
                   foreach($imp->envMonitorPlan->forms as $up) {
                        $desc = htmlspecialchars($up->description);
                        $upl_bentuk .= "- {$desc} </w:t><w:p/><w:t>";
                   }
               }
            }

            if($imp->envMonitorPlan->locations) {
                if($imp->envMonitorPlan->locations->first()) {
                    foreach($imp->envMonitorPlan->locations as $up) {
                        $desc = htmlspecialchars($up->description);
                        $upl_lokasi .= "- {$desc} </w:t><w:p/><w:t>";
                    }
                }
            }
        }


        return [
            $st => '',
            $st . '_sumber_dampak' => $component,
            $st . '_jenis_dampak' => $change_type . ' ' . $ronaAwal,
            $st .'_besaran_dampak' => $imp->unit,
            $st . '_ukl_bentuk' => $ukl_bentuk,
            $st . '_ukl_lokasi' => $ukl_lokasi,
            $st . '_ukl_periode' => $ukl_periode,
            $st . '_upl_bentuk' => $upl_bentuk,
            $st . '_upl_lokasi' => $upl_lokasi,
            $st . '_upl_periode' => $upl_periode,
            $st . '_pelaksana' => $upl_pelaksana,
            $st . '_pengawas' => $upl_pengawas,
            $st . '_pelaporan' => $upl_pelaporan,
            $st . '_ukl_keterangan' => $ukl_keterangan,
            $st . '_upl_keterangan' => $upl_keterangan
        ];
    }

    private function pertekColumnIndoName($column) {
        $name = '';

        switch ($column) {
            case 'wastewater':
                $name = 'Air Limbah';
                break;
            case 'disposal_wastewater':
                $name = 'Pembuangan Air Limbah';
                break;
            case 'utilization_wastewater':
                $name = 'Pemanfaatan Air Limbah';
                break;
            case 'emission':
                $name = 'Emisi Gas Buang';
                break;
            case 'chimney':
                $name = 'Melalui Cerobong Asap';
                break;
            case 'genset':
                $name = 'Pembuangan Emisi Gas Buang Dari Genset';
                break;
            case 'b3':
                $name = 'Limbah B3';
                break;
            case 'collect_b3':
                $name = 'Pengumpulan Limbah B3';
                break;
            case 'hoard_b3':
                $name = 'Penimbunan Limbah B3';
                break;
            case 'process_b3':
                $name = 'Pengolahan Limbah B3';
                break;
            case 'utilization_b3':
                $name = 'Pemanfaatan Limbah B3';
                break;
            case 'dumping_b3':
                $name = 'Dumping Limbah B3';
                break;
            case 'tps':
                $name = 'TPS';
                break;
            case 'traffic':
                $name = 'Gangguan Lalu Lintas';
                break;
            default:
                break;
        }

        return $name;
    }

    private function renderHtmlTable($data, $width = null, $font = null, $font_size = null)
    {
        $table = new Table();
        $table->addRow();
        $cell = null;
        if($width) {
            $cell = $table->addCell($width);
        } else {
            $cell = $table->addCell();
        }
        $selected_font = $font ? $font : 'Cambria';
        $selected_font_size = $font_size ? $font_size : '16.5';
        $content = '';
        if($data) {
            $content = str_replace('<p>', '<p style="font-family: ' . $selected_font . '; font-size: ' . $selected_font_size . 'px;">', $this->replaceHtmlList($data));
        }
        Html::addHtml($cell, $content);
        return $table;
    }

    private function replaceHtmlList($data, $font = 'Cambria')
    {
        if($data) {
            $removed_nested_p = $this->removeNestedParagraph($data);
            return ListRender::parsingList($removed_nested_p, $font, '11px');
        } else {
            return '';
        }
    }

    private function removeNestedParagraph($data)
    {
        $old_data = $data;
        $new_data = null;

        while(true) {
            $new_data = preg_replace('/(.*<p>)(((?!<\/p>).)*?)(<p>)(.*?)(<\/p>)(.*)/', '\1\2\5\7', $old_data);
            if($new_data == $old_data) {
                break;
            } else {
                $old_data = $new_data;
            }
        }

        return $new_data;
    }

    private function docxToPdf($url)
    {
        $downloadUri = $url ;
        $key = Document::GenerateRevisionId($downloadUri);
        $convertedUri;
        $download_url = Document::GetConvertedUri($downloadUri, 'docx', 'pdf', $key, FALSE, $convertedUri);
        return $convertedUri;
    }
}
