<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\TemplateProcessor;

class ExportDocument extends Controller
{
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
}
