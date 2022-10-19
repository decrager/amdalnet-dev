<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App{
/**
 * App\Entity
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Entity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Entity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Entity query()
 */
	class Entity extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\AndalAttachment
 *
 * @property int $id
 * @property int|null $id_project
 * @property string|null $name
 * @property string|null $file
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property bool $is_pertek
 * @property bool $is_andal
 * @method static \Illuminate\Database\Eloquent\Builder|AndalAttachment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AndalAttachment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AndalAttachment query()
 * @method static \Illuminate\Database\Eloquent\Builder|AndalAttachment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AndalAttachment whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AndalAttachment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AndalAttachment whereIdProject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AndalAttachment whereIsAndal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AndalAttachment whereIsPertek($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AndalAttachment whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AndalAttachment whereUpdatedAt($value)
 */
	class AndalAttachment extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\AndalComment
 *
 * @property int $id
 * @property int $id_project
 * @property int $id_user
 * @property string $comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|AndalComment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AndalComment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AndalComment query()
 * @method static \Illuminate\Database\Eloquent\Builder|AndalComment whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AndalComment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AndalComment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AndalComment whereIdProject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AndalComment whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AndalComment whereUpdatedAt($value)
 */
	class AndalComment extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\Announcement
 *
 * @property int $id
 * @property string $pic_name
 * @property int $project_id
 * @property string $project_result
 * @property string $pic_address
 * @property string $cs_name
 * @property string $cs_address
 * @property string $project_type
 * @property string $project_location
 * @property string $project_scale
 * @property string $proof
 * @property string $potential_impact
 * @property string $start_date
 * @property string $end_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $id_applicant
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\Feedback[] $feedbacks
 * @property-read int|null $feedbacks_count
 * @property-read \App\Entity\Initiator|null $initiator
 * @property-read \App\Entity\Project|null $project
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement query()
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereCsAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereCsName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereIdApplicant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement wherePicAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement wherePicName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement wherePotentialImpact($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereProjectLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereProjectResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereProjectScale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereProjectType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereProof($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereUpdatedAt($value)
 */
	class Announcement extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\AppParam
 *
 * @property int $id
 * @property string $parameter_name
 * @property string $title
 * @property int $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property bool $deleted
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $is_numeric
 * @method static \Illuminate\Database\Eloquent\Builder|AppParam newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AppParam newQuery()
 * @method static \Illuminate\Database\Query\Builder|AppParam onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|AppParam query()
 * @method static \Illuminate\Database\Eloquent\Builder|AppParam whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppParam whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppParam whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppParam whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppParam whereIsNumeric($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppParam whereParameterName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppParam whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppParam whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppParam whereValue($value)
 * @method static \Illuminate\Database\Query\Builder|AppParam withTrashed()
 * @method static \Illuminate\Database\Query\Builder|AppParam withoutTrashed()
 */
	class AppParam extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\ArcgisService
 *
 * @property int $id
 * @property int $id_category
 * @property string $url_service
 * @property string $source
 * @property string|null $organization
 * @property int $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $is_proxy
 * @property string $name
 * @property int|null $id_province
 * @property-read \App\Entity\ArcgisServiceCategory|null $category
 * @method static \Illuminate\Database\Eloquent\Builder|ArcgisService newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArcgisService newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArcgisService query()
 * @method static \Illuminate\Database\Eloquent\Builder|ArcgisService whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArcgisService whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArcgisService whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArcgisService whereIdCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArcgisService whereIdProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArcgisService whereIsProxy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArcgisService whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArcgisService whereOrganization($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArcgisService whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArcgisService whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArcgisService whereUrlService($value)
 */
	class ArcgisService extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\ArcgisServiceCategory
 *
 * @property int $id
 * @property string $category_name
 * @property int $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $id_provinsi
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\ArcgisService[] $arcgisServices
 * @property-read int|null $arcgis_services_count
 * @method static \Illuminate\Database\Eloquent\Builder|ArcgisServiceCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArcgisServiceCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArcgisServiceCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|ArcgisServiceCategory whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArcgisServiceCategory whereCategoryName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArcgisServiceCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArcgisServiceCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArcgisServiceCategory whereIdProvinsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArcgisServiceCategory whereUpdatedAt($value)
 */
	class ArcgisServiceCategory extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\Authority
 *
 * @property int $id
 * @property string|null $sector
 * @property string|null $project
 * @property string|null $authority
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Authority newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Authority newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Authority query()
 * @method static \Illuminate\Database\Eloquent\Builder|Authority whereAuthority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Authority whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Authority whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Authority whereProject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Authority whereSector($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Authority whereUpdatedAt($value)
 */
	class Authority extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\BlogPost
 *
 * @property int $id
 * @property string $title
 * @property string $content
 * @property string|null $marking
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\BlogPostFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPost newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPost newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPost query()
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPost whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPost whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPost whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPost whereMarking($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPost whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPost whereUpdatedAt($value)
 */
	class BlogPost extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\Business
 *
 * @property int $id
 * @property int $parent_id
 * @property string $value
 * @property string $description
 * @property string|null $hash
 * @method static \Illuminate\Database\Eloquent\Builder|Business newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Business newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Business query()
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereValue($value)
 */
	class Business extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\BusinessEnvParam
 *
 * @property int $id
 * @property int|null $business_id
 * @property int|null $id_param
 * @property string|null $condition
 * @property int|null $id_unit
 * @property string|null $doc_req
 * @property string|null $amdal_type
 * @property string|null $risk_level
 * @property string|null $is_param_multisector
 * @property-read \App\Entity\Business|null $business
 * @property-read \App\Entity\Param|null $param
 * @property-read \App\Entity\Unit|null $unit
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessEnvParam newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessEnvParam newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessEnvParam query()
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessEnvParam whereAmdalType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessEnvParam whereBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessEnvParam whereCondition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessEnvParam whereDocReq($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessEnvParam whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessEnvParam whereIdParam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessEnvParam whereIdUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessEnvParam whereIsParamMultisector($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessEnvParam whereRiskLevel($value)
 */
	class BusinessEnvParam extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\BusinessEnvParamsTemp
 *
 * @property int $id
 * @property int|null $business_id
 * @property string|null $condition
 * @property string|null $doc_req
 * @property string|null $amdal_type
 * @property string|null $risk_level
 * @property string|null $is_param_multisector
 * @property int|null $id_unit
 * @property int|null $id_param
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessEnvParamsTemp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessEnvParamsTemp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessEnvParamsTemp query()
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessEnvParamsTemp whereAmdalType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessEnvParamsTemp whereBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessEnvParamsTemp whereCondition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessEnvParamsTemp whereDocReq($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessEnvParamsTemp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessEnvParamsTemp whereIdParam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessEnvParamsTemp whereIdUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessEnvParamsTemp whereIsParamMultisector($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessEnvParamsTemp whereRiskLevel($value)
 */
	class BusinessEnvParamsTemp extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\ChangeType
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property bool $is_primary
 * @method static \Illuminate\Database\Eloquent\Builder|ChangeType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChangeType newQuery()
 * @method static \Illuminate\Database\Query\Builder|ChangeType onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ChangeType query()
 * @method static \Illuminate\Database\Eloquent\Builder|ChangeType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChangeType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChangeType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChangeType whereIsPrimary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChangeType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChangeType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|ChangeType withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ChangeType withoutTrashed()
 */
	class ChangeType extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\Comment
 *
 * @property int $id
 * @property int|null $id_user
 * @property int|null $id_impact_identification
 * @property string|null $description
 * @property string|null $document_type
 * @property bool|null $is_checked
 * @property int|null $reply_to
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $column_type
 * @property int|null $id_stage
 * @property int|null $id_project
 * @property int|null $id_impact_identification_clone
 * @property-read \App\Entity\ImpactIdentification|null $impactIdentification
 * @property-read \App\Entity\ImpactIdentificationClone|null $impactIdentificationClone
 * @property-read Comment|null $replied
 * @property-read \Illuminate\Database\Eloquent\Collection|Comment[] $reply
 * @property-read int|null $reply_count
 * @property-read \App\Entity\ProjectStage|null $stage
 * @property-read \App\Laravue\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereColumnType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereDocumentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereIdImpactIdentification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereIdImpactIdentificationClone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereIdProject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereIdStage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereIsChecked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereReplyTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereUpdatedAt($value)
 */
	class Comment extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\Component
 *
 * @property int $id
 * @property string|null $name
 * @property int $id_project_stage
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $definition
 * @property bool $is_master
 * @property int|null $originator_id
 * @property-read \App\Entity\ProjectStage|null $stage
 * @method static \Illuminate\Database\Eloquent\Builder|Component master()
 * @method static \Illuminate\Database\Eloquent\Builder|Component newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Component newQuery()
 * @method static \Illuminate\Database\Query\Builder|Component onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Component query()
 * @method static \Illuminate\Database\Eloquent\Builder|Component whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Component whereDefinition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Component whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Component whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Component whereIdProjectStage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Component whereIsMaster($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Component whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Component whereOriginatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Component whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Component withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Component withoutTrashed()
 */
	class Component extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\ComponentType
 *
 * @property int $id
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ComponentType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ComponentType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ComponentType query()
 * @method static \Illuminate\Database\Eloquent\Builder|ComponentType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComponentType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComponentType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComponentType whereUpdatedAt($value)
 */
	class ComponentType extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\District
 *
 * @property int $id
 * @property int $id_prov
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\Lpjp[] $lpjp
 * @property-read int|null $lpjp_count
 * @method static \Illuminate\Database\Eloquent\Builder|District newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|District newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|District query()
 * @method static \Illuminate\Database\Eloquent\Builder|District whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|District whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|District whereIdProv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|District whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|District whereUpdatedAt($value)
 */
	class District extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\DocumentAttachment
 *
 * @property int $id
 * @property int $id_project
 * @property string $attachment
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentAttachment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentAttachment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentAttachment query()
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentAttachment whereAttachment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentAttachment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentAttachment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentAttachment whereIdProject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentAttachment whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentAttachment whereUpdatedAt($value)
 */
	class DocumentAttachment extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\EligibilityCriteria
 *
 * @property int $id
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\FeasibilityTestDetail[] $feasibility
 * @property-read int|null $feasibility_count
 * @method static \Illuminate\Database\Eloquent\Builder|EligibilityCriteria newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EligibilityCriteria newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EligibilityCriteria query()
 * @method static \Illuminate\Database\Eloquent\Builder|EligibilityCriteria whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EligibilityCriteria whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EligibilityCriteria whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EligibilityCriteria whereUpdatedAt($value)
 */
	class EligibilityCriteria extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\EnvImpactAnalysis
 *
 * @property int $id
 * @property int|null $id_impact_identifications
 * @property string|null $impact_size
 * @property string|null $impact_eval_result
 * @property string|null $response
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $impact_type
 * @property string|null $studies_condition
 * @property string|null $condition_dev_no_plan
 * @property string|null $condition_dev_with_plan
 * @property string|null $impact_size_difference
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\SignificantImpactFlowchart[] $child
 * @property-read int|null $child_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\ImpactAnalysisDetail[] $detail
 * @property-read int|null $detail_count
 * @property-read \App\Entity\ImpactIdentificationClone|null $impactIdentification
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\SignificantImpactFlowchart[] $parent
 * @property-read int|null $parent_count
 * @method static \Illuminate\Database\Eloquent\Builder|EnvImpactAnalysis newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EnvImpactAnalysis newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EnvImpactAnalysis query()
 * @method static \Illuminate\Database\Eloquent\Builder|EnvImpactAnalysis whereConditionDevNoPlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvImpactAnalysis whereConditionDevWithPlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvImpactAnalysis whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvImpactAnalysis whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvImpactAnalysis whereIdImpactIdentifications($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvImpactAnalysis whereImpactEvalResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvImpactAnalysis whereImpactSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvImpactAnalysis whereImpactSizeDifference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvImpactAnalysis whereImpactType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvImpactAnalysis whereResponse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvImpactAnalysis whereStudiesCondition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvImpactAnalysis whereUpdatedAt($value)
 */
	class EnvImpactAnalysis extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\EnvManagamentSop
 *
 * @property int $id
 * @property string $sop_type
 * @property string $file
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|EnvManagamentSop newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EnvManagamentSop newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EnvManagamentSop query()
 * @method static \Illuminate\Database\Eloquent\Builder|EnvManagamentSop whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvManagamentSop whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvManagamentSop whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvManagamentSop whereSopType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvManagamentSop whereUpdatedAt($value)
 */
	class EnvManagamentSop extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\EnvManageApproach
 *
 * @property int $id
 * @property int|null $id_project
 * @property string|null $approach_type
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|EnvManageApproach newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EnvManageApproach newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EnvManageApproach query()
 * @method static \Illuminate\Database\Eloquent\Builder|EnvManageApproach whereApproachType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvManageApproach whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvManageApproach whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvManageApproach whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvManageApproach whereIdProject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvManageApproach whereUpdatedAt($value)
 */
	class EnvManageApproach extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\EnvManageDoc
 *
 * @property int $id
 * @property int $id_project
 * @property string $type
 * @property string $filepath
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $name
 * @property-read \App\Entity\Project $project
 * @method static \Illuminate\Database\Eloquent\Builder|EnvManageDoc newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EnvManageDoc newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EnvManageDoc query()
 * @method static \Illuminate\Database\Eloquent\Builder|EnvManageDoc whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvManageDoc whereFilepath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvManageDoc whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvManageDoc whereIdProject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvManageDoc whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvManageDoc whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvManageDoc whereUpdatedAt($value)
 */
	class EnvManageDoc extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\EnvManagePlan
 *
 * @property int $id
 * @property int $id_impact_identifications
 * @property string|null $success_indicator
 * @property string|null $form
 * @property string|null $location
 * @property string|null $period
 * @property string|null $institution
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $impact_source
 * @property string|null $executor
 * @property string|null $supervisor
 * @property string|null $report_recipient
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\EnvPlanForm[] $forms
 * @property-read int|null $forms_count
 * @property-read \App\Entity\ImpactIdentificationClone|null $impactIdentification
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\EnvPlanIndicator[] $indicators
 * @property-read int|null $indicators_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\EnvPlanLocation[] $locations
 * @property-read int|null $locations_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\EnvPlanSource[] $sources
 * @property-read int|null $sources_count
 * @method static \Illuminate\Database\Eloquent\Builder|EnvManagePlan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EnvManagePlan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EnvManagePlan query()
 * @method static \Illuminate\Database\Eloquent\Builder|EnvManagePlan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvManagePlan whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvManagePlan whereExecutor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvManagePlan whereForm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvManagePlan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvManagePlan whereIdImpactIdentifications($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvManagePlan whereImpactSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvManagePlan whereInstitution($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvManagePlan whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvManagePlan wherePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvManagePlan whereReportRecipient($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvManagePlan whereSuccessIndicator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvManagePlan whereSupervisor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvManagePlan whereUpdatedAt($value)
 */
	class EnvManagePlan extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\EnvMonitorPlan
 *
 * @property int $id
 * @property int $id_impact_identifications
 * @property string|null $collection_method
 * @property string|null $location
 * @property string|null $time_frequent
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $indicator
 * @property string|null $impact_source
 * @property string|null $executor
 * @property string|null $supervisor
 * @property string|null $report_recipient
 * @property string|null $form
 * @property string|null $period
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\EnvPlanForm[] $forms
 * @property-read int|null $forms_count
 * @property-read \App\Entity\ImpactIdentificationClone|null $impactIdentification
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\EnvPlanIndicator[] $indicators
 * @property-read int|null $indicators_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\EnvPlanLocation[] $locations
 * @property-read int|null $locations_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\EnvPlanSource[] $sources
 * @property-read int|null $sources_count
 * @method static \Illuminate\Database\Eloquent\Builder|EnvMonitorPlan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EnvMonitorPlan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EnvMonitorPlan query()
 * @method static \Illuminate\Database\Eloquent\Builder|EnvMonitorPlan whereCollectionMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvMonitorPlan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvMonitorPlan whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvMonitorPlan whereExecutor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvMonitorPlan whereForm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvMonitorPlan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvMonitorPlan whereIdImpactIdentifications($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvMonitorPlan whereImpactSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvMonitorPlan whereIndicator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvMonitorPlan whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvMonitorPlan wherePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvMonitorPlan whereReportRecipient($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvMonitorPlan whereSupervisor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvMonitorPlan whereTimeFrequent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvMonitorPlan whereUpdatedAt($value)
 */
	class EnvMonitorPlan extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\EnvParam
 *
 * @property int $id
 * @property int|null $kbli_id
 * @property int|null $id_param
 * @property string|null $condition
 * @property int|null $id_unit
 * @property string|null $doc_req
 * @property string|null $amdal_type
 * @property string|null $risk_level
 * @property string|null $is_param_multisector
 * @property-read \App\Entity\Param|null $param
 * @property-read \App\Entity\Unit|null $unit
 * @method static \Illuminate\Database\Eloquent\Builder|EnvParam newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EnvParam newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EnvParam query()
 * @method static \Illuminate\Database\Eloquent\Builder|EnvParam whereAmdalType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvParam whereCondition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvParam whereDocReq($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvParam whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvParam whereIdParam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvParam whereIdUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvParam whereIsParamMultisector($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvParam whereKbliId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvParam whereRiskLevel($value)
 */
	class EnvParam extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\EnvPlanForm
 *
 * @property int $id
 * @property int|null $id_env_manage_plan
 * @property int|null $id_env_monitor_plan
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|EnvPlanForm newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EnvPlanForm newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EnvPlanForm query()
 * @method static \Illuminate\Database\Eloquent\Builder|EnvPlanForm whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvPlanForm whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvPlanForm whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvPlanForm whereIdEnvManagePlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvPlanForm whereIdEnvMonitorPlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvPlanForm whereUpdatedAt($value)
 */
	class EnvPlanForm extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\EnvPlanIndicator
 *
 * @property int $id
 * @property int|null $id_env_manage_plan
 * @property int|null $id_env_monitor_plan
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $id_impact_identification
 * @property-read \App\Entity\ImpactIdentificationClone|null $impactIdentification
 * @method static \Illuminate\Database\Eloquent\Builder|EnvPlanIndicator newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EnvPlanIndicator newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EnvPlanIndicator query()
 * @method static \Illuminate\Database\Eloquent\Builder|EnvPlanIndicator whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvPlanIndicator whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvPlanIndicator whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvPlanIndicator whereIdEnvManagePlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvPlanIndicator whereIdEnvMonitorPlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvPlanIndicator whereIdImpactIdentification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvPlanIndicator whereUpdatedAt($value)
 */
	class EnvPlanIndicator extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\EnvPlanInstitution
 *
 * @property int $id
 * @property int|null $id_impact_identification
 * @property string|null $executor
 * @property string|null $supervisor
 * @property string|null $report_recipient
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Entity\ImpactIdentificationClone|null $impactIdentification
 * @method static \Illuminate\Database\Eloquent\Builder|EnvPlanInstitution newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EnvPlanInstitution newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EnvPlanInstitution query()
 * @method static \Illuminate\Database\Eloquent\Builder|EnvPlanInstitution whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvPlanInstitution whereExecutor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvPlanInstitution whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvPlanInstitution whereIdImpactIdentification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvPlanInstitution whereReportRecipient($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvPlanInstitution whereSupervisor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvPlanInstitution whereUpdatedAt($value)
 */
	class EnvPlanInstitution extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\EnvPlanLocation
 *
 * @property int $id
 * @property int|null $id_env_manage_plan
 * @property int|null $id_env_monitor_plan
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|EnvPlanLocation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EnvPlanLocation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EnvPlanLocation query()
 * @method static \Illuminate\Database\Eloquent\Builder|EnvPlanLocation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvPlanLocation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvPlanLocation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvPlanLocation whereIdEnvManagePlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvPlanLocation whereIdEnvMonitorPlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvPlanLocation whereUpdatedAt($value)
 */
	class EnvPlanLocation extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\EnvPlanSource
 *
 * @property int $id
 * @property int|null $id_env_manage_plan
 * @property int|null $id_env_monitor_plan
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $id_impact_identification
 * @property-read \App\Entity\ImpactIdentificationClone|null $impactIdentification
 * @method static \Illuminate\Database\Eloquent\Builder|EnvPlanSource newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EnvPlanSource newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EnvPlanSource query()
 * @method static \Illuminate\Database\Eloquent\Builder|EnvPlanSource whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvPlanSource whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvPlanSource whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvPlanSource whereIdEnvManagePlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvPlanSource whereIdEnvMonitorPlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvPlanSource whereIdImpactIdentification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvPlanSource whereUpdatedAt($value)
 */
	class EnvPlanSource extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\EnvironmentalApproval
 *
 * @property int $id
 * @property string $template_type
 * @property string $description
 * @property string $file
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|EnvironmentalApproval newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EnvironmentalApproval newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EnvironmentalApproval query()
 * @method static \Illuminate\Database\Eloquent\Builder|EnvironmentalApproval whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvironmentalApproval whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvironmentalApproval whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvironmentalApproval whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvironmentalApproval whereTemplateType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvironmentalApproval whereUpdatedAt($value)
 */
	class EnvironmentalApproval extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\EnvironmentalExpert
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $status
 * @property string|null $expertise
 * @property string|null $cv
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\FormulatorTeamMember[] $teamMembers
 * @property-read int|null $team_members_count
 * @method static \Illuminate\Database\Eloquent\Builder|EnvironmentalExpert newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EnvironmentalExpert newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EnvironmentalExpert query()
 * @method static \Illuminate\Database\Eloquent\Builder|EnvironmentalExpert whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvironmentalExpert whereCv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvironmentalExpert whereExpertise($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvironmentalExpert whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvironmentalExpert whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvironmentalExpert whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvironmentalExpert whereUpdatedAt($value)
 */
	class EnvironmentalExpert extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\EnvironmentalPermit
 *
 * @property int $id
 * @property string|null $pemarkasa_name
 * @property string $authority
 * @property string|null $kegiatan_name
 * @property string|null $sk_number
 * @property string|null $date
 * @property string|null $publisher
 * @property string|null $file
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $document_type
 * @method static \Illuminate\Database\Eloquent\Builder|EnvironmentalPermit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EnvironmentalPermit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EnvironmentalPermit query()
 * @method static \Illuminate\Database\Eloquent\Builder|EnvironmentalPermit whereAuthority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvironmentalPermit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvironmentalPermit whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvironmentalPermit whereDocumentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvironmentalPermit whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvironmentalPermit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvironmentalPermit whereKegiatanName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvironmentalPermit wherePemarkasaName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvironmentalPermit wherePublisher($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvironmentalPermit whereSkNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnvironmentalPermit whereUpdatedAt($value)
 */
	class EnvironmentalPermit extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\ExpertBank
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $address
 * @property string|null $email
 * @property string|null $mobile_phone_no
 * @property string|null $expertise
 * @property string|null $institution
 * @property string|null $cv_file
 * @property string|null $cert_luk_file
 * @property string|null $cert_non_luk_file
 * @property string|null $ijazah_file
 * @property bool|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\ExpertBankTeamMember[] $expertBankTeamMember
 * @property-read int|null $expert_bank_team_member_count
 * @method static \Illuminate\Database\Eloquent\Builder|ExpertBank newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExpertBank newQuery()
 * @method static \Illuminate\Database\Query\Builder|ExpertBank onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ExpertBank query()
 * @method static \Illuminate\Database\Eloquent\Builder|ExpertBank whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpertBank whereCertLukFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpertBank whereCertNonLukFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpertBank whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpertBank whereCvFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpertBank whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpertBank whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpertBank whereExpertise($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpertBank whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpertBank whereIjazahFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpertBank whereInstitution($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpertBank whereMobilePhoneNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpertBank whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpertBank whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpertBank whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|ExpertBank withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ExpertBank withoutTrashed()
 */
	class ExpertBank extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\ExpertBankTeam
 *
 * @property int $id
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\ExpertBankTeamMember[] $member
 * @property-read int|null $member_count
 * @method static \Illuminate\Database\Eloquent\Builder|ExpertBankTeam newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExpertBankTeam newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExpertBankTeam query()
 * @method static \Illuminate\Database\Eloquent\Builder|ExpertBankTeam whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpertBankTeam whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpertBankTeam whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpertBankTeam whereUpdatedAt($value)
 */
	class ExpertBankTeam extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\ExpertBankTeamMember
 *
 * @property int $id
 * @property int|null $id_expert_bank_team
 * @property int|null $id_expert_bank
 * @property string|null $position
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Entity\ExpertBank|null $expertBank
 * @property-read \App\Entity\ExpertBankTeam|null $team
 * @method static \Illuminate\Database\Eloquent\Builder|ExpertBankTeamMember newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExpertBankTeamMember newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExpertBankTeamMember query()
 * @method static \Illuminate\Database\Eloquent\Builder|ExpertBankTeamMember whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpertBankTeamMember whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpertBankTeamMember whereIdExpertBank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpertBankTeamMember whereIdExpertBankTeam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpertBankTeamMember wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpertBankTeamMember whereUpdatedAt($value)
 */
	class ExpertBankTeamMember extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\FeasibilityTest
 *
 * @property int $id
 * @property int|null $id_project
 * @property string|null $conclusion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $id_feasibility_test_team_member
 * @property int|null $id_tuk_secretary_member
 * @property string|null $email
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\FeasibilityTestDetail[] $detail
 * @property-read int|null $detail_count
 * @property-read \App\Entity\FeasibilityTestTeamMember|null $feasibilityTestTeamMember
 * @property-read mixed $input_time
 * @property-read \App\Entity\TukSecretaryMember|null $tukSecretaryMember
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTest query()
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTest whereConclusion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTest whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTest whereIdFeasibilityTestTeamMember($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTest whereIdProject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTest whereIdTukSecretaryMember($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTest whereUpdatedAt($value)
 */
	class FeasibilityTest extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\FeasibilityTestDetail
 *
 * @property int $id
 * @property int|null $id_feasibility_test
 * @property string|null $appropriateness
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $id_eligibility_criteria
 * @property string|null $expert_notes
 * @property-read \App\Entity\EligibilityCriteria|null $eligibility
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTestDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTestDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTestDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTestDetail whereAppropriateness($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTestDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTestDetail whereExpertNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTestDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTestDetail whereIdEligibilityCriteria($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTestDetail whereIdFeasibilityTest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTestDetail whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTestDetail whereUpdatedAt($value)
 */
	class FeasibilityTestDetail extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\FeasibilityTestRecap
 *
 * @property int $id
 * @property int|null $id_project
 * @property string|null $recap
 * @property bool|null $is_feasib
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Entity\Project|null $project
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTestRecap newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTestRecap newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTestRecap query()
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTestRecap whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTestRecap whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTestRecap whereIdProject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTestRecap whereIsFeasib($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTestRecap whereRecap($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTestRecap whereUpdatedAt($value)
 */
	class FeasibilityTestRecap extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\FeasibilityTestTeam
 *
 * @property int $id
 * @property string|null $authority
 * @property string|null $assignment_number
 * @property string|null $assignment_file
 * @property string|null $phone
 * @property string|null $email
 * @property int|null $id_province
 * @property int|null $id_district
 * @property string|null $address
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $id_province_name
 * @property int|null $id_district_name
 * @property int|null $team_number
 * @property string|null $logo
 * @property string|null $institution
 * @property-read \App\Entity\District|null $district
 * @property-read \App\Entity\District|null $districtAuthority
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\FeasibilityTestTeamMember[] $member
 * @property-read int|null $member_count
 * @property-read \App\Entity\Province|null $province
 * @property-read \App\Entity\Province|null $provinceAuthority
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\TukSecretaryMember[] $secretary
 * @property-read int|null $secretary_count
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTestTeam newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTestTeam newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTestTeam query()
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTestTeam whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTestTeam whereAssignmentFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTestTeam whereAssignmentNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTestTeam whereAuthority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTestTeam whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTestTeam whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTestTeam whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTestTeam whereIdDistrict($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTestTeam whereIdDistrictName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTestTeam whereIdProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTestTeam whereIdProvinceName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTestTeam whereInstitution($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTestTeam whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTestTeam wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTestTeam whereTeamNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTestTeam whereUpdatedAt($value)
 */
	class FeasibilityTestTeam extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\FeasibilityTestTeamMember
 *
 * @property int $id
 * @property int|null $id_feasibility_test_team
 * @property string|null $position
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $id_expert_bank
 * @property int|null $id_luk_member
 * @property-read \App\Entity\ExpertBank|null $expertBank
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\FeasibilityTest[] $feasibilityTest
 * @property-read int|null $feasibility_test_count
 * @property-read \App\Entity\FeasibilityTestTeam|null $feasibilityTestTeam
 * @property-read \App\Entity\LukMember|null $lukMember
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\TukProject[] $tukProject
 * @property-read int|null $tuk_project_count
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTestTeamMember newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTestTeamMember newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTestTeamMember query()
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTestTeamMember whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTestTeamMember whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTestTeamMember whereIdExpertBank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTestTeamMember whereIdFeasibilityTestTeam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTestTeamMember whereIdLukMember($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTestTeamMember wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeasibilityTestTeamMember whereUpdatedAt($value)
 */
	class FeasibilityTestTeamMember extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\Feedback
 *
 * @property int $id
 * @property int $announcement_id
 * @property string $name
 * @property string $id_card_number
 * @property string $phone
 * @property string $email
 * @property string|null $photo_filepath
 * @property int $responder_type_id
 * @property bool $is_relevant
 * @property int|null $set_relevant_by
 * @property string|null $set_relevant_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property bool $deleted
 * @property string|null $deleted_at
 * @property string|null $concern
 * @property string|null $expectation
 * @property int|null $rating
 * @property int|null $public_consultation_id
 * @property string|null $environment_condition
 * @property string|null $local_impact
 * @property string|null $community_type
 * @property string|null $community_gender
 * @property-read \App\Entity\Announcement $announcement
 * @property-read \App\Entity\ResponderType $responderType
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback query()
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereAnnouncementId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereCommunityGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereCommunityType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereConcern($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereEnvironmentCondition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereExpectation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereIdCardNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereIsRelevant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereLocalImpact($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback wherePhotoFilepath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback wherePublicConsultationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereResponderTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereSetRelevantAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereSetRelevantBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereUpdatedAt($value)
 */
	class Feedback extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\Formulator
 *
 * @property int $id
 * @property string|null $id_formulator
 * @property string|null $name
 * @property string|null $expertise
 * @property string|null $cert_no
 * @property string|null $nip
 * @property string|null $date_start
 * @property string|null $date_end
 * @property string|null $cert_file
 * @property string|null $cv_file
 * @property string|null $reg_no
 * @property string|null $id_institution
 * @property string|null $membership_status
 * @property string|null $id_lsp
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $email
 * @property string|null $nik
 * @property string|null $province
 * @property string|null $district
 * @property string|null $address
 * @property string|null $phone
 * @property int|null $id_lpjp
 * @property-read \App\Entity\District|null $formulatorDistrict
 * @property-read \App\Entity\Province|null $formulatorProvince
 * @property-read \App\Entity\Lpjp|null $lpjp
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\FormulatorTeamMember[] $teamMember
 * @property-read int|null $team_member_count
 * @property-read \App\Laravue\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Formulator newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Formulator newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Formulator query()
 * @method static \Illuminate\Database\Eloquent\Builder|Formulator whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Formulator whereCertFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Formulator whereCertNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Formulator whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Formulator whereCvFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Formulator whereDateEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Formulator whereDateStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Formulator whereDistrict($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Formulator whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Formulator whereExpertise($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Formulator whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Formulator whereIdFormulator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Formulator whereIdInstitution($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Formulator whereIdLpjp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Formulator whereIdLsp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Formulator whereMembershipStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Formulator whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Formulator whereNik($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Formulator whereNip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Formulator wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Formulator whereProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Formulator whereRegNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Formulator whereUpdatedAt($value)
 */
	class Formulator extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\FormulatorTeam
 *
 * @property int $id
 * @property string $id_team
 * @property string|null $name
 * @property int|null $status
 * @property string|null $id_applicant
 * @property string|null $date_input
 * @property string|null $evidence_letter
 * @property string|null $id_formulator
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $id_project
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\FormulatorTeamMember[] $member
 * @property-read int|null $member_count
 * @property-read \App\Entity\Project|null $project
 * @method static \Illuminate\Database\Eloquent\Builder|FormulatorTeam newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FormulatorTeam newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FormulatorTeam query()
 * @method static \Illuminate\Database\Eloquent\Builder|FormulatorTeam whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormulatorTeam whereDateInput($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormulatorTeam whereEvidenceLetter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormulatorTeam whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormulatorTeam whereIdApplicant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormulatorTeam whereIdFormulator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormulatorTeam whereIdProject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormulatorTeam whereIdTeam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormulatorTeam whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormulatorTeam whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormulatorTeam whereUpdatedAt($value)
 */
	class FormulatorTeam extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\FormulatorTeamMember
 *
 * @property int $id
 * @property int|null $id_formulator_team
 * @property int|null $id_formulator
 * @property string|null $position
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $id_expert
 * @property-read \App\Entity\EnvironmentalExpert|null $expert
 * @property-read \App\Entity\Formulator|null $formulator
 * @property-read \App\Entity\FormulatorTeam|null $team
 * @method static \Illuminate\Database\Eloquent\Builder|FormulatorTeamMember newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FormulatorTeamMember newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FormulatorTeamMember query()
 * @method static \Illuminate\Database\Eloquent\Builder|FormulatorTeamMember whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormulatorTeamMember whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormulatorTeamMember whereIdExpert($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormulatorTeamMember whereIdFormulator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormulatorTeamMember whereIdFormulatorTeam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormulatorTeamMember wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormulatorTeamMember whereUpdatedAt($value)
 */
	class FormulatorTeamMember extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\GovernmentInstitution
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $institution_type
 * @property string|null $email
 * @property int|null $id_province
 * @property int|null $id_district
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Entity\District|null $district
 * @property-read \App\Entity\Province|null $province
 * @method static \Illuminate\Database\Eloquent\Builder|GovernmentInstitution newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GovernmentInstitution newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GovernmentInstitution query()
 * @method static \Illuminate\Database\Eloquent\Builder|GovernmentInstitution whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GovernmentInstitution whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GovernmentInstitution whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GovernmentInstitution whereIdDistrict($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GovernmentInstitution whereIdProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GovernmentInstitution whereInstitutionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GovernmentInstitution whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GovernmentInstitution whereUpdatedAt($value)
 */
	class GovernmentInstitution extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\HolisticEvaluation
 *
 * @property int $id
 * @property int|null $id_project
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Entity\Project|null $project
 * @method static \Illuminate\Database\Eloquent\Builder|HolisticEvaluation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HolisticEvaluation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HolisticEvaluation query()
 * @method static \Illuminate\Database\Eloquent\Builder|HolisticEvaluation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HolisticEvaluation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HolisticEvaluation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HolisticEvaluation whereIdProject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HolisticEvaluation whereUpdatedAt($value)
 */
	class HolisticEvaluation extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\Home
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Home newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Home newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Home query()
 */
	class Home extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\ImpactAnalysisDetail
 *
 * @property int $id
 * @property int|null $id_env_impact_analysis
 * @property int|null $id_important_trait
 * @property string|null $important_trait
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Entity\EnvImpactAnalysis|null $impactAnalysis
 * @property-read \App\Entity\ImportantTrait|null $importantTrait
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactAnalysisDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactAnalysisDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactAnalysisDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactAnalysisDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactAnalysisDetail whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactAnalysisDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactAnalysisDetail whereIdEnvImpactAnalysis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactAnalysisDetail whereIdImportantTrait($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactAnalysisDetail whereImportantTrait($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactAnalysisDetail whereUpdatedAt($value)
 */
	class ImpactAnalysisDetail extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\ImpactIdentification
 *
 * @property int $id
 * @property int $id_project
 * @property int|null $id_project_component
 * @property int|null $id_project_rona_awal
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $id_change_type
 * @property int|null $id_unit
 * @property float|null $nominal
 * @property string|null $potential_impact_evaluation
 * @property bool|null $is_hypothetical_significant
 * @property string|null $initial_study_plan
 * @property string|null $study_location
 * @property int|null $study_length_year
 * @property int|null $study_length_month
 * @property int|null $id_sub_project_component
 * @property int|null $id_sub_project_rona_awal
 * @property bool|null $is_managed
 * @property \App\Entity\Unit|null $unit
 * @property string|null $change_type_name
 * @property string|null $data_file
 * @property-read \App\Entity\ChangeType|null $changeType
 * @property-read \App\Entity\ImpactIdentificationClone|null $clone
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read \App\Entity\ProjectComponent|null $component
 * @property-read \App\Entity\EnvImpactAnalysis|null $envImpactAnalysis
 * @property-read \App\Entity\EnvManagePlan|null $envManagePlan
 * @property-read \App\Entity\EnvMonitorPlan|null $envMonitorPlan
 * @property-read \App\Entity\ImpactStudy|null $impactStudy
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\PotentialImpactEvaluation[] $potentialImpactEvaluation
 * @property-read int|null $potential_impact_evaluation_count
 * @property-read \App\Entity\Project $project
 * @property-read \App\Entity\ProjectRonaAwal|null $ronaAwal
 * @property-read \App\Entity\SubProjectComponent|null $subProjectComponent
 * @property-read \App\Entity\SubProjectRonaAwal|null $subProjectRonaAwal
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactIdentification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactIdentification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactIdentification query()
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactIdentification whereChangeTypeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactIdentification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactIdentification whereDataFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactIdentification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactIdentification whereIdChangeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactIdentification whereIdProject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactIdentification whereIdProjectComponent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactIdentification whereIdProjectRonaAwal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactIdentification whereIdSubProjectComponent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactIdentification whereIdSubProjectRonaAwal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactIdentification whereIdUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactIdentification whereInitialStudyPlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactIdentification whereIsHypotheticalSignificant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactIdentification whereIsManaged($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactIdentification whereNominal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactIdentification wherePotentialImpactEvaluation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactIdentification whereStudyLengthMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactIdentification whereStudyLengthYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactIdentification whereStudyLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactIdentification whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactIdentification whereUpdatedAt($value)
 */
	class ImpactIdentification extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\ImpactIdentificationClone
 *
 * @property int $id
 * @property int|null $id_impact_identification
 * @property int|null $id_project
 * @property int|null $id_unit
 * @property int|null $id_change_type
 * @property float|null $nominal
 * @property string|null $potential_impact_evaluation
 * @property bool|null $is_hypothetical_significant
 * @property bool|null $is_managed
 * @property string|null $initial_study_plan
 * @property string|null $study_location
 * @property int|null $study_length_year
 * @property int|null $study_length_month
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $id_sub_project_component
 * @property int|null $id_sub_project_rona_awal
 * @property string|null $data_file
 * @property int|null $id_project_component
 * @property int|null $id_project_rona_awal
 * @property-read \App\Entity\ChangeType|null $changeType
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read \App\Entity\EnvImpactAnalysis|null $envImpactAnalysis
 * @property-read \App\Entity\EnvManagePlan|null $envManagePlan
 * @property-read \App\Entity\EnvMonitorPlan|null $envMonitorPlan
 * @property-read \App\Entity\ImpactStudyClone|null $impactStudy
 * @property-read \App\Entity\EnvPlanInstitution|null $institution
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\PotentialImpactEvalClone[] $potentialImpactEvaluation
 * @property-read int|null $potential_impact_evaluation_count
 * @property-read \App\Entity\Project|null $project
 * @property-read \App\Entity\ProjectComponent|null $projectComponent
 * @property-read \App\Entity\ProjectRonaAwal|null $projectRonaAwal
 * @property-read \App\Entity\ImpactIdentification|null $real
 * @property-read \App\Entity\SubProjectComponent|null $subProjectComponent
 * @property-read \App\Entity\SubProjectRonaAwal|null $subProjectRonaAwal
 * @property-read \App\Entity\Unit|null $unit
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactIdentificationClone newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactIdentificationClone newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactIdentificationClone query()
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactIdentificationClone whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactIdentificationClone whereDataFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactIdentificationClone whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactIdentificationClone whereIdChangeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactIdentificationClone whereIdImpactIdentification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactIdentificationClone whereIdProject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactIdentificationClone whereIdProjectComponent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactIdentificationClone whereIdProjectRonaAwal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactIdentificationClone whereIdSubProjectComponent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactIdentificationClone whereIdSubProjectRonaAwal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactIdentificationClone whereIdUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactIdentificationClone whereInitialStudyPlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactIdentificationClone whereIsHypotheticalSignificant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactIdentificationClone whereIsManaged($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactIdentificationClone whereNominal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactIdentificationClone wherePotentialImpactEvaluation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactIdentificationClone whereStudyLengthMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactIdentificationClone whereStudyLengthYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactIdentificationClone whereStudyLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactIdentificationClone whereUpdatedAt($value)
 */
	class ImpactIdentificationClone extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\ImpactKegiatanLainSekitar
 *
 * @property int $id
 * @property int $id_impact_identification
 * @property int $id_project_kegiatan_lain_sekitar
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property bool $is_andal
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactKegiatanLainSekitar newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactKegiatanLainSekitar newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactKegiatanLainSekitar query()
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactKegiatanLainSekitar whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactKegiatanLainSekitar whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactKegiatanLainSekitar whereIdImpactIdentification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactKegiatanLainSekitar whereIdProjectKegiatanLainSekitar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactKegiatanLainSekitar whereIsAndal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactKegiatanLainSekitar whereUpdatedAt($value)
 */
	class ImpactKegiatanLainSekitar extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\ImpactStudy
 *
 * @property int $id
 * @property int $id_impact_identification
 * @property string|null $forecast_method
 * @property string|null $required_information
 * @property string|null $data_gathering_method
 * @property string|null $analysis_method
 * @property string|null $evaluation_method
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactStudy newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactStudy newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactStudy query()
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactStudy whereAnalysisMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactStudy whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactStudy whereDataGatheringMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactStudy whereEvaluationMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactStudy whereForecastMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactStudy whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactStudy whereIdImpactIdentification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactStudy whereRequiredInformation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactStudy whereUpdatedAt($value)
 */
	class ImpactStudy extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\ImpactStudyClone
 *
 * @property int $id
 * @property int|null $id_impact_identification_clone
 * @property int|null $id_project
 * @property string|null $forecast_method
 * @property string|null $required_information
 * @property string|null $data_gathering_method
 * @property string|null $analysis_method
 * @property string|null $evaluation_method
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactStudyClone newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactStudyClone newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactStudyClone query()
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactStudyClone whereAnalysisMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactStudyClone whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactStudyClone whereDataGatheringMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactStudyClone whereEvaluationMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactStudyClone whereForecastMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactStudyClone whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactStudyClone whereIdImpactIdentificationClone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactStudyClone whereIdProject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactStudyClone whereRequiredInformation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImpactStudyClone whereUpdatedAt($value)
 */
	class ImpactStudyClone extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\ImportantTrait
 *
 * @property int $id
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\ImpactAnalysisDetail[] $impactAnalysis
 * @property-read int|null $impact_analysis_count
 * @method static \Illuminate\Database\Eloquent\Builder|ImportantTrait newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImportantTrait newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImportantTrait query()
 * @method static \Illuminate\Database\Eloquent\Builder|ImportantTrait whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImportantTrait whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImportantTrait whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImportantTrait whereUpdatedAt($value)
 */
	class ImportantTrait extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\Initiator
 *
 * @property int $id
 * @property string $name
 * @property string $pic
 * @property string $email
 * @property string|null $phone
 * @property string|null $address
 * @property string $user_type
 * @property string|null $nib
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $agency_type
 * @property string|null $province
 * @property string|null $district
 * @property string|null $pic_role
 * @property string|null $logo
 * @property string|null $nib_doc_oss
 * @property-read \App\Laravue\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Initiator newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Initiator newQuery()
 * @method static \Illuminate\Database\Query\Builder|Initiator onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Initiator query()
 * @method static \Illuminate\Database\Eloquent\Builder|Initiator whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Initiator whereAgencyType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Initiator whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Initiator whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Initiator whereDistrict($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Initiator whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Initiator whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Initiator whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Initiator whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Initiator whereNib($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Initiator whereNibDocOss($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Initiator wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Initiator wherePic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Initiator wherePicRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Initiator whereProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Initiator whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Initiator whereUserType($value)
 * @method static \Illuminate\Database\Query\Builder|Initiator withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Initiator withoutTrashed()
 */
	class Initiator extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\Institution
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|Institution newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Institution newQuery()
 * @method static \Illuminate\Database\Query\Builder|Institution onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Institution query()
 * @method static \Illuminate\Database\Eloquent\Builder|Institution whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Institution whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Institution whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Institution whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Institution whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Institution withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Institution withoutTrashed()
 */
	class Institution extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\KaForm
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $document_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|KaForm newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KaForm newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KaForm query()
 * @method static \Illuminate\Database\Eloquent\Builder|KaForm whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KaForm whereDocumentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KaForm whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KaForm whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KaForm whereUpdatedAt($value)
 */
	class KaForm extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\KaReview
 *
 * @property int $id
 * @property int|null $id_project
 * @property string|null $status
 * @property string|null $notes
 * @property string|null $application_letter
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $document_type
 * @property-read \App\Entity\Project|null $project
 * @method static \Illuminate\Database\Eloquent\Builder|KaReview newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KaReview newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KaReview query()
 * @method static \Illuminate\Database\Eloquent\Builder|KaReview whereApplicationLetter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KaReview whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KaReview whereDocumentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KaReview whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KaReview whereIdProject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KaReview whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KaReview whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KaReview whereUpdatedAt($value)
 */
	class KaReview extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\Kbli
 *
 * @property int $id
 * @property int $parent_id
 * @property string $value
 * @property string $description
 * @method static \Illuminate\Database\Eloquent\Builder|Kbli newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Kbli newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Kbli query()
 * @method static \Illuminate\Database\Eloquent\Builder|Kbli whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kbli whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kbli whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kbli whereValue($value)
 */
	class Kbli extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\KbliEnvParam
 *
 * @property int $id
 * @property int $kbli_id
 * @property string $param
 * @property string $condition
 * @property string $unit
 * @property string $doc_req
 * @property string $amdal_type
 * @property string $risk_level
 * @property string|null $is_param_multisector
 * @method static \Illuminate\Database\Eloquent\Builder|KbliEnvParam newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KbliEnvParam newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KbliEnvParam query()
 * @method static \Illuminate\Database\Eloquent\Builder|KbliEnvParam whereAmdalType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KbliEnvParam whereCondition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KbliEnvParam whereDocReq($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KbliEnvParam whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KbliEnvParam whereIsParamMultisector($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KbliEnvParam whereKbliId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KbliEnvParam whereParam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KbliEnvParam whereRiskLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KbliEnvParam whereUnit($value)
 */
	class KbliEnvParam extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\KegiatanLainSekitar
 *
 * @property int $id
 * @property string $name
 * @property bool $is_master
 * @property int|null $originator_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\ProjectKegiatanLainSekitar[] $project
 * @property-read int|null $project_count
 * @method static \Illuminate\Database\Eloquent\Builder|KegiatanLainSekitar newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KegiatanLainSekitar newQuery()
 * @method static \Illuminate\Database\Query\Builder|KegiatanLainSekitar onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|KegiatanLainSekitar query()
 * @method static \Illuminate\Database\Eloquent\Builder|KegiatanLainSekitar whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KegiatanLainSekitar whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KegiatanLainSekitar whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KegiatanLainSekitar whereIsMaster($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KegiatanLainSekitar whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KegiatanLainSekitar whereOriginatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KegiatanLainSekitar whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|KegiatanLainSekitar withTrashed()
 * @method static \Illuminate\Database\Query\Builder|KegiatanLainSekitar withoutTrashed()
 */
	class KegiatanLainSekitar extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\Lpjp
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $pic
 * @property string|null $reg_no
 * @property string|null $date_start
 * @property string|null $date_end
 * @property string|null $address
 * @property int|null $id_district
 * @property int|null $id_prov
 * @property string|null $phone_no
 * @property string|null $contact_person
 * @property string|null $mobile_phone_no
 * @property string|null $email
 * @property string|null $url_address
 * @property string|null $cert_file
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Entity\District|null $district
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\Formulator[] $members
 * @property-read int|null $members_count
 * @property-read \App\Entity\Province|null $province
 * @method static \Illuminate\Database\Eloquent\Builder|Lpjp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Lpjp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Lpjp query()
 * @method static \Illuminate\Database\Eloquent\Builder|Lpjp whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lpjp whereCertFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lpjp whereContactPerson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lpjp whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lpjp whereDateEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lpjp whereDateStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lpjp whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lpjp whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lpjp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lpjp whereIdDistrict($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lpjp whereIdProv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lpjp whereMobilePhoneNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lpjp whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lpjp wherePhoneNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lpjp wherePic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lpjp whereRegNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lpjp whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lpjp whereUrlAddress($value)
 */
	class Lpjp extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\LukMember
 *
 * @property int $id
 * @property string|null $status
 * @property string|null $nik
 * @property string|null $nip
 * @property string|null $name
 * @property string|null $institution
 * @property string|null $email
 * @property string|null $position
 * @property string|null $phone
 * @property string|null $decision_number
 * @property string|null $decision_file
 * @property string|null $sex
 * @property string|null $cv
 * @property int|null $id_province
 * @property int|null $id_district
 * @property string|null $address
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Entity\District|null $district
 * @property-read \App\Entity\FeasibilityTestTeamMember|null $feasibilityTestTeamMember
 * @property-read \App\Entity\Province|null $province
 * @method static \Illuminate\Database\Eloquent\Builder|LukMember newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LukMember newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LukMember query()
 * @method static \Illuminate\Database\Eloquent\Builder|LukMember whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LukMember whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LukMember whereCv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LukMember whereDecisionFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LukMember whereDecisionNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LukMember whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LukMember whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LukMember whereIdDistrict($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LukMember whereIdProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LukMember whereInstitution($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LukMember whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LukMember whereNik($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LukMember whereNip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LukMember wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LukMember wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LukMember whereSex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LukMember whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LukMember whereUpdatedAt($value)
 */
	class LukMember extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\MasterPotentialImpactEvaluationParam
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|MasterPotentialImpactEvaluationParam newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MasterPotentialImpactEvaluationParam newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MasterPotentialImpactEvaluationParam query()
 * @method static \Illuminate\Database\Eloquent\Builder|MasterPotentialImpactEvaluationParam whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MasterPotentialImpactEvaluationParam whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MasterPotentialImpactEvaluationParam whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MasterPotentialImpactEvaluationParam whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MasterPotentialImpactEvaluationParam whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MasterPotentialImpactEvaluationParam whereUpdatedAt($value)
 */
	class MasterPotentialImpactEvaluationParam extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\Material
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $raise_date
 * @property string $link
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Material newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Material newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Material query()
 * @method static \Illuminate\Database\Eloquent\Builder|Material whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Material whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Material whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Material whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Material whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Material whereRaiseDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Material whereUpdatedAt($value)
 */
	class Material extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\MediumLowUklUplTemplate
 *
 * @property int $id
 * @property string $template_type
 * @property string $file
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $type
 * @method static \Illuminate\Database\Eloquent\Builder|MediumLowUklUplTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MediumLowUklUplTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MediumLowUklUplTemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder|MediumLowUklUplTemplate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediumLowUklUplTemplate whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediumLowUklUplTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediumLowUklUplTemplate whereTemplateType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediumLowUklUplTemplate whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediumLowUklUplTemplate whereUpdatedAt($value)
 */
	class MediumLowUklUplTemplate extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\MeetingReport
 *
 * @property int $id
 * @property int|null $id_project
 * @property int|null $id_testing_meeting
 * @property string|null $meeting_date
 * @property string|null $meeting_time
 * @property string|null $location
 * @property int|null $expert_bank_team_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $document_type
 * @property string|null $file
 * @property string|null $notes
 * @property bool|null $is_accepted
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\MeetingReportInvitation[] $invitations
 * @property-read int|null $invitations_count
 * @property-read \App\Entity\Project|null $project
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingReport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingReport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingReport query()
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingReport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingReport whereDocumentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingReport whereExpertBankTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingReport whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingReport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingReport whereIdProject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingReport whereIdTestingMeeting($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingReport whereIsAccepted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingReport whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingReport whereMeetingDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingReport whereMeetingTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingReport whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingReport whereUpdatedAt($value)
 */
	class MeetingReport extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\MeetingReportInvitation
 *
 * @property int $id
 * @property int|null $id_expert_bank_team_member
 * @property int|null $id_meeting_report
 * @property string|null $role
 * @property string|null $name
 * @property string|null $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $id_feasibility_test_team_member
 * @property string|null $institution
 * @property int|null $id_government_institution
 * @property int|null $id_tuk_secretary_member
 * @property-read \App\Entity\ExpertBankTeamMember|null $expertBankTeamMember
 * @property-read \App\Entity\FeasibilityTestTeamMember|null $feasibilityTestTeamMember
 * @property-read \App\Entity\MeetingReport|null $meeting
 * @property-read \App\Entity\TukSecretaryMember|null $tukSecretaryMember
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingReportInvitation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingReportInvitation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingReportInvitation query()
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingReportInvitation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingReportInvitation whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingReportInvitation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingReportInvitation whereIdExpertBankTeamMember($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingReportInvitation whereIdFeasibilityTestTeamMember($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingReportInvitation whereIdGovernmentInstitution($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingReportInvitation whereIdMeetingReport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingReportInvitation whereIdTukSecretaryMember($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingReportInvitation whereInstitution($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingReportInvitation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingReportInvitation whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingReportInvitation whereUpdatedAt($value)
 */
	class MeetingReportInvitation extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\OssLicense
 *
 * @property int $id
 * @property int $id_initiator
 * @property string $email
 * @property string $kd_izin
 * @property string $id_izin
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OssLicense newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OssLicense newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OssLicense query()
 * @method static \Illuminate\Database\Eloquent\Builder|OssLicense whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OssLicense whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OssLicense whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OssLicense whereIdInitiator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OssLicense whereIdIzin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OssLicense whereKdIzin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OssLicense whereUpdatedAt($value)
 */
	class OssLicense extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\OssNib
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $nib
 * @property string|null $nib_submit_date
 * @property string|null $nib_published_date
 * @property string|null $nib_updated_date
 * @property string $oss_id
 * @property string $id_izin
 * @property string $kd_izin
 * @property string $company_name
 * @property string $company_email
 * @property array $json_content
 * @method static \Illuminate\Database\Eloquent\Builder|OssNib newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OssNib newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OssNib query()
 * @method static \Illuminate\Database\Eloquent\Builder|OssNib whereCompanyEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OssNib whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OssNib whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OssNib whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OssNib whereIdIzin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OssNib whereJsonContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OssNib whereKdIzin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OssNib whereNib($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OssNib whereNibPublishedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OssNib whereNibSubmitDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OssNib whereNibUpdatedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OssNib whereOssId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OssNib whereUpdatedAt($value)
 */
	class OssNib extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\OssProject
 *
 * @property int $id
 * @property string $nib
 * @property string|null $applicant_name
 * @property string|null $no_id_user_process
 * @property string|null $id_project_oss
 * @property string|null $biz_scale
 * @property string|null $risk_scale
 * @property string|null $project_desc
 * @property string|null $biz_desc
 * @property string|null $project_name
 * @property string|null $kbli
 * @property int|null $land_area
 * @property string|null $land_area_unit
 * @property string|null $project_address
 * @property string|null $project_scale
 * @property string|null $project_scale_unit
 * @property string|null $document_code
 * @property string|null $document_name
 * @property string|null $authority
 * @property array|null $json_content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OssProject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OssProject newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OssProject query()
 * @method static \Illuminate\Database\Eloquent\Builder|OssProject whereApplicantName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OssProject whereAuthority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OssProject whereBizDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OssProject whereBizScale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OssProject whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OssProject whereDocumentCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OssProject whereDocumentName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OssProject whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OssProject whereIdProjectOss($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OssProject whereJsonContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OssProject whereKbli($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OssProject whereLandArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OssProject whereLandAreaUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OssProject whereNib($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OssProject whereNoIdUserProcess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OssProject whereProjectAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OssProject whereProjectDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OssProject whereProjectName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OssProject whereProjectScale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OssProject whereProjectScaleUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OssProject whereRiskScale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OssProject whereUpdatedAt($value)
 */
	class OssProject extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\Param
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|Param newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Param newQuery()
 * @method static \Illuminate\Database\Query\Builder|Param onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Param query()
 * @method static \Illuminate\Database\Eloquent\Builder|Param whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Param whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Param whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Param whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Param whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Param withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Param withoutTrashed()
 */
	class Param extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\Policy
 *
 * @property int $id
 * @property string $regulation_no
 * @property int $regulation_type
 * @property string $about
 * @property string $set
 * @property string $link
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $field_of_activity
 * @property-read \App\Entity\Regulation|null $regulation
 * @method static \Illuminate\Database\Eloquent\Builder|Policy newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Policy newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Policy query()
 * @method static \Illuminate\Database\Eloquent\Builder|Policy whereAbout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Policy whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Policy whereFieldOfActivity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Policy whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Policy whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Policy whereRegulationNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Policy whereRegulationType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Policy whereSet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Policy whereUpdatedAt($value)
 */
	class Policy extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\PotentialImpactEvalClone
 *
 * @property int $id
 * @property int|null $id_impact_identification_clone
 * @property int|null $id_pie_param
 * @property string|null $text
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Entity\MasterPotentialImpactEvaluationParam|null $pieParam
 * @method static \Illuminate\Database\Eloquent\Builder|PotentialImpactEvalClone newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PotentialImpactEvalClone newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PotentialImpactEvalClone query()
 * @method static \Illuminate\Database\Eloquent\Builder|PotentialImpactEvalClone whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PotentialImpactEvalClone whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PotentialImpactEvalClone whereIdImpactIdentificationClone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PotentialImpactEvalClone whereIdPieParam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PotentialImpactEvalClone whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PotentialImpactEvalClone whereUpdatedAt($value)
 */
	class PotentialImpactEvalClone extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\PotentialImpactEvaluation
 *
 * @property int $id
 * @property int $id_impact_identification
 * @property int $id_pie_param
 * @property string|null $text
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Entity\MasterPotentialImpactEvaluationParam $pieParam
 * @method static \Illuminate\Database\Eloquent\Builder|PotentialImpactEvaluation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PotentialImpactEvaluation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PotentialImpactEvaluation query()
 * @method static \Illuminate\Database\Eloquent\Builder|PotentialImpactEvaluation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PotentialImpactEvaluation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PotentialImpactEvaluation whereIdImpactIdentification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PotentialImpactEvaluation whereIdPieParam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PotentialImpactEvaluation whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PotentialImpactEvaluation whereUpdatedAt($value)
 */
	class PotentialImpactEvaluation extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\Project
 *
 * @property int $id
 * @property string|null $id_project
 * @property string|null $project_title
 * @property string|null $scale
 * @property string|null $scale_unit
 * @property string|null $authority
 * @property string|null $project_type
 * @property string|null $sector
 * @property string|null $description
 * @property int|null $id_applicant
 * @property string|null $field
 * @property string|null $location_desc
 * @property string|null $risk_level
 * @property int|null $project_year
 * @property string|null $map
 * @property int|null $map_scale
 * @property string|null $map_scale_unit
 * @property int|null $id_formulator_team
 * @property string|null $announcement_letter
 * @property string|null $result_risk
 * @property string|null $kbli
 * @property bool $published
 * @property string|null $required_doc
 * @property string|null $biz_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $ktr
 * @property string|null $type_formulator_team
 * @property int|null $id_lpjp
 * @property string|null $pre_agreement
 * @property string|null $pre_agreement_file
 * @property string|null $map_rkl
 * @property string|null $map_rpl
 * @property string|null $registration_no
 * @property string|null $marking
 * @property mixed|null $workflow_meta
 * @property string|null $auth_province
 * @property string|null $auth_district
 * @property string|null $study_approach
 * @property string|null $status
 * @property string|null $ppib
 * @property string|null $ppib_file
 * @property string|null $kawasan_lindung
 * @property string|null $kawasan_lindung_file
 * @property string|null $ppjk
 * @property string|null $isppjk
 * @property string|null $oss_risk
 * @property string|null $oss_authority
 * @property string|null $oss_area
 * @property string|null $oss_invest_status
 * @property string|null $oss_required_doc
 * @property string|null $isDraft
 * @property string|null $oss_sppl_doc
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\DocumentAttachment[] $DocumentAttachment
 * @property-read int|null $document_attachment_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\ProjectAddress[] $address
 * @property-read int|null $address_count
 * @property-read \App\Entity\Announcement|null $announcement
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\ProjectAuthority[] $authorities
 * @property-read int|null $authorities_count
 * @property-read \App\Entity\District $district
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\FeasibilityTest[] $feasibilityTest
 * @property-read int|null $feasibility_test_count
 * @property-read \App\Entity\FeasibilityTestRecap|null $feasibilityTestRecap
 * @property-read mixed $filling_date
 * @property-read mixed $rkl_rpl_document
 * @property-read mixed $submission_deadline
 * @property-read mixed $ukl_upl_document
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\ImpactIdentification[] $impactIdentifications
 * @property-read int|null $impact_identifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\ImpactIdentificationClone[] $impactIdentificationsClone
 * @property-read int|null $impact_identifications_clone_count
 * @property-read \App\Entity\Initiator|null $initiator
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\KaReview[] $kaReviews
 * @property-read int|null $ka_reviews_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\SubProject[] $listSubProject
 * @property-read int|null $list_sub_project_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\ProjectMapAttachment[] $mapFiles
 * @property-read int|null $map_files_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\MeetingReport[] $meetingReports
 * @property-read int|null $meeting_reports_count
 * @property-read \App\Entity\Province|null $province
 * @property-read \App\Entity\ProjectSkkl|null $skkl
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\SubProject[] $subProjects
 * @property-read int|null $sub_projects_count
 * @property-read \App\Entity\FormulatorTeam|null $team
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\TestingMeeting[] $testingMeeting
 * @property-read int|null $testing_meeting_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\TukProject[] $tukProject
 * @property-read int|null $tuk_project_count
 * @method static \Illuminate\Database\Eloquent\Builder|Project newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project newQuery()
 * @method static \Illuminate\Database\Query\Builder|Project onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Project query()
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereAnnouncementLetter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereAuthDistrict($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereAuthProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereAuthority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereBizType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereField($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereIdApplicant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereIdFormulatorTeam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereIdLpjp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereIdProject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereIsDraft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereIsppjk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereKawasanLindung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereKawasanLindungFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereKbli($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereKtr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereLocationDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereMap($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereMapRkl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereMapRpl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereMapScale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereMapScaleUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereMarking($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereOssArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereOssAuthority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereOssInvestStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereOssRequiredDoc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereOssRisk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereOssSpplDoc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project wherePpib($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project wherePpibFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project wherePpjk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project wherePreAgreement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project wherePreAgreementFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereProjectTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereProjectType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereProjectYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project wherePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereRegistrationNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereRequiredDoc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereResultRisk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereRiskLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereScale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereScaleUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereSector($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereStudyApproach($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereTypeFormulatorTeam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereWorkflowMeta($value)
 * @method static \Illuminate\Database\Query\Builder|Project withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Project withoutTrashed()
 */
	class Project extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Entity{
/**
 * App\Entity\ProjectAddress
 *
 * @property int $id
 * @property int $id_project
 * @property string|null $prov
 * @property string|null $district
 * @property string|null $address
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectAddress newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectAddress newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectAddress query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectAddress whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectAddress whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectAddress whereDistrict($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectAddress whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectAddress whereIdProject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectAddress whereProv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectAddress whereUpdatedAt($value)
 */
	class ProjectAddress extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\ProjectAuthority
 *
 * @property int $id
 * @property int|null $id_project
 * @property string|null $sector
 * @property string|null $project
 * @property string|null $authority
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectAuthority newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectAuthority newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectAuthority query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectAuthority whereAuthority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectAuthority whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectAuthority whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectAuthority whereIdProject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectAuthority whereProject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectAuthority whereSector($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectAuthority whereUpdatedAt($value)
 */
	class ProjectAuthority extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\ProjectComponent
 *
 * @property int $id
 * @property int $id_project
 * @property int|null $id_component
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $description
 * @property string|null $measurement
 * @property bool $is_andal
 * @property-read \App\Entity\Component|null $component
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectComponent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectComponent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectComponent query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectComponent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectComponent whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectComponent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectComponent whereIdComponent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectComponent whereIdProject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectComponent whereIsAndal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectComponent whereMeasurement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectComponent whereUpdatedAt($value)
 */
	class ProjectComponent extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\ProjectField
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectField newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectField newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectField query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectField whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectField whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectField whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectField whereUpdatedAt($value)
 */
	class ProjectField extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\ProjectFilter
 *
 * @property int $id
 * @property int $id_project
 * @property bool $wastewater
 * @property bool $disposal_wastewater
 * @property bool $utilization_wastewater
 * @property bool $high_pollution
 * @property bool $emission
 * @property bool $chimney
 * @property bool $genset
 * @property bool $high_emission
 * @property bool $b3
 * @property bool $collect_b3
 * @property bool $hoard_b3
 * @property bool $process_b3
 * @property bool $utilization_b3
 * @property bool $dumping_b3
 * @property bool $tps
 * @property string $traffic
 * @property bool $low_traffic
 * @property bool $mid_traffic
 * @property bool $high_traffic
 * @property bool $nothing
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $transport_b3
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectFilter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectFilter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectFilter query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectFilter whereB3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectFilter whereChimney($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectFilter whereCollectB3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectFilter whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectFilter whereDisposalWastewater($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectFilter whereDumpingB3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectFilter whereEmission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectFilter whereGenset($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectFilter whereHighEmission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectFilter whereHighPollution($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectFilter whereHighTraffic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectFilter whereHoardB3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectFilter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectFilter whereIdProject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectFilter whereLowTraffic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectFilter whereMidTraffic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectFilter whereNothing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectFilter whereProcessB3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectFilter whereTps($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectFilter whereTraffic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectFilter whereTransportB3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectFilter whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectFilter whereUtilizationB3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectFilter whereUtilizationWastewater($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectFilter whereWastewater($value)
 */
	class ProjectFilter extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\ProjectKaForm
 *
 * @property int $id
 * @property int|null $id_testing_verification
 * @property int|null $id_ka_form
 * @property string|null $suitability
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $name
 * @property-read \App\Entity\KaForm|null $kaForm
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectKaForm newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectKaForm newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectKaForm query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectKaForm whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectKaForm whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectKaForm whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectKaForm whereIdKaForm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectKaForm whereIdTestingVerification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectKaForm whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectKaForm whereSuitability($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectKaForm whereUpdatedAt($value)
 */
	class ProjectKaForm extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\ProjectKegiatanLainSekitar
 *
 * @property int $id
 * @property string|null $description
 * @property string|null $measurement
 * @property string|null $address
 * @property int $district_id
 * @property int $province_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $kegiatan_lain_sekitar_id
 * @property int $project_id
 * @property bool $is_andal
 * @property-read \App\Entity\KegiatanLainSekitar|null $kegiatanLainSekitar
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectKegiatanLainSekitar newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectKegiatanLainSekitar newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectKegiatanLainSekitar query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectKegiatanLainSekitar whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectKegiatanLainSekitar whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectKegiatanLainSekitar whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectKegiatanLainSekitar whereDistrictId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectKegiatanLainSekitar whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectKegiatanLainSekitar whereIsAndal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectKegiatanLainSekitar whereKegiatanLainSekitarId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectKegiatanLainSekitar whereMeasurement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectKegiatanLainSekitar whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectKegiatanLainSekitar whereProvinceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectKegiatanLainSekitar whereUpdatedAt($value)
 */
	class ProjectKegiatanLainSekitar extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\ProjectPkplhFinal
 *
 * @property int $id
 * @property int $id_project
 * @property string $number
 * @property string $title
 * @property string $date_published
 * @property string $file
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectPkplhFinal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectPkplhFinal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectPkplhFinal query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectPkplhFinal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectPkplhFinal whereDatePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectPkplhFinal whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectPkplhFinal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectPkplhFinal whereIdProject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectPkplhFinal whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectPkplhFinal whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectPkplhFinal whereUpdatedAt($value)
 */
	class ProjectPkplhFinal extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\ProjectRonaAwal
 *
 * @property int $id
 * @property int $id_project
 * @property int|null $id_rona_awal
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $description
 * @property string|null $measurement
 * @property bool $is_andal
 * @property string|null $file
 * @property-read \App\Entity\RonaAwal|null $rona_awal
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectRonaAwal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectRonaAwal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectRonaAwal query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectRonaAwal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectRonaAwal whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectRonaAwal whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectRonaAwal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectRonaAwal whereIdProject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectRonaAwal whereIdRonaAwal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectRonaAwal whereIsAndal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectRonaAwal whereMeasurement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectRonaAwal whereUpdatedAt($value)
 */
	class ProjectRonaAwal extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\ProjectSkkl
 *
 * @property int $id
 * @property int|null $id_project
 * @property string|null $file
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectSkkl newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectSkkl newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectSkkl query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectSkkl whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectSkkl whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectSkkl whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectSkkl whereIdProject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectSkkl whereUpdatedAt($value)
 */
	class ProjectSkkl extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\ProjectSkklFinal
 *
 * @property int $id
 * @property string $number
 * @property string $title
 * @property string $date_published
 * @property string $file
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $id_project
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectSkklFinal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectSkklFinal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectSkklFinal query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectSkklFinal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectSkklFinal whereDatePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectSkklFinal whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectSkklFinal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectSkklFinal whereIdProject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectSkklFinal whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectSkklFinal whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectSkklFinal whereUpdatedAt($value)
 */
	class ProjectSkklFinal extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\ProjectStage
 *
 * @property int $id
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectStage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectStage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectStage query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectStage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectStage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectStage whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectStage whereUpdatedAt($value)
 */
	class ProjectStage extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\Province
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\Lpjp[] $lpjp
 * @property-read int|null $lpjp_count
 * @method static \Illuminate\Database\Eloquent\Builder|Province newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Province newQuery()
 * @method static \Illuminate\Database\Query\Builder|Province onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Province query()
 * @method static \Illuminate\Database\Eloquent\Builder|Province whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Province whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Province whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Province whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Province whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Province withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Province withoutTrashed()
 */
	class Province extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\PublicConsultation
 *
 * @property int $id
 * @property int $announcement_id
 * @property int $project_id
 * @property string $event_date
 * @property int $participant
 * @property string $location
 * @property string $address
 * @property string $positive_feedback_summary
 * @property string $negative_feedback_summary
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property bool $is_publish
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\PublicConsultationDoc[] $docs
 * @property-read int|null $docs_count
 * @method static \Illuminate\Database\Eloquent\Builder|PublicConsultation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PublicConsultation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PublicConsultation query()
 * @method static \Illuminate\Database\Eloquent\Builder|PublicConsultation whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicConsultation whereAnnouncementId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicConsultation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicConsultation whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicConsultation whereEventDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicConsultation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicConsultation whereIsPublish($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicConsultation whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicConsultation whereNegativeFeedbackSummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicConsultation whereParticipant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicConsultation wherePositiveFeedbackSummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicConsultation whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicConsultation whereUpdatedAt($value)
 */
	class PublicConsultation extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\PublicConsultationDoc
 *
 * @property int $id
 * @property int $public_consultation_id
 * @property array $doc_json
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read mixed $file
 * @property-read \App\Entity\PublicConsultation|null $publicConsultation
 * @method static \Illuminate\Database\Eloquent\Builder|PublicConsultationDoc newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PublicConsultationDoc newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PublicConsultationDoc query()
 * @method static \Illuminate\Database\Eloquent\Builder|PublicConsultationDoc whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicConsultationDoc whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicConsultationDoc whereDocJson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicConsultationDoc whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicConsultationDoc wherePublicConsultationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicConsultationDoc whereUpdatedAt($value)
 */
	class PublicConsultationDoc extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\PublicQuestion
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $email
 * @property string|null $question
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PublicQuestion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PublicQuestion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PublicQuestion query()
 * @method static \Illuminate\Database\Eloquent\Builder|PublicQuestion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicQuestion whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicQuestion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicQuestion whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicQuestion whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicQuestion whereUpdatedAt($value)
 */
	class PublicQuestion extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\PublicSPT
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $role
 * @property string|null $nik
 * @property string|null $photo
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $comment
 * @property string|null $worries
 * @property string|null $hope
 * @property int|null $relevance
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PublicSPT newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PublicSPT newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PublicSPT query()
 * @method static \Illuminate\Database\Eloquent\Builder|PublicSPT whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicSPT whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicSPT whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicSPT whereHope($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicSPT whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicSPT whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicSPT whereNik($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicSPT wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicSPT wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicSPT whereRelevance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicSPT whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicSPT whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicSPT whereWorries($value)
 */
	class PublicSPT extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\Region
 *
 * @property int $id
 * @property string $region_id
 * @property string|null $province
 * @property string|null $regency
 * @property string|null $district
 * @property string|null $village
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Region newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Region newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Region query()
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereDistrict($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereRegency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereVillage($value)
 */
	class Region extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\Regulation
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Regulation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Regulation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Regulation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Regulation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Regulation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Regulation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Regulation whereUpdatedAt($value)
 */
	class Regulation extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\ResponderType
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ResponderType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ResponderType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ResponderType query()
 * @method static \Illuminate\Database\Eloquent\Builder|ResponderType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResponderType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResponderType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResponderType whereUpdatedAt($value)
 */
	class ResponderType extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\RklRplComment
 *
 * @property int $id
 * @property int $id_project
 * @property int $id_user
 * @property string $comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|RklRplComment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RklRplComment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RklRplComment query()
 * @method static \Illuminate\Database\Eloquent\Builder|RklRplComment whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RklRplComment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RklRplComment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RklRplComment whereIdProject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RklRplComment whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RklRplComment whereUpdatedAt($value)
 */
	class RklRplComment extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\RonaAwal
 *
 * @property int $id
 * @property string|null $name
 * @property int $id_component_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $definition
 * @property bool $is_master
 * @property int|null $originator_id
 * @property-read \App\Entity\ComponentType|null $componentType
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\ProjectRonaAwal[] $projectRonaAwal
 * @property-read int|null $project_rona_awal_count
 * @method static \Illuminate\Database\Eloquent\Builder|RonaAwal master()
 * @method static \Illuminate\Database\Eloquent\Builder|RonaAwal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RonaAwal newQuery()
 * @method static \Illuminate\Database\Query\Builder|RonaAwal onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|RonaAwal query()
 * @method static \Illuminate\Database\Eloquent\Builder|RonaAwal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RonaAwal whereDefinition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RonaAwal whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RonaAwal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RonaAwal whereIdComponentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RonaAwal whereIsMaster($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RonaAwal whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RonaAwal whereOriginatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RonaAwal whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|RonaAwal withTrashed()
 * @method static \Illuminate\Database\Query\Builder|RonaAwal withoutTrashed()
 */
	class RonaAwal extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\SignificantImpactFlowchart
 *
 * @property int $id
 * @property int|null $parent_id
 * @property int|null $child_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Entity\EnvImpactAnalysis|null $child
 * @property-read \App\Entity\EnvImpactAnalysis|null $parent
 * @method static \Illuminate\Database\Eloquent\Builder|SignificantImpactFlowchart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SignificantImpactFlowchart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SignificantImpactFlowchart query()
 * @method static \Illuminate\Database\Eloquent\Builder|SignificantImpactFlowchart whereChildId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SignificantImpactFlowchart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SignificantImpactFlowchart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SignificantImpactFlowchart whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SignificantImpactFlowchart whereUpdatedAt($value)
 */
	class SignificantImpactFlowchart extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\Sop
 *
 * @property int $id
 * @property int|null $id_component
 * @property int|null $id_rona_awal
 * @property string|null $mgmt_form
 * @property string|null $mgmt_period
 * @property string|null $monitoring_form
 * @property int|null $monitoring_time
 * @property int|null $monitoring_freq
 * @property string|null $monitoring_date_field
 * @property string|null $name
 * @property string|null $impact
 * @property string|null $other_impact
 * @property string|null $monitoring_period
 * @property string|null $impact_quantity
 * @property string|null $code
 * @property string|null $effective_date
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Sop newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sop newQuery()
 * @method static \Illuminate\Database\Query\Builder|Sop onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Sop query()
 * @method static \Illuminate\Database\Eloquent\Builder|Sop whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sop whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sop whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sop whereEffectiveDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sop whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sop whereIdComponent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sop whereIdRonaAwal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sop whereImpact($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sop whereImpactQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sop whereMgmtForm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sop whereMgmtPeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sop whereMonitoringDateField($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sop whereMonitoringForm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sop whereMonitoringFreq($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sop whereMonitoringPeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sop whereMonitoringTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sop whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sop whereOtherImpact($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sop whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Sop withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Sop withoutTrashed()
 */
	class Sop extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\SubProject
 *
 * @property int $id
 * @property string $kbli
 * @property string $name
 * @property string $result
 * @property string $biz_type
 * @property string $type
 * @property int $id_project
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $sector
 * @property string|null $scale
 * @property string|null $scale_unit
 * @property string|null $biz_name
 * @property string|null $sector_name
 * @property string|null $id_proyek
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\SubProjectComponent[] $components
 * @property-read int|null $components_count
 * @property-read \App\Entity\Project $project
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\SubProjectRonaAwal[] $ronaAwals
 * @property-read int|null $rona_awals_count
 * @method static \Illuminate\Database\Eloquent\Builder|SubProject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubProject newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubProject query()
 * @method static \Illuminate\Database\Eloquent\Builder|SubProject whereBizName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubProject whereBizType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubProject whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubProject whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubProject whereIdProject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubProject whereIdProyek($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubProject whereKbli($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubProject whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubProject whereResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubProject whereScale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubProject whereScaleUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubProject whereSector($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubProject whereSectorName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubProject whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubProject whereUpdatedAt($value)
 */
	class SubProject extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\SubProjectComponent
 *
 * @property int $id
 * @property int $id_sub_project
 * @property int|null $id_component
 * @property string|null $name
 * @property int|null $id_project_stage
 * @property string|null $description_common
 * @property string|null $description_specific
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $definition
 * @property string|null $unit
 * @property bool $is_andal
 * @property-read \App\Entity\Component|null $component
 * @property-read \App\Entity\ProjectStage|null $projectStage
 * @property-read \App\Entity\SubProject|null $subProject
 * @method static \Illuminate\Database\Eloquent\Builder|SubProjectComponent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubProjectComponent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubProjectComponent query()
 * @method static \Illuminate\Database\Eloquent\Builder|SubProjectComponent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubProjectComponent whereDefinition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubProjectComponent whereDescriptionCommon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubProjectComponent whereDescriptionSpecific($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubProjectComponent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubProjectComponent whereIdComponent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubProjectComponent whereIdProjectStage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubProjectComponent whereIdSubProject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubProjectComponent whereIsAndal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubProjectComponent whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubProjectComponent whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubProjectComponent whereUpdatedAt($value)
 */
	class SubProjectComponent extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\SubProjectParam
 *
 * @property int $id
 * @property string $param
 * @property string $scale
 * @property string $scale_unit
 * @property string $result
 * @property int $id_sub_project
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Entity\SubProject $subProject
 * @method static \Illuminate\Database\Eloquent\Builder|SubProjectParam newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubProjectParam newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubProjectParam query()
 * @method static \Illuminate\Database\Eloquent\Builder|SubProjectParam whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubProjectParam whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubProjectParam whereIdSubProject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubProjectParam whereParam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubProjectParam whereResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubProjectParam whereScale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubProjectParam whereScaleUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubProjectParam whereUpdatedAt($value)
 */
	class SubProjectParam extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\SubProjectRonaAwal
 *
 * @property int $id
 * @property int $id_sub_project_component
 * @property int|null $id_rona_awal
 * @property string|null $name
 * @property int|null $id_component_type
 * @property string|null $description_common
 * @property string|null $description_specific
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $definition
 * @property string|null $unit
 * @property bool $is_andal
 * @property-read \App\Entity\ComponentType|null $componentType
 * @property-read \App\Entity\RonaAwal|null $ronaAwal
 * @property-read \App\Entity\SubProjectComponent|null $subProjectComponent
 * @method static \Illuminate\Database\Eloquent\Builder|SubProjectRonaAwal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubProjectRonaAwal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubProjectRonaAwal query()
 * @method static \Illuminate\Database\Eloquent\Builder|SubProjectRonaAwal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubProjectRonaAwal whereDefinition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubProjectRonaAwal whereDescriptionCommon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubProjectRonaAwal whereDescriptionSpecific($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubProjectRonaAwal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubProjectRonaAwal whereIdComponentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubProjectRonaAwal whereIdRonaAwal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubProjectRonaAwal whereIdSubProjectComponent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubProjectRonaAwal whereIsAndal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubProjectRonaAwal whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubProjectRonaAwal whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubProjectRonaAwal whereUpdatedAt($value)
 */
	class SubProjectRonaAwal extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\SupportDoc
 *
 * @property int $id
 * @property string $id_project
 * @property string $name
 * @property string $file
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|SupportDoc newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SupportDoc newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SupportDoc query()
 * @method static \Illuminate\Database\Eloquent\Builder|SupportDoc whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupportDoc whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupportDoc whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupportDoc whereIdProject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupportDoc whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupportDoc whereUpdatedAt($value)
 */
	class SupportDoc extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\TestingMeeting
 *
 * @property int $id
 * @property string|null $meeting_date
 * @property string|null $meeting_time
 * @property string|null $location
 * @property int|null $expert_bank_team_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $id_project
 * @property string|null $document_type
 * @property string|null $file
 * @property bool|null $is_invitation_sent
 * @property string|null $invitation_file
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\TestingMeetingInvitation[] $invitations
 * @property-read int|null $invitations_count
 * @property-read \App\Entity\Project $project
 * @method static \Illuminate\Database\Eloquent\Builder|TestingMeeting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TestingMeeting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TestingMeeting query()
 * @method static \Illuminate\Database\Eloquent\Builder|TestingMeeting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestingMeeting whereDocumentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestingMeeting whereExpertBankTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestingMeeting whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestingMeeting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestingMeeting whereIdProject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestingMeeting whereInvitationFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestingMeeting whereIsInvitationSent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestingMeeting whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestingMeeting whereMeetingDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestingMeeting whereMeetingTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestingMeeting whereUpdatedAt($value)
 */
	class TestingMeeting extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\TestingMeetingInvitation
 *
 * @property int $id
 * @property int|null $id_expert_bank_team_member
 * @property int|null $id_testing_meeting
 * @property string|null $role
 * @property string|null $name
 * @property string|null $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $id_feasibility_test_team_member
 * @property string|null $institution
 * @property int|null $id_government_institution
 * @property int|null $id_tuk_secretary_member
 * @property int|null $id_user
 * @property-read \App\Entity\ExpertBankTeamMember|null $expertBankTeamMember
 * @property-read \App\Entity\FeasibilityTestTeamMember|null $feasibilityTestTeamMember
 * @property-read \App\Entity\GovernmentInstitution|null $governmentInstitution
 * @property-read \App\Entity\TestingMeeting|null $meeting
 * @property-read \App\Entity\TukSecretaryMember|null $tukSecretaryMember
 * @property-read \App\Laravue\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|TestingMeetingInvitation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TestingMeetingInvitation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TestingMeetingInvitation query()
 * @method static \Illuminate\Database\Eloquent\Builder|TestingMeetingInvitation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestingMeetingInvitation whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestingMeetingInvitation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestingMeetingInvitation whereIdExpertBankTeamMember($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestingMeetingInvitation whereIdFeasibilityTestTeamMember($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestingMeetingInvitation whereIdGovernmentInstitution($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestingMeetingInvitation whereIdTestingMeeting($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestingMeetingInvitation whereIdTukSecretaryMember($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestingMeetingInvitation whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestingMeetingInvitation whereInstitution($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestingMeetingInvitation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestingMeetingInvitation whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestingMeetingInvitation whereUpdatedAt($value)
 */
	class TestingMeetingInvitation extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\TestingVerification
 *
 * @property int $id
 * @property int|null $id_project
 * @property string|null $approved_by_position
 * @property string|null $approved_in
 * @property string|null $approved_date
 * @property string|null $approved_by_name
 * @property string|null $checked_in
 * @property string|null $checked_date
 * @property string|null $checked_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $document_type
 * @property string|null $notes
 * @property bool|null $is_complete
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\ProjectKaForm[] $forms
 * @property-read int|null $forms_count
 * @property-read \App\Entity\Project|null $project
 * @method static \Illuminate\Database\Eloquent\Builder|TestingVerification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TestingVerification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TestingVerification query()
 * @method static \Illuminate\Database\Eloquent\Builder|TestingVerification whereApprovedByName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestingVerification whereApprovedByPosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestingVerification whereApprovedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestingVerification whereApprovedIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestingVerification whereCheckedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestingVerification whereCheckedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestingVerification whereCheckedIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestingVerification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestingVerification whereDocumentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestingVerification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestingVerification whereIdProject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestingVerification whereIsComplete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestingVerification whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestingVerification whereUpdatedAt($value)
 */
	class TestingVerification extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\TukProject
 *
 * @property int $id
 * @property int|null $id_project
 * @property int|null $id_feasibility_test_team_member
 * @property int|null $id_tuk_secretary_member
 * @property string|null $role
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $id_user
 * @property-read \App\Entity\FeasibilityTestTeamMember|null $feasibilityTestTeamMember
 * @property-read \App\Entity\Project|null $project
 * @property-read \App\Entity\TukSecretaryMember|null $tukSecretaryMember
 * @property-read \App\Laravue\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|TukProject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TukProject newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TukProject query()
 * @method static \Illuminate\Database\Eloquent\Builder|TukProject whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TukProject whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TukProject whereIdFeasibilityTestTeamMember($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TukProject whereIdProject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TukProject whereIdTukSecretaryMember($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TukProject whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TukProject whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TukProject whereUpdatedAt($value)
 */
	class TukProject extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\TukSecretaryMember
 *
 * @property int $id
 * @property int|null $id_feasibility_test_team
 * @property string|null $status
 * @property string|null $nik
 * @property string|null $nip
 * @property string|null $name
 * @property string|null $institution
 * @property string|null $email
 * @property string|null $position
 * @property string|null $phone
 * @property string|null $decision_number
 * @property string|null $decision_file
 * @property string|null $sex
 * @property string|null $cv
 * @property int|null $id_province
 * @property int|null $id_district
 * @property string|null $address
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Entity\District|null $district
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\FeasibilityTest[] $feasibilityTest
 * @property-read int|null $feasibility_test_count
 * @property-read \App\Entity\FeasibilityTestTeam|null $feasibilityTestTeam
 * @property-read \App\Entity\Province|null $province
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\TukProject[] $tukProject
 * @property-read int|null $tuk_project_count
 * @method static \Illuminate\Database\Eloquent\Builder|TukSecretaryMember newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TukSecretaryMember newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TukSecretaryMember query()
 * @method static \Illuminate\Database\Eloquent\Builder|TukSecretaryMember whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TukSecretaryMember whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TukSecretaryMember whereCv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TukSecretaryMember whereDecisionFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TukSecretaryMember whereDecisionNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TukSecretaryMember whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TukSecretaryMember whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TukSecretaryMember whereIdDistrict($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TukSecretaryMember whereIdFeasibilityTestTeam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TukSecretaryMember whereIdProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TukSecretaryMember whereInstitution($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TukSecretaryMember whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TukSecretaryMember whereNik($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TukSecretaryMember whereNip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TukSecretaryMember wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TukSecretaryMember wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TukSecretaryMember whereSex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TukSecretaryMember whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TukSecretaryMember whereUpdatedAt($value)
 */
	class TukSecretaryMember extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\UklUplComment
 *
 * @property int $id
 * @property int $id_project
 * @property int $id_user
 * @property string $comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|UklUplComment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UklUplComment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UklUplComment query()
 * @method static \Illuminate\Database\Eloquent\Builder|UklUplComment whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UklUplComment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UklUplComment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UklUplComment whereIdProject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UklUplComment whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UklUplComment whereUpdatedAt($value)
 */
	class UklUplComment extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\Unit
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|Unit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Unit newQuery()
 * @method static \Illuminate\Database\Query\Builder|Unit onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Unit query()
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Unit withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Unit withoutTrashed()
 */
	class Unit extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\VideoTutorial
 *
 * @property int $id
 * @property string $tutorial_type
 * @property string $url_video
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|VideoTutorial newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VideoTutorial newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VideoTutorial query()
 * @method static \Illuminate\Database\Eloquent\Builder|VideoTutorial whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VideoTutorial whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VideoTutorial whereTutorialType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VideoTutorial whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VideoTutorial whereUrlVideo($value)
 */
	class VideoTutorial extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\Webgis
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Webgis newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Webgis newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Webgis query()
 */
	class Webgis extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\WorkflowLog
 *
 * @property int $id
 * @property int|null $id_project
 * @property string|null $transition
 * @property string|null $from_place
 * @property string|null $to_place
 * @property float|null $duration
 * @property float|null $duration_total
 * @property mixed|null $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property-read \App\Entity\Project|null $project
 * @method static \Illuminate\Database\Eloquent\Builder|WorkflowLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WorkflowLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WorkflowLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|WorkflowLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkflowLog whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkflowLog whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkflowLog whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkflowLog whereDurationTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkflowLog whereFromPlace($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkflowLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkflowLog whereIdProject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkflowLog whereToPlace($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkflowLog whereTransition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkflowLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkflowLog whereUpdatedBy($value)
 */
	class WorkflowLog extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\WorkflowState
 *
 * @property int $id
 * @property string $state
 * @property string $public_tracking
 * @property string|null $code
 * @property string|null $flag
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|WorkflowState newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WorkflowState newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WorkflowState query()
 * @method static \Illuminate\Database\Eloquent\Builder|WorkflowState whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkflowState whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkflowState whereFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkflowState whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkflowState wherePublicTracking($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkflowState whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkflowState whereUpdatedAt($value)
 */
	class WorkflowState extends \Eloquent {}
}

namespace App\Entity{
/**
 * App\Entity\WorkflowStep
 *
 * @property int $id
 * @property string $code
 * @property string|null $doc_type
 * @property int $rank
 * @property bool $is_conditional
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|WorkflowStep newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WorkflowStep newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WorkflowStep query()
 * @method static \Illuminate\Database\Eloquent\Builder|WorkflowStep whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkflowStep whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkflowStep whereDocType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkflowStep whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkflowStep whereIsConditional($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkflowStep whereRank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkflowStep whereUpdatedAt($value)
 */
	class WorkflowStep extends \Eloquent {}
}

namespace App\Laravue\Models{
/**
 * Class Permission
 *
 * @package App\Laravue\Models
 * @property int $id
 * @property string $name
 * @property string $guard_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Laravue\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Laravue\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Permission allowed()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission query()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereUpdatedAt($value)
 */
	class Permission extends \Eloquent {}
}

namespace App\Laravue\Models{
/**
 * Class Role
 *
 * @property Permission[] $permissions
 * @property string $name
 * @package App\Laravue\Models
 * @property int $id
 * @property string $guard_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Laravue\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereUpdatedAt($value)
 */
	class Role extends \Eloquent {}
}

namespace App\Laravue\Models{
/**
 * Class User
 *
 * @property string $name
 * @property string $email
 * @property string $password
 * @property Role[] $roles
 * @method static User create(array $user)
 * @package App
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $avatar
 * @property string|null $oss_username
 * @property int $active
 * @property string|null $access_token
 * @property string|null $refresh_token
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Laravue\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAccessToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereOssUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRefreshToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

namespace App{
/**
 * App\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $avatar
 * @property string|null $oss_username
 * @property int $active
 * @property string|null $access_token
 * @property string|null $refresh_token
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAccessToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereOssUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRefreshToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

