<?php

namespace App\Http\Controllers;

use App\Entity\MeetingReport;
use App\Entity\Project;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\File;

class ExportDocument extends Controller
{
    public function ExportKA($id)
    {
        if (Request::segment(3) == 'docx') {
            $getData = DB::table('impact_studies')
                ->select(
                    'impact_studies.forecast_method',
                    'impact_studies.required_information',
                    'impact_studies.data_gathering_method',
                    'impact_studies.analysis_method',
                    'impact_studies.evaluation_method',
                    'projects.project_title',
                    'initiators.pic',
                    'projects.description',
                    'projects.location_desc'
                )
                ->leftJoin('impact_identifications', 'impact_studies.id_impact_identification', '=', 'impact_identifications.id')
                ->leftJoin('projects', 'projects.id', '=', 'impact_identifications.id_project')
                ->leftJoin('project_components', 'project_components.id', '=', 'impact_identifications.id_project_component')
                ->leftJoin('project_rona_awals', 'project_rona_awals.id', '=', 'impact_identifications.id_project_rona_awal')
                ->leftJoin('change_types', 'change_types.id', '=', 'impact_identifications.id_change_type')
                ->leftJoin('units', 'units.id', '=', 'impact_identifications.id_unit')
                ->leftJoin('initiators', 'initiators.id', '=', 'projects.id_applicant')
                ->where('projects.id', '=', $id)
                ->first();

            $templateProcessor = new TemplateProcessor('template_KA.docx');

            $templateProcessor->setValue('project_title', $getData->project_title);
            $templateProcessor->setValue('pic', $getData->pic);
            $templateProcessor->setValue('description', htmlspecialchars($getData->description));
            $templateProcessor->setValue('location_desc', htmlspecialchars($getData->location_desc));
            $templateProcessor->setValue('required_information', $getData->required_information);
            $templateProcessor->setValue('data_gathering_method', $getData->data_gathering_method);
            $templateProcessor->setValue('analysis_method', $getData->analysis_method);
            $templateProcessor->setValue('forecast_method', $getData->forecast_method);
            $templateProcessor->setValue('evaluation_method', $getData->evaluation_method);

            $save_file_name = 'form-ka-' . $getData->project_title . ".docx";
            if (!File::exists(storage_path('app/public/formulir/'))) {
                File::makeDirectory(storage_path('app/public/formulir/'));
                $templateProcessor->saveAs(storage_path('app/public/formulir/' . $save_file_name));
            }
            // $templateProcessor->saveAs(storage_path('formulir/' . $save_file_name));

            return response()->download(storage_path('app/public/formulir/' . $save_file_name))->deleteFileAfterSend(false);
        }

        if (Request::segment(3) === 'pdf') {
            $getProject = Project::where('id', '=', $id)->first();

            $domPdfPath = base_path('vendor/dompdf/dompdf');
            Settings::setPdfRendererPath($domPdfPath);
            Settings::setPdfRendererName('DomPDF');

            //Load word file
            $Content = IOFactory::load(storage_path('app/public/formulir/form-ka-' . $getProject->project_title . '.docx'));

            //Save it into PDF
            $fileName = 'form-ka-' . $getProject->project_title . '.pdf';
            $PDFWriter = IOFactory::createWriter($Content, 'PDF');

            $PDFWriter->save(storage_path('app/public/formulir/' . $fileName));
            return response()->download(storage_path('app/public/formulir/' . $fileName))->deleteFileAfterSend(false);
        }
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

        $save_file_name = 'berita-acara-ka-' . $beritaAcara->project->project_title . '.docx';
        if (!File::exists(storage_path('app/public/berita-acara/'))) {
            File::makeDirectory(storage_path('app/public/berita-acara/'));
            $templateProcessor->saveAs(storage_path('app/public/berita-acara/' . $save_file_name));
        }

        return response()->download(storage_path('app/public/berita-acara/' . $save_file_name))->deleteFileAfterSend(false);

    }
}
