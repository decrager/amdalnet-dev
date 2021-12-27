<?php

namespace App\Http\Controllers;

use App\Entity\EligibilityCriteria;
use App\Entity\FeasibilityTest;
use App\Entity\FeasibilityTestDetail;
use App\Entity\Project;
use App\Utils\TemplateProcessor;
use Carbon\Carbon;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

class FeasibilityTestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->pdf) {
            return $this->exportPDF($request->idProject);
        }

        if($request->dokumen) {
           return $this->dokumen($request->idProject);
        }

        if($request->idProject) {
            $feasibility = FeasibilityTest::where('id_project', $request->idProject)->first();
            if($feasibility) {
                return $this->getExistData($request->idProject);
            } else {
                return $this->getFreshData($request->idProject);
            }
        }
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
        $data = $request->feasibility;

        $feasibility = null;
        
        if($data['type'] == 'new') {
            $feasibility = new FeasibilityTest();
            $feasibility->id_project = $data['idProject'];
        } else {
            $feasibility = FeasibilityTest::where('id_project', $data['idProject'])->first();
        }

        $feasibility->conclusion = $data['conclusion'];
        $feasibility->save();

        for($i = 0; $i < count($data['detail']); $i++) {

            $feasibility_detail = null;

            if($data['detail'][$i]['type'] == 'new') {
                $feasibility_detail = new FeasibilityTestDetail();
                $feasibility_detail->id_feasibility_test = $feasibility->id;
                $feasibility_detail->id_eligibility_criteria = $data['detail'][$i]['id'];
            } else {
                $feasibility_detail = FeasibilityTestDetail::where([['id_feasibility_test', $feasibility->id],['id_eligibility_criteria',$data['detail'][$i]['id']]])->first();
            }

            $feasibility_detail->appropriateness = $data['detail'][$i]['appropriateness'];
            $feasibility_detail->notes = $data['detail'][$i]['notes'];
            $feasibility_detail->expert_notes = $data['detail'][$i]['expert_notes'];
            $feasibility_detail->save();
        }

        return response()->json(['messsage' => 'success']);
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

    private function getExistData($idProject) {
        $feasibility = FeasibilityTest::where('id_project', $idProject)->first();

        $data = [];
        
        if($feasibility->detail->first()) {
            foreach($feasibility->detail as $d) {
                $data[] = [
                    'id' => $d->id_eligibility_criteria,
                    'description' => $d->eligibility->description,
                    'appropriateness' => $d->appropriateness,
                    'notes' => $d->notes,
                    'expert_notes' => $d->expert_notes,
                    'type' => 'update'
                ];
            }

            usort($data, function($a, $b) {
                return $a['id'] <=> $b['id'];
            });
        }

        $eligibility = EligibilityCriteria::whereDoesntHave('feasibility', function($q) use($feasibility) {
            $q->where('id_feasibility_test', $feasibility->id);
        });

        foreach($eligibility as $e) {
            $data[] = [
                'id' => $e->id,
                'description' => $e->description,
                'appropriateness' => null,
                'notes' => null,
                'expert_notes' => null,
                'type' => 'new'
            ]; 
        }

        return [
            'type' => 'update',
            'id' => $feasibility->id,
            'idProject' => $idProject,
            'conclusion' => $feasibility->conclusion,
            'detail' => $data
        ];
    }

    private function getFreshData($idProject) {
        $data = [];
        $eligibility = EligibilityCriteria::all();

        foreach($eligibility as $e) {
            $data[] = [
                'id' => $e->id,
                'description' => $e->description,
                'appropriateness' => null,
                'notes' => null,
                'expert_notes' => null,
                'type' => 'new'
            ]; 
        }

        return [
            'type' => 'new',
            'id' => null,
            'idProject' => $idProject,
            'conclusion' => null,
            'detail' => $data
        ];
    }

    private function dokumen($id_project) {
        if (!File::exists(storage_path('app/public/uji-kelayakan/'))) {
            File::makeDirectory(storage_path('app/public/uji-kelayakan/'));
        }

        
        Carbon::setLocale('id');
        $project = Project::findOrFail($id_project);
        $save_file_name = 'uji-kelayakan-' . strtolower($project->project_title) . '.docx';

        if(File::exists(storage_path('app/public/uji-kelayakan/' . $save_file_name))) {
            return $save_file_name;
        }

        $project_type = $project->project_type;
        $project_title_big = strtoupper($project->project_title);
        $project_address_big = '';
        $project_district_big = '';
        $project_province_big = '';
        $pemrakarsa_big = strtoupper($project->initiator->name);
        $project_title = ucwords(strtolower($project->project_title));
        $project_address = '';
        $project_district = '';
        $project_province = '';
        $pemrakarsa = $project->initiator->name;
        $pic = $project->initiator->pic;
        $pemrakarsa_address = $project->initiator->address;

        if($project->address) {
            if($project->address->first()) {
                $project_address = $project->address->first()->address;
                $project_district = ucwords(strtolower($project->address->first()->district));
                $project_province = 'Provinsi ' . ucwords(strtolower($project->address->first()->prov));
                $project_address_big = strtoupper($project->address->first()->address);
                $project_district_big = strtoupper($project->address->first()->district);
                $project_province_big = 'PROVINSI ' . strtoupper($project->address->first()->prov);
            }
        }

        $docs_date = Carbon::createFromFormat('Y-m-d', date('Y-m-d'))->isoFormat('D MMMM Y');

        $templateProcessor = new TemplateProcessor('template_kelayakan.docx');
        $templateProcessor->setValue('docs_date', $docs_date);
        $templateProcessor->setValue('project_title_big', $project_title_big);
        $templateProcessor->setValue('project_address_big', $project_address_big);
        $templateProcessor->setValue('project_district_big', $project_district_big);
        $templateProcessor->setValue('project_province_big', $project_province_big);
        $templateProcessor->setValue('pemrakarsa_big', $pemrakarsa_big);
        $templateProcessor->setValue('project_title', $project_title);
        $templateProcessor->setValue('project_address', $project_address);
        $templateProcessor->setValue('project_district', $project_district);
        $templateProcessor->setValue('project_province', $project_province);
        $templateProcessor->setValue('pemrakarsa', $pemrakarsa);
        $templateProcessor->setValue('project_type', $project_type);
        $templateProcessor->setValue('pic', $pic);
        $templateProcessor->setValue('pemrakarsa_address', $pemrakarsa_address);

        $templateProcessor->saveAs(storage_path('app/public/uji-kelayakan/' . $save_file_name));

        return $save_file_name;
    }

    private function exportPDF($id_project) {
        $domPdfPath = base_path('vendor/dompdf/dompdf');
        Settings::setPdfRendererPath($domPdfPath);
        Settings::setPdfRendererName('DomPDF');
        $project = Project::findOrFail($id_project);

        //Load word file
        $Content = IOFactory::load(storage_path('app/public/uji-kelayakan/' . 'uji-kelayakan-' . strtolower($project->project_title) . '.docx'));

        //Save it into PDF
        $PDFWriter = IOFactory::createWriter($Content, 'PDF');

        $PDFWriter->save(storage_path('app/public/uji-kelayakan/' . 'uji-kelayakan-' . strtolower($project->project_title) . '.pdf'));

        return response()->download(storage_path('app/public/uji-kelayakan/' . 'uji-kelayakan-' . strtolower($project->project_title) . '.pdf'))->deleteFileAfterSend(true);
    }
}
