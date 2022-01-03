<?php

namespace App\Http\Controllers;

use App\Entity\Announcement;
use App\Entity\Comment;
use App\Entity\EnvManageDoc;
use App\Entity\FeasibilityTest;
use App\Entity\Feedback;
use App\Entity\ImpactIdentification;
use App\Entity\ImpactStudy;
use App\Entity\Project;
use App\Entity\ProjectSkkl;
use App\Entity\TestingVerification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class TrackingDocumentController extends Controller
{    
    public function index($id) {
        $project = Project::with('address')
            ->where('id', $id)
            ->first();
        if ($project == null) {
            return response()->json([
                'status' => 404,
                'code' => 404,
                'message' => 'Project not found.',
            ], 404);
        }
        // get timeline based on required_doc
        try {
            $project->{"timeline"} = $this->getTimeline($project);
            return response()->json([
                'status' => 200,
                'code' => 200,
                'data' => $project,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'code' => 500,
                'error' => 'Gagal memuat tracking dokumen.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    private function translateDay($dayEN) {
        $mapping = [
            'Mon' => 'Senin',
            'Tue' => 'Selasa',
            'Wed' => 'Rabu',
            'Thu' => 'Kamis',
            'Fri' => 'Jumat',
            'Sat' => 'Sabtu',
            'Sun' => 'Minggu',
        ];
        return $mapping[$dayEN];
    }

    private function formatDateWIB($ts) {
        $dayEN = date('D', strtotime($ts));
        $f = date('D, d M Y H:i', strtotime($ts)) . ' WIB';
        return str_replace($dayEN, $this->translateDay($dayEN), $f);
    }

    
    private function getTimeline($project) {
        $timeline = [];
        array_push($timeline, [
            'content' => 'Hasil Penapisan',
            'timestamp' => $this->formatDateWIB($project->created_at),
            'size' => 'large',
            'icon' => 'el-icon-success',
            'color' => 'green',
        ]);
        $announcement = Announcement::select('*')
            ->where('project_id', $project->id)
            ->first();
        $feedbacks = [];
        if ($announcement != null) {
            $feedbacks = Feedback::select('*')
                ->where('announcement_id', $announcement->id)
                ->orderBy('created_at', 'asc')
                ->get();
            if (count($feedbacks) > 0) {
                array_push($timeline, [
                    'content' => 'SPT dari Masyarakat',
                    'timestamp' => $this->formatDateWIB($feedbacks[0]->created_at),
                    'size' => 'large',
                    'icon' => 'el-icon-success',
                    'color' => 'green',
                ]);
            } else {
                array_push($timeline, [
                    'content' => 'SPT dari Masyarakat',
                    'timestamp' => null,
                    'size' => 'large',
                    'icon' => 'el-icon-edit',
                    'color' => 'orange',
                ]);
            }
        }
        if (count($feedbacks) > 0) {
            if ($project->required_doc == 'AMDAL') {
                $timeline = $this->getTimelineAmdal($project, $timeline);
            } else if (in_array($project->required_doc, ['UKL-UPL', 'SPPL'])) {
                $timeline = $this->getTimelineUklUpl($project, $timeline);
            }
        }
        return $timeline;
    }

    private function getTimelineAmdal($project, $timeline) {
        $amdalTimeline = [];
        $impacts = ImpactIdentification::with('impactStudy')
            ->where('id_project', $project->id)
            ->orderBy('created_at', 'asc')
            ->get();
        if (count($impacts) > 0) {
            $listComment = [];
            $formulirKAComplete = false;
            $impactStudies = [];
            foreach ($impacts as $impact) {
                // metode studi
                if ($impact->impactStudy != null) {
                    array_push($impactStudies, $impact->impactStudy);
                }
                // comment for ANDAL, RKL-RPL
                $comments = Comment::select('*')
                    ->where('id_impact_identification', $impact->id)
                    ->orderBy('created_at', 'asc')
                    ->get();
                foreach ($comments as $comment) {
                    array_push($listComment, $comment);
                }
            }
            $lastImpactStudy = null;
            if (count($impactStudies) == count($impacts)) {
                $formulirKAComplete = true;
                $len = count($impactStudies);
                $lastImpactStudy = $impactStudies[$len-1];
            }
            $commentGrouped = [];
            $commentGrouped['andal'] = [];
            $commentGrouped['rkl'] = [];
            $commentGrouped['rpl'] = [];
            foreach ($listComment as $comment) {
                if ($comment->document_type == 'andal') {
                    array_push($commentGrouped['andal'], $comment);
                } else if ($comment->document_type == 'rkl') {
                    array_push($commentGrouped['rkl'], $comment);
                } else if ($comment->document_type == 'rpl') {
                    array_push($commentGrouped['rpl'], $comment);
                }
            }
            // AMDAL
            // Formulir KA
            if ($formulirKAComplete) {
                array_push($amdalTimeline, [
                    'content' => 'Formulir KA',
                    'timestamp' => $this->formatDateWIB($lastImpactStudy->created_at),
                    'size' => 'large',
                    'icon' => 'el-icon-success',
                    'color' => 'green',
                ]);
            } else {
                array_push($amdalTimeline, [
                    'content' => 'Formulir KA',
                    'timestamp' => null,
                    'size' => 'large',
                    'icon' => 'el-icon-edit',
                    'color' => 'orange',
                ]);
            }
            // Penyusunan ANDAL
            $andal = false;
            if (count($commentGrouped['andal']) > 0) {
                $andal = true;
                array_push($amdalTimeline, [
                    'content' => 'Penyusunan ANDAL',
                    'timestamp' => $this->formatDateWIB($commentGrouped['andal'][0]->created_at),
                    'size' => 'large',
                    'icon' => 'el-icon-success',
                    'color' => 'green',
                ]);
            } else if ($formulirKAComplete) {
                array_push($amdalTimeline, [
                    'content' => 'Penyusunan ANDAL',
                    'timestamp' => null,
                    'size' => 'large',
                    'icon' => 'el-icon-edit',
                    'color' => 'orange',
                ]);
            }
            // Penyusunan RKL RPL
            if ($andal) {
                if (count($commentGrouped['rkl']) > 0) {
                    array_push($amdalTimeline, [
                        'content' => 'Penyusunan RKL RPL',
                        'timestamp' => $this->formatDateWIB($commentGrouped['rkl'][0]->created_at),
                        'size' => 'large',
                        'icon' => 'el-icon-success',
                        'color' => 'green',
                    ]);
                } else {
                    array_push($amdalTimeline, [
                        'content' => 'Penyusunan RKL RPL',
                        'timestamp' => null,
                        'size' => 'large',
                        'icon' => 'el-icon-edit',
                        'color' => 'orange',
                    ]);
                }
            }
            // Pengujian RKL RPL (table: testing_verifications (start), feasibility_tests (end))
            $pengujianComplete = false;
            $ver = TestingVerification::select('*')
                ->where('id_project', $project->id)
                ->orderBy('created_at', 'asc')
                ->get();
            $fTest = FeasibilityTest::select('*')
                ->where('id_project', $project->id)
                ->orderBy('created_at', 'asc')
                ->get();
            
            if ($andal && count($fTest) > 0) {
                $pengujianComplete = true;
                array_push($amdalTimeline, [
                    'content' => 'Pengujian',
                    'timestamp' => $this->formatDateWIB($fTest[0]->created_at),
                    'size' => 'large',
                    'icon' => 'el-icon-success',
                    'color' => 'green',
                ]);
            } else if ($andal && count($ver) > 0) {
                array_push($amdalTimeline, [
                    'content' => 'Pengujian',
                    'timestamp' => null,
                    'size' => 'large',
                    'icon' => 'el-icon-edit',
                    'color' => 'orange',
                ]);
            }
            // Surat Keputusan (start: pengujianComplete=true, end: project_skkl)
            $skkl = ProjectSkkl::select('*')
                ->where('id_project', $project->id)
                ->orderBy('created_at', 'asc')
                ->get();
            if ($pengujianComplete && count($skkl) > 0) {
                array_push($amdalTimeline, [
                    'content' => 'Surat Keputusan',
                    'timestamp' => $this->formatDateWIB($skkl[0]->created_at),
                    'size' => 'large',
                    'icon' => 'el-icon-success',
                    'color' => 'green',
                ]);
            } else if ($pengujianComplete) {
                array_push($amdalTimeline, [
                    'content' => 'Surat Keputusan',
                    'timestamp' => null,
                    'size' => 'large',
                    'icon' => 'el-icon-edit',
                    'color' => 'orange',
                ]);
            }
        }
        return array_merge($timeline, $amdalTimeline);
    }

    private function getTimelineUklUpl($project, $timeline) {
        $uklUplTimeline = [];
        $impacts = ImpactIdentification::with('impactStudy')
            ->where('id_project', $project->id)
            ->orderBy('created_at', 'asc')
            ->get();
        // Formulir UKL UPL (start: impact_identifications, end: check if all unit is filled)
        $unitFilled = 0;
        $formulirComplete = false;
        if (count($impacts) > 0) {
            foreach ($impacts as $impact) {
                if (empty($impact->unit) && empty($impact->id_unit)) {
                } else {
                    $unitFilled++;
                }
            }
            $formulirComplete = $unitFilled == count($impacts);
        }
        if ($formulirComplete) {
            $len = count($impacts);
            array_push($uklUplTimeline, [
                'content' => 'Formulir UKL UPL',
                'timestamp' => $this->formatDateWIB($impacts[$len-1]->created_at),
                'size' => 'large',
                'icon' => 'el-icon-success',
                'color' => 'green',
            ]);
        } else {
            array_push($uklUplTimeline, [
                'content' => 'Formulir UKL UPL',
                'timestamp' => null,
                'size' => 'large',
                'icon' => 'el-icon-edit',
                'color' => 'orange',
            ]);
        }
        // Matriks UKL UPL (start: formulirComplete=true, end: have 2 env_manage_docs: SPPL, DPT)
        $docs = EnvManageDoc::select('*')
            ->where('id_project', $project->id)
            ->orderBy('created_at', 'asc')
            ->get();
        $sppl = false;
        $dpt = false;
        if (count($docs) > 0) {
            foreach ($docs as $doc) {
                if ($doc->type == 'SPPL') {
                    $sppl = true;
                }
                if ($doc->type == 'DPT') {
                    $dpt = true;
                }
            }
            if ($sppl && $dpt) {
                $len = count($docs);
                array_push($uklUplTimeline, [
                    'content' => 'Matriks UKL UPL',
                    'timestamp' => $this->formatDateWIB($docs[$len-1]->created_at),
                    'size' => 'large',
                    'icon' => 'el-icon-success',
                    'color' => 'green',
                ]);
            } else {                
                array_push($uklUplTimeline, [
                    'content' => 'Matriks UKL UPL',
                    'timestamp' => null,
                    'size' => 'large',
                    'icon' => 'el-icon-edit',
                    'color' => 'orange',
                ]);
            }
        }
        // Dokumen UKL UPL (start: matriksComplete=true, end: check if document file exists with this id_project)
        $dokumenComplete = false;
        $docFilepath = 'app/public/ukl-upl/ukl-upl-' . strtolower($project->project_title) . '.docx';
        if ($sppl && $dpt && File::exists(storage_path($docFilepath))) {
            $dokumenComplete = true;
            $createdTime = date('Y-m-d H:i:s', filectime(storage_path($docFilepath)));
            array_push($uklUplTimeline, [
                'content' => 'Dokumen UKL UPL',
                'timestamp' => $this->formatDateWIB($createdTime),
                'size' => 'large',
                'icon' => 'el-icon-success',
                'color' => 'green',
            ]);
        } else if ($sppl && $dpt) {            
            array_push($uklUplTimeline, [
                'content' => 'Dokumen UKL UPL',
                'timestamp' => null,
                'size' => 'large',
                'icon' => 'el-icon-edit',
                'color' => 'orange',
            ]);
        }
        // Surat Keputusan (start: dokumenComplete=true, end: project_skkl)
        $skkl = ProjectSkkl::select('*')
            ->where('id_project', $project->id)
            ->orderBy('created_at', 'asc')
            ->get();
        if ($dokumenComplete && count($skkl) > 0) {
            array_push($uklUplTimeline, [
                'content' => 'Surat Keputusan',
                'timestamp' => $this->formatDateWIB($skkl[0]->created_at),
                'size' => 'large',
                'icon' => 'el-icon-success',
                'color' => 'green',
            ]);
        } else if ($dokumenComplete) {            
            array_push($uklUplTimeline, [
                'content' => 'Surat Keputusan',
                'timestamp' => null,
                'size' => 'large',
                'icon' => 'el-icon-edit',
                'color' => 'orange',
            ]);
        }
        return array_merge($timeline, $uklUplTimeline);
    }
    
}
