<?php

namespace App\Http\Controllers;

use App\Entity\Component;
use App\Entity\DocumentAttachment;
use App\Entity\ExpertBankTeamMember;
use App\Entity\ImpactIdentification;
use App\Entity\ImpactStudy;
use App\Entity\MeetingReport;
use App\Entity\Project;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;
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
                'impact_identifications.potential_impact_evaluation',
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
            ->where('sub_projects.id_project', '=', $id)
            ->where('project_stages.name', '=', 'Pra Konstruksi')
            ->get();

        $konstruksiDetail = DB::table('sub_project_components')
            ->select(
                'impact_identifications.initial_study_plan',
                'rona_awal.name as rona_awal_name',
                'impact_identifications.potential_impact_evaluation',
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
            ->where('sub_projects.id_project', '=', $id)
            ->where('project_stages.name', '=', 'Konstruksi')
            ->get();

        $operasiDetail = DB::table('sub_project_components')
            ->select(
                'impact_identifications.initial_study_plan',
                'rona_awal.name as rona_awal_name',
                'impact_identifications.potential_impact_evaluation',
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
            ->where('sub_projects.id_project', '=', $id)
            ->where('project_stages.name', '=', 'Operasi')
            ->get();

        $pascaOperasiDetail = DB::table('sub_project_components')
            ->select(
                'impact_identifications.initial_study_plan',
                'rona_awal.name as rona_awal_name',
                'impact_identifications.potential_impact_evaluation',
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
                'impact_identifications.potential_impact_evaluation',
                'impact_identifications.is_hypothetical_significant'
            )
            ->selectRaw('ROW_NUMBER () OVER (ORDER BY impact_studies.id) as number')
            ->leftJoin('impact_identifications', 'impact_studies.id_impact_identification', '=', 'impact_identifications.id')
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
}
