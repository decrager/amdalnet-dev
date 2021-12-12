<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpertBank extends Model
{
    use SoftDeletes;

    protected $table = 'expert_bank';

    protected $fillable = [
        'name',
        'address',
        'email',
        'mobile_phone_no',
        'expertise',
        'institution',
        'status',
        'cv_file',
        'cert_luk_file',
        'cert_non_luk_file',
        'ijazah_file',
    ];

    public function expertBankTeamMember()
    {
        return $this->hasMany(ExpertBankTeamMember::class, 'id_expert_bank', 'id');
    }
}
