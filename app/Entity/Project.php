<?php

namespace App\Entity;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use ZeroDaHero\LaravelWorkflow\Traits\WorkflowTrait;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Support\Facades\File;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Project extends Model implements Auditable
{
    use AuditableTrait;

    use SoftDeletes;

    use WorkflowTrait;

    // protected $fillable = [
    //     'project_title',
    //     'scale',
    //     'scale_unit',
    //     'authority',
    //     'project_type',
    //     'sector',
    //     'description',
    //     'id_applicant',
    //     'id_prov',
    //     'id_district',
    //     'address',
    //     'field',
    //     'location_desc',
    //     'risk_level',
    //     'project_year',
    //     'map',
    //     'map_scale',
    //     'map_scale_unit',
    //     'id_formulator_team',
    //     'announcement_letter',
    //     'kbli',
    //     'result_risk',
    //     'required_doc',
    //     'biz_type',
    //     'id_project',
    //     'type_formulator_team',
    //     'ktr',
    //     'id_lpjp',
    //     'pre_agreement',
    //     'pre_agreement_file',
    // ];

    protected $guarded = [];
    
    protected $appends = ['filling_date', 'submission_deadline', 'rkl_rpl_document', 'ukl_upl_document'];

    public function team()
    {
        return $this->hasOne(FormulatorTeam::class, 'id_project', 'id');
    }

    public function impactIdentifications()
    {
        return $this->hasMany(ImpactIdentification::class, 'id_project', 'id');
    }

    public function impactIdentificationsClone()
    {
        return $this->hasMany(ImpactIdentificationClone::class, 'id_project', 'id');
    }

    public function address()
    {
        return $this->hasMany(ProjectAddress::class, 'id_project', 'id');
    }

    public function authorities()
    {
        return $this->hasMany(ProjectAuthority::class, 'id_project', 'id');
    }

    public function listSubProject()
    {
        return $this->hasMany(SubProject::class, 'id_project', 'id');
    }

    public function testingMeeting()
    {
        return $this->hasMany(TestingMeeting::class, 'id_project', 'id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'id_district', 'id');
    }

    public function province()
    {
        return $this->hasOne(Province::class, 'id', 'id_prov');
    }

    public function initiator()
    {
        return $this->belongsTo(Initiator::class, 'id_applicant', 'id');
    }

    public function subProjects()
    {
        return $this->hasMany(SubProject::class, 'id_project', 'id');
    }

    public function mapFiles()
    {
        return $this->hasMany(ProjectMapAttachment::class, 'id_project', 'id');
    }

    public function feasibilityTest()
    {
        return $this->hasMany(FeasibilityTest::class, 'id_project', 'id');
    }

    public function kaReviews()
    {
        return $this->hasMany(KaReview::class, 'id_project', 'id');
    }

    public function skkl()
    {
        return $this->hasOne(ProjectSkkl::class, 'id_project', 'id');
    }

    public function meetingReports()
    {
        return $this->hasMany(MeetingReport::class, 'id_project', 'id');
    }

    public function tukProject()
    {
        return $this->hasMany(TukProject::class, 'id_project', 'id');
    }

    public function feasibilityTestRecap()
    {
        return $this->hasOne(FeasibilityTestRecap::class, 'id_project', 'id');
    }

    public function getFillingDateAttribute()
    {
        Carbon::setLocale('id');
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->isoFormat('D MMMM Y');
    }

    public function getSubmissionDeadlineAttribute()
    {
        Carbon::setLocale('id');
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->addMonth(1)->isoFormat('D MMMM Y');
    }

    public function getRklRplDocumentAttribute()
    {
        if (!File::exists(storage_path('app/public/workspace/'))) {
            return false;
        }

        $save_file_name = $this->id . '-rkl-rpl' . '.docx';

        if (File::exists(storage_path('app/public/workspace/' . $save_file_name))) {
            return true;
        }

        return false;
    }

    public function getUklUplDocumentAttribute()
    {
        if (!File::exists(storage_path('app/public/workspace/'))) {
            return false;
        }

        $save_file_name = 'ukl-upl-' . strtolower(str_replace('/', '-', $this->project_title)) . '.docx';

        if (File::exists(storage_path('app/public/workspace/' . $save_file_name))) {
            return true;
        }

        return false;
    }
}
