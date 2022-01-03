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
use App\Entity\ProjectStage;
use App\Utils\TemplateProcessor;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

class ExportDocument extends Controller
{
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
                'components.name as component_name',
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
                'components.name as component_name',
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
                'components.name as component_name',
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
                'components.name as component_name',
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
        $Content = IOFactory::load(storage_path('app/public/formulir/' . $getProject->attachment));

        //Save it into PDF
        $getName = explode('.', $getProject->attachment);
        $PDFWriter = IOFactory::createWriter($Content, 'PDF');

        $PDFWriter->save(storage_path('app/public/formulir/' . $getName[0]) . '.pdf');

        return response()->download(storage_path('app/public/formulir/' . $getName[0] . '.pdf'))->deleteFileAfterSend(false);
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
                'sops.impact_quantity',
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

        $templateProcessor = new TemplateProcessor('template_ukl-upl.docx');

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

        $save_file_name = $getData->project_title . ".docx";
        $templateProcessor->saveAs($save_file_name);
        return response()->download($save_file_name)->deleteFileAfterSend(false);
    }

    public function ExportBA($id, $type)
    {
        $beritaAcara = MeetingReport::where([['id_project', $id], ['document_type', $type]])->first();
        $expert_bank_member = ExpertBankTeamMember::where([['id_expert_bank_team', $beritaAcara->expert_bank_team_id], ['position', 'Ketua']])->first();
        $ketua_tuk = $expert_bank_member->expertBank->name;
        $institution = $expert_bank_member->expertBank->institution;
        $templateProcessor = new TemplateProcessor('template_berita_acara.docx');

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

        $save_file_name = 'berita-acara-ka-' . $beritaAcara->project->project_title . '.docx';
        if (!File::exists(storage_path('app/public/berita-acara/'))) {
            File::makeDirectory(storage_path('app/public/berita-acara/'));
            $templateProcessor->saveAs(storage_path('app/public/berita-acara/' . $save_file_name));
        }

        return response()->download(storage_path('app/public/berita-acara/' . $save_file_name))->deleteFileAfterSend(false);
    }

    public function saveKADoc(Request $request)
    {
        $dokumenKa = '';
        if ($request->file('dokumenKa')) {
            $dokumenKa = $request->file('dokumenKa');
            $docName = 'form-ka-' . $request->project_name . '.docx';
            $dokumenKa->storePubliclyAs('public/formulir/', $docName);

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

    public function uklUpl($id_project)
    {
        if (!File::exists(storage_path('app/public/workspace/'))) {
            File::makeDirectory(storage_path('app/public/workspace/'));
        }

        Carbon::setLocale('id');
        $project = Project::findOrFail($id_project);

        $save_file_name = 'ukl-upl-' . strtolower($project->project_title) . '.docx';

        if (File::exists(storage_path('app/public/workspace/' . $save_file_name))) {
            return $save_file_name;
        }

        $project_title_big = strtoupper($project->project_title);
        $pemrakarsa = $project->initiator->name;
        $pemrakarsa_address = $project->initiator->address;
        $pemrakarsa_phone = $project->initiator->phone;
        $pemrakarsa_nib = $project->initiator->nib;
        $pic = $project->initiator->pic;
        $position = '';
        $district = '';
        $project_sector = $project->sector;
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
        $com_konstruksi = [];
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

        foreach($stages as $s) {
            foreach($impact_identification as $imp) {
                $ronaAwal = '';
                $component = '';

                $id_stages = null;

                if ($imp->subProjectComponent) {
                    if ($imp->subProjectComponent->id_project_stage) {
                        $id_stages = $imp->subProjectComponent->id_project_stage;
                    } else {
                        $id_stages = $imp->subProjectComponent->component->id_project_stage;
                    }

                    if ($id_stages == $s->id) {
                        if ($imp->subProjectRonaAwal) {
                            $ronaAwal = $imp->subProjectRonaAwal->id_rona_awal ? $imp->subProjectRonaAwal->ronaAwal->name : $imp->subProjectRonaAwal->name;
                            $component = $imp->subProjectComponent->id_component ? $imp->subProjectComponent->component->name : $imp->subProjectComponent->name;
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

                if($s->name  == 'Pra Konstruksi') {
                    $pk[] = $this->getUklUplData($imp, 'pk', $component, $change_type, $ronaAwal);
                }


                if($s->name == 'Konstruksi') {
                    if(array_search($component, array_column($com_konstruksi, 'com_konstruksi_name')) === false) {
                        $com_konstruksi[] = [
                            'com_konstruksi_name' => $component
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
                        $kll[] = $this->getUklUplData($imp, 'kls', $component, $change_type, $ronaAwal);
                    }

                }

                if($s->name == 'Operasi') {
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
                        $oll[] = $this->getUklUplData($imp, 'ols', $component, $change_type, $ronaAwal);
                    }
                }

                if($s->name == 'Pasca Operasi') {
                    $dl_pasca_operasi[] = [
                        'dl_pasca_operasi_name' => "$change_type $ronaAwal akibat $component"
                    ];

                    $po[] = $this->getUklUplData($imp, 'po', $component, $change_type, $ronaAwal);
                }
            }
        }

        $doc_date = Carbon::createFromFormat('Y-m-d', date('Y-m-d'))->isoFormat('D MMMM Y');

        $templateProcessor = new TemplateProcessor('template_ukl_upl.docx');
        $templateProcessor->setValue('project_title_big', $project_title_big);
        $templateProcessor->setValue('pemrakarsa', $pemrakarsa);
        $templateProcessor->setValue('pemrakarsa_address', $pemrakarsa_address);
        $templateProcessor->setValue('pemrakarsa_phone', $pemrakarsa_phone);
        $templateProcessor->setValue('pemrakarsa_nib', $pemrakarsa_nib);
        $templateProcessor->setValue('pic', $pic);
        $templateProcessor->setValue('position', $position);
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
        $templateProcessor->cloneBlock('dl_pasca_operasi_block', count($dl_pasca_operasi), true, false, $dl_pasca_operasi);
        $templateProcessor->cloneBlock('com_konstruksi_block', count($com_konstruksi), true, false, $com_konstruksi);
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

        $templateProcessor->saveAs(storage_path('app/public/workspace/' . $save_file_name));

        return $save_file_name;
    }

    public function exportUklUplPdf($idProject)
    {
        $domPdfPath = base_path('vendor/dompdf/dompdf');
        Settings::setPdfRendererPath($domPdfPath);
        Settings::setPdfRendererName('DomPDF');
        $project = Project::findOrFail($idProject);

        //Load word file
        $Content = IOFactory::load(storage_path('app/public/ukl-upl/' . 'ukl-upl-' . strtolower($project->project_title) . '.docx'));

        //Save it into PDF
        $PDFWriter = IOFactory::createWriter($Content, 'PDF');

        $PDFWriter->save(storage_path('app/public/ukl-upl/' . 'ukl-upl-' . strtolower($project->project_title) . '.pdf'));

        return response()->download(storage_path('app/public/ukl-upl/' . 'ukl-upl-' . strtolower($project->project_title) . '.pdf'))->deleteFileAfterSend(false);
    }

    private function getComponentType($imp) {
        $component_type = '';
        if($imp->subProjectRonaAwal->id_rona_awal) {
            $com_type = ComponentType::find($imp->subProjectRonaAwal->ronaAwal->id_component_type);
            if($com_type) {
                $component_type = $com_type->name;
            }
        } else {
            if($imp->subProjectRonaAwal->id_component_type) {
                $com_type = ComponentType::find($imp->subProjectRonaAwal->id_component_type);
                if($com_type) {
                    $component_type = $com_type->name;
                }
            }
        }

        return $component_type;
    }

    private function getUklUplData($imp, $st, $component, $change_type, $ronaAwal) {
        $ukl_bentuk = '';
        $ukl_lokasi = '';
        $ukl_periode = '';
        $upl_bentuk = '';
        $upl_lokasi = '';
        $upl_periode = '';
        $upl_pelaksana = '';
        $upl_pengawas = '';
        $upl_pelaporan = '';

        if($imp->envManagePlan) {
            if($imp->envManagePlan->first()) {
                $ukl = EnvManagePlan::where('id_impact_identifications', $imp->id)->get();
                foreach($ukl as $uk) {
                    $ukl_bentuk .= "- {$uk->form} </w:t><w:p/><w:t>";
                    $ukl_lokasi .= "- {$uk->location} </w:t><w:p/><w:t>";
                    $ukl_periode .= "- {$uk->period} </w:t><w:p/><w:t>";
                }
            }
        }

        if($imp->envMonitorPlan) {
            if($imp->envMonitorPlan->first()) {
                $upl = EnvMonitorPlan::where('id_impact_identifications', $imp->id)->get();
                foreach($upl as $up) {
                    $upl_bentuk .= "- {$up->form} </w:t><w:p/><w:t>";
                    $upl_lokasi .= "- {$up->location} </w:t><w:p/><w:t>";
                    $upl_periode .= "- {$up->period} </w:t><w:p/><w:t>";
                    $upl_pelaksana .= "{$up->executor} </w:t><w:p/><w:t>";
                    $upl_pengawas .= "{$up->supervisor} </w:t><w:p/><w:t>";
                    $upl_pelaporan .= "{$up->report_recipient} </w:t><w:p/><w:t>";
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
            $st . '_pelaporan' => $upl_pelaporan
        ];
    }
}