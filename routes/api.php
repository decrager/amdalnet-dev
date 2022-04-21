<?php

use App\Http\Controllers\ArcgisServiceController;
use App\Http\Controllers\BaganAlirController;
use App\Http\Controllers\BesaranDampakController;
use App\Http\Controllers\ExportDocument;
use App\Http\Controllers\UklUplCommentController;
use App\Http\Controllers\ProjectMapAttachmentController;
use App\Http\Resources\UserResource;
use App\Laravue\Acl;
use App\Laravue\Faker;
use App\Laravue\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChangeTypeController;
use App\Http\Controllers\FormulatorController;
use App\Http\Controllers\FormulatorTeamController;
use App\Http\Controllers\PieParamController;
use App\Http\Controllers\ImpactIdentificationController;
use App\Http\Controllers\LpjpController;
use App\Http\Controllers\MatriksDampakController;
use App\Http\Controllers\MatriksUklUplController;
use App\Http\Controllers\WebgisController;
use App\Laravue\Models\User;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\KaCommentController;
use App\Http\Controllers\TrackingDocumentController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\PeraturanController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnvironmentalPermitController;
use App\Http\Controllers\OssController;
use App\Http\Controllers\EnvironmentalApprovalController;
use App\Http\Controllers\GovernmentInstitutionController;
use App\Http\Controllers\MediumLowUklUplTemplateController;
use App\Http\Controllers\EnvironmentalManagemenSopController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\VideoTutorialController;
use App\Http\Controllers\ProjectOtherComponentController;
use App\Http\Controllers\SubProjectComponentController;
use App\Http\Controllers\ProjectKegiatanLainSekitarController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::apiResource('home', 'HomeController');
Route::apiResource('webgis', 'WebgisController');

Route::namespace('Api')->group(function () {
    Route::post('auth/login', 'AuthController@login');
    // check user by email
    Route::get('auth/is-email-registered', 'AuthController@isEmailRegistered');
    // OSS routes
    Route::get('auth/userinfo-oss', 'AuthController@getUserInfo');
    Route::get('auth/receive-token', 'AuthController@receiveToken');
    Route::post('auth/validate-token', 'AuthController@validateToken');
    Route::post('auth/update-token', 'AuthController@updateToken');
    Route::post('auth/login-oss', 'AuthController@loginOss');
    Route::post('auth/register-oss', 'AuthController@registerOss');

    Route::group(['middleware' => 'auth:sanctum'], function () {
        // Auth routes
        Route::get('auth/user', 'AuthController@user');
        Route::post('auth/logout', 'AuthController@logout');

        Route::get('/user', function (Request $request) {
            return (new UserResource($request->user()))
                ->response()
                ->header('Cache-Control', 'no-cache, max-age=0, no-store, private, must-revalidate');
        });

        // Api resource routes
        Route::apiResource('roles', 'RoleController')->middleware('permission:' . Acl::PERMISSION_MANAGE_PERMISSION);
        Route::apiResource('users', 'UserController')->middleware('permission:' . Acl::PERMISSION_MANAGE_USER);
        Route::apiResource('permissions', 'PermissionController')->middleware('permission:' . Acl::PERMISSION_MANAGE_PERMISSION);

        Route::get('doc-uklupl', [ExportDocument::class, 'ExportUklUpl']);

        // Custom routes
        Route::put('users/{user}', 'UserController@update');
        Route::put('uploadAvatar/{user}', 'UserController@updateAvatar');
        Route::get('users/{user}/permissions', 'UserController@permissions')->middleware('permission:' . Acl::PERMISSION_MANAGE_PERMISSION);
        Route::put('users/{user}/permissions', 'UserController@updatePermissions')->middleware('permission:' . Acl::PERMISSION_MANAGE_PERMISSION);
        Route::get('roles/{role}/permissions', 'RoleController@permissions')->middleware('permission:' . Acl::PERMISSION_MANAGE_PERMISSION);
    });
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('workspace/session/init', 'WorkspaceController@sessionInit');
    Route::get('workspace/config/{id}', 'WorkspaceController@getConfig');
});

Route::post('workspace/template/import', 'WorkspaceController@importTemplate');
Route::post('workspace/document/track', 'WorkspaceController@track');
Route::post('workspace/document/upload', 'WorkspaceController@upload');
Route::post('workspace/document/download', 'WorkspaceController@download');
Route::post('workspace/document/convert', 'WorkspaceController@convert');
Route::post('workspace/document/delete', 'WorkspaceController@delete');
Route::post('workspace/document/assets', 'WorkspaceController@assets');
Route::post('workspace/document/files', 'WorkspaceController@files');

// Fake APIs
Route::get('/table/list', function () {
    $rowsNumber = mt_rand(20, 30);
    $data = [];
    for ($rowIndex = 0; $rowIndex < $rowsNumber; $rowIndex++) {
        $row = [
            'author' => Faker::randomString(mt_rand(5, 10)),
            'display_time' => Faker::randomDateTime()->format('Y-m-d H:i:s'),
            'id' => mt_rand(100000, 100000000),
            'pageviews' => mt_rand(100, 10000),
            'status' => Faker::randomInArray(['deleted', 'published', 'draft']),
            'title' => Faker::randomString(mt_rand(20, 50)),
        ];

        $data[] = $row;
    }

    return response()->json(new JsonResponse(['items' => $data]));
});

Route::get('/orders', function () {
    $rowsNumber = 8;
    $data = [];
    for ($rowIndex = 0; $rowIndex < $rowsNumber; $rowIndex++) {
        $row = [
            'order_no' => 'LARAVUE' . mt_rand(1000000, 9999999),
            'price' => mt_rand(10000, 999999),
            'status' => Faker::randomInArray(['success', 'pending']),
        ];

        $data[] = $row;
    }

    return response()->json(new JsonResponse(['items' => $data]));
});

Route::get('/articles', function () {
    $rowsNumber = 10;
    $data = [];
    for ($rowIndex = 0; $rowIndex < $rowsNumber; $rowIndex++) {
        $row = [
            'id' => mt_rand(100, 10000),
            'display_time' => Faker::randomDateTime()->format('Y-m-d H:i:s'),
            'title' => Faker::randomString(mt_rand(20, 50)),
            'author' => Faker::randomString(mt_rand(5, 10)),
            'comment_disabled' => Faker::randomBoolean(),
            'content' => Faker::randomString(mt_rand(100, 300)),
            'content_short' => Faker::randomString(mt_rand(30, 50)),
            'status' => Faker::randomInArray(['deleted', 'published', 'draft']),
            'forecast' => mt_rand(100, 9999) / 100,
            'image_uri' => 'https://via.placeholder.com/400x300',
            'importance' => mt_rand(1, 3),
            'pageviews' => mt_rand(10000, 999999),
            'reviewer' => Faker::randomString(mt_rand(5, 10)),
            'timestamp' => Faker::randomDateTime()->getTimestamp(),
            'type' => Faker::randomInArray(['US', 'VI', 'JA']),

        ];

        $data[] = $row;
    }

    return response()->json(new JsonResponse(['items' => $data, 'total' => mt_rand(1000, 10000)]));
});

Route::get('articles/{id}', function ($id) {
    $article = [
        'id' => $id,
        'display_time' => Faker::randomDateTime()->format('Y-m-d H:i:s'),
        'title' => Faker::randomString(mt_rand(20, 50)),
        'author' => Faker::randomString(mt_rand(5, 10)),
        'comment_disabled' => Faker::randomBoolean(),
        'content' => Faker::randomString(mt_rand(100, 300)),
        'content_short' => Faker::randomString(mt_rand(30, 50)),
        'status' => Faker::randomInArray(['deleted', 'published', 'draft']),
        'forecast' => mt_rand(100, 9999) / 100,
        'image_uri' => 'https://via.placeholder.com/400x300',
        'importance' => mt_rand(1, 3),
        'pageviews' => mt_rand(10000, 999999),
        'reviewer' => Faker::randomString(mt_rand(5, 10)),
        'timestamp' => Faker::randomDateTime()->getTimestamp(),
        'type' => Faker::randomInArray(['US', 'VI', 'JA']),

    ];

    return response()->json(new JsonResponse($article));
});

Route::get('articles/{id}/pageviews', function ($id) {
    $pageviews = [
        'PC' => mt_rand(10000, 999999),
        'Mobile' => mt_rand(10000, 999999),
        'iOS' => mt_rand(10000, 999999),
        'android' => mt_rand(10000, 999999),
    ];
    $data = [];
    foreach ($pageviews as $device => $pageview) {
        $data[] = [
            'key' => $device,
            'pv' => $pageview,
        ];
    }

    return response()->json(new JsonResponse(['pvData' => $data]));
});

Route::apiResource('project-fields', 'ProjectFieldController');
Route::apiResource('provinces', 'ProvinceController');
Route::apiResource('districts', 'DistrictController');
Route::apiResource('business', 'BusinessController');
Route::apiResource('kbli-env-params', 'BusinessEnvParamController');
Route::apiResource('business-env-params', 'BusinessEnvParamController');
Route::apiResource('projects', 'ProjectController');
Route::apiResource('formulator-teams', 'FormulatorTeamController');
Route::apiResource('environmental-experts', 'EnvironmentalExpertController');
Route::apiResource('oss-projects', 'OssProjectController');
Route::apiResource('responder-types', 'ResponderTypeController');
Route::apiResource('feedbacks', 'FeedbackController');
Route::apiResource('support-docs', 'SupportDocController');
Route::apiResource('announcements', 'AnnouncementController');
Route::apiResource('initiators', 'InitiatorController');
Route::apiResource('lpjp', 'LpjpController');
Route::apiResource('formulators', 'FormulatorController');
Route::apiResource('expert-banks', 'ExpertBankController');
Route::apiResource('public-consultations', 'PublicConsultationController');
Route::apiResource('rona-awals', 'RonaAwalController');
Route::apiResource('components', 'ComponentController');
Route::apiResource('kegiatan-lain-sekitar', 'KegiatanLainSekitarController');
Route::apiResource('project-stages', 'ProjectStageController');
Route::apiResource('sops', 'SopController');
Route::apiResource('component-types', 'ComponentTypeController');
Route::apiResource('app-params', 'AppParamController');
Route::get('initiatorsByEmail', 'InitiatorController@showByEmail');
Route::get('formulatorsByEmail', 'FormulatorController@showByEmail');
Route::get('lpjpsByEmail', 'LpjpController@showByEmail');
Route::get('expertByEmail', 'ExpertBankController@showByEmail');
Route::apiResource('impact-identifications', 'ImpactIdentificationController');
Route::apiResource('params', 'ParamController');
Route::apiResource('units', 'UnitController');
Route::apiResource('project-components', 'ProjectComponentController');
Route::apiResource('project-rona-awals', 'ProjectRonaAwalController');
Route::apiResource('project-kegiatan-lain-sekitar', 'ProjectKegiatanLainSekitarController');
Route::apiResource('impact-kegiatan-lain-sekitar', 'ImpactKegiatanLainSekitarController');
Route::apiResource('change-types', 'ChangeTypeController');
Route::apiResource('institutions', 'InstitutionController');
Route::apiResource('andal-composing', 'AndalComposingController');
Route::apiResource('matriks-rkl', 'MatriksRKLController');
Route::apiResource('matriks-rpl', 'MatriksRPLController');
Route::apiResource('testing-verification', 'TestingVerificationController');
Route::apiResource('testing-meeting', 'TestingMeetingController');
Route::apiResource('meeting-report', 'MeetingReportController');
Route::apiResource('test-verif-rkl-rpl', 'TestVerifRKLRPLController');
Route::apiResource('test-meet-rkl-rpl', 'TestMeetRKLRPLController');
Route::apiResource('meet-report-rkl-rpl', 'MeetReportRKLRPLController');
Route::apiResource('feasibility-test', 'FeasibilityTestController');
Route::apiResource('skkl', 'SKKLController');
Route::apiResource('skkl-final', 'SKKLFinalController');
Route::apiResource('impact-studies', 'ImpactStudyController');
Route::get('ukl-upl-comment/{id}', [UklUplCommentController::class, 'index']);
Route::post('ukl-upl-comment', [UklUplCommentController::class, 'store']);
Route::get('ka-docx/{id}', [ExportDocument::class, 'KADocx']);
Route::apiResource('scoping', 'ScopingController');
Route::apiResource('sub-project-components', 'SubProjectComponentController');
Route::apiResource('sub-project-rona-awals', 'SubProjectRonaAwalController');
Route::get('subproject-components', [SubProjectComponentController::class,'subProjectComponents']);
Route::get('subproject-hues', [SubProjectRonaAwalController::class,'subProjectHues']);
Route::get('bagan-alir/{id}', [BaganAlirController::class, 'baganAlirUklUpl']);
Route::get('project-map', [ProjectMapAttachmentController::class, 'index']);
Route::get('map-pdf', [ProjectMapAttachmentController::class, 'getMapPdf']);
Route::get('map-geojson-merge', [ProjectMapAttachmentController::class, 'getMergeGeojson']);
Route::get('map-geojson', [ProjectMapAttachmentController::class, 'getGeojson']);
Route::get('projects-geom', [ProjectMapAttachmentController::class, 'getProjectByGeom']);
Route::get('change-types', [ChangeTypeController::class, 'index']);
Route::get('pie-params', [PieParamController::class, 'index']);
Route::post('upload-map', [ProjectMapAttachmentController::class, 'post']);
Route::post('upload-maps', [ProjectMapAttachmentController::class, 'store']);
Route::get('download-map/{id}', [ProjectMapAttachmentController::class, 'download']);
Route::apiResource('manage-approach', 'ManageApproachController');
Route::post('upload-ka-doc', [ExportDocument::class, 'saveKADoc']);
Route::get('pie-entries', [ImpactIdentificationController::class, 'pieEntries']);
Route::post('change-types', [ChangeTypeController::class, 'addChangeType']);
Route::get('get-document-ka/{id}', [ExportDocument::class, 'getDocKA']);
Route::get('form-ka-pdf/{id}', [ExportDocument::class, 'ExportKAPdf']);
Route::apiResource('andal-clone', 'AndalCloneController');
Route::get('map/{id}', [ProjectMapAttachmentController::class, 'show']);
Route::get('maps', [ProjectMapAttachmentController::class, 'getProjectMap']);
Route::apiResource('public-spt', 'PublicSPTController');
Route::get('lpjp-teams', [LpjpController::class, 'getFormulator']);
Route::get('form-teams', [FormulatorTeamController::class, 'getAll']);
Route::get('matriks-dampak/table/{id}', [MatriksDampakController::class, 'getTable']);
Route::get('matriks-dampak/table-dph/{id}', [MatriksDampakController::class, 'getTableDph']);
Route::get('matriks-dampak/rona-mapping/{id}', [MatriksDampakController::class, 'requestRonaMapping']);
Route::get('formulators-all', [FormulatorController::class, 'getFormulatorName']);
Route::get('project-maps', [WebgisController::class, 'index']);
Route::get('eval-dampak', [BaganAlirController::class, 'evalDampak']);
Route::get('dokumen-ukl-upl/{id}', [ExportDocument::class, 'uklUpl']);
Route::get('dokumen-ukl-upl-pdf/{id}', [ExportDocument::class, 'exportUklUplPdf']);
Route::apiResource('ka-comment', 'KaCommentController');
Route::apiResource('employee-tuk', 'EmployeeTUKController');
Route::apiResource('tuk-management', 'TUKManagementController');
Route::apiResource('regions', 'RegionController');
Route::apiResource('authorities', 'AuthorityController');
Route::get('timeline', [ProjectController::class, 'timeline']);
Route::apiResource('tuk-project', 'TukProjectController');
Route::apiResource('reset-password', 'ResetPasswordController');

// Arcgis Service
Route::get('arcgis-services', [ArcgisServiceController::class, 'arcgisServiceList']);
Route::get('arcgis-service-categories', [ArcgisServiceController::class, 'arcgisServiceCategoryList']);
Route::get('arcgis-service/{id}', [ArcgisServiceController::class, 'showArcgisServiceList']);
Route::get('arcgis-service-category/{id}', [ArcgisServiceController::class, 'showArcgisServiceCategoryList']);
Route::post('arcgis-service', [ArcgisServiceController::class, 'createArcgisService']);
Route::post('arcgis-service-category', [ArcgisServiceController::class, 'createArcgisServiceCategory']);
Route::patch('arcgis-service/{id}', [ArcgisServiceController::class, 'updateArcgisService']);
Route::delete('arcgis-service/{id}', [ArcgisServiceController::class, 'deleteAcrgisService']);
Route::delete('arcgis-service-category/{id}', [ArcgisServiceController::class, 'deleteAcrgisServiceCategory']);

Route::get('besaran-dampak/list/{id}', [BesaranDampakController::class, 'getList']);
Route::get('matriks-ukl-upl/table-ukl/{id}', [MatriksUklUplController::class, 'getTableUkl']);
Route::get('matriks-ukl-upl/table-upl/{id}', [MatriksUklUplController::class, 'getTableUpl']);
Route::get('matriks-ukl-upl/is-form-complete/{id}', [MatriksUklUplController::class, 'getIsFormComplete']);
Route::get('matriks-ukl-upl/get-project-marking/{id}', [MatriksUklUplController::class, 'getProjectMarking']);
Route::apiResource('env-manage-plans', 'EnvManagePlanController');
Route::apiResource('env-monitor-plans', 'EnvMonitorPlanController');
Route::apiResource('env-manage-docs', 'EnvManageDocController');
Route::apiResource('env-monitor-plans', 'EnvMonitorPlanController');
Route::apiResource('public-questions', 'PublicQuestionController');
Route::apiResource('ka-reviews', 'KaReviewController');
Route::apiResource('project-other-components', 'ProjectOtherComponentController');
// notification
Route::get('mark-all-read/{user}', function (User $user) {
    $user->unreadNotifications->markAsRead();
    event(new \App\Events\NotificationEvent());
    return response(['message' => 'done']);
});
Route::get('get-districts-by-name', [DistrictController::class, 'getDistrictByName']);
Route::get('announcement-by-filter', [AnnouncementController::class, 'getAnnouncementByFilter']);
Route::apiResource('policys', 'PolicyController');
Route::post('policys/update', [PolicyController::class, 'update']);
Route::get('policys/delete/{id}', [PolicyController::class, 'destroy']);
Route::get('peraturan', [PeraturanController::class, 'index']);
Route::post('peraturan', [PeraturanController::class, 'store']);
Route::post('peraturan/update', [PeraturanController::class, 'update']);
Route::get('peraturan/delete/{id}', [PeraturanController::class, 'destroy']);
Route::apiResource('regulations', 'RegulationsController');
Route::apiResource('ossNibs', 'OssNibController');

Route::apiResource('materials', 'MaterialController');
Route::post('materials', [MaterialController::class, 'store']);
Route::post('materials/update', [MaterialController::class, 'update']);
Route::get('materials/delete/{id}', [MaterialController::class, 'destroy']);

Route::get('tracking-document/{id}', [TrackingDocumentController::class, 'index']);
// dpdph master-detail
Route::get('impacts', [ImpactIdentificationController::class, 'getImpacts']);
Route::post('impacts', [ImpactIdentificationController::class, 'saveImpacts']);
// dashboard
Route::get('proposal-count', [DashboardController::class, 'proposalCount']);
Route::get('latest-activities', [DashboardController::class, 'latestActivities']);
Route::group(['prefix' => 'dashboard'], function($r) {
    $r->get('permit-authority', [DashboardController::class, 'permitAuthority']);
    $r->get('status', [DashboardController::class, 'status']);
    $r->get('initiator', [DashboardController::class, 'initiator']);
    $r->get('chart', [DashboardController::class, 'chart']);
});

Route::get('environmental-permit', [EnvironmentalPermitController::class, 'index']);
Route::post('environmental-permit', [EnvironmentalPermitController::class, 'store']);
Route::post('environmental-permit/update', [EnvironmentalPermitController::class, 'update']);
Route::get('environmental-permit/delete/{id}', [EnvironmentalPermitController::class, 'destroy']);

//OSS consume
Route::get('oss/sectorByKbli/{kbli}', [OssController::class, 'sectorByKbli']);
Route::get('oss/getField', [OssController::class, 'getField']);
Route::get('oss/getParameterByFieldId/{id}', [OssController::class, 'getParamByFieldId']);
Route::post('oss/calculateDoc', [OssController::class, 'calculateDoc']);
Route::post('oss/receiveNib', [OssController::class, 'receiveNib']);
Route::post('oss/receiveLicenseStatus', [OssController::class, 'receiveLicenseStatus']);

Route::get('environmental-approval', [EnvironmentalApprovalController::class, 'index']);
Route::post('environmental-approval', [EnvironmentalApprovalController::class, 'store']);
Route::post('environmental-approval/update', [EnvironmentalApprovalController::class, 'update']);
Route::get('environmental-approval/delete/{id}', [EnvironmentalApprovalController::class, 'destroy']);

Route::get('template-ukl-upl-medium-low', [MediumLowUklUplTemplateController::class, 'index']);
Route::post('template-ukl-upl-medium-low', [MediumLowUklUplTemplateController::class, 'store']);
Route::post('template-ukl-upl-medium-low/update', [MediumLowUklUplTemplateController::class, 'update']);
Route::get('template-ukl-upl-medium-low/delete/{id}', [MediumLowUklUplTemplateController::class, 'destroy']);

Route::get('env-management-sop', [EnvironmentalManagemenSopController::class, 'index']);
Route::post('env-management-sop', [EnvironmentalManagemenSopController::class, 'store']);
Route::post('env-management-sop/update', [EnvironmentalManagemenSopController::class, 'update']);
Route::get('env-management-sop/delete/{id}', [EnvironmentalManagemenSopController::class, 'destroy']);

Route::get('tutorial-video', [VideoTutorialController::class, 'index']);
Route::post('tutorial-video', [VideoTutorialController::class, 'store']);
Route::post('tutorial-video/update', [VideoTutorialController::class, 'update']);
Route::get('tutorial-video/delete/{id}', [VideoTutorialController::class, 'destroy']);


// Master Government Insitution
Route::apiResource('government-institution', 'GovernmentInstitutionController');

// Route::put('activateUser/{user}', 'UserController@updateActive');
Route::put('activateUser/{user}', function (User $user) {
    if ($user === null) {
        return response()->json(['error' => 'User not found'], 404);
    }

    if ($user->active == 1){
        return response()->json(['error' => 'User not active not found'], 404);
    }

    $user->active = 1;
    $user->save();

    return response(['message' => 'done']);
});


// Route::get('/testemail', function () {

//     $data = User::find(236);

//         $billData = [
//             'name' => '#007 Bill',
//             'body' => 'You have received a new bill.',
//             'thanks' => 'Thank you',
//             'text' => '$600',
//             'offer' => url('/'),
//             'bill_id' => 30061
//         ];

//         Notification::send($data, new BillingNotification($billData));

//         dd('Bill notification has been sent!');
// });
