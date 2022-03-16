<?php

namespace App\Http\Controllers;

use App\Entity\Business;
use App\Entity\BusinessEnvParam;
use App\Entity\OssNib;
use App\Entity\Project;
use App\Services\OssService;
use Error;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use stdClass;

class OssController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sectorByKbli($kbli)
    {
        $business = Business::where('value', $kbli)->first();

        if (!$business) {
            return response()->json(['errors' => 'KBLI Tidak Ditemukan Silahkan Coba KBLI Lainnya', 404]);
        }

        return Business::where('parent_id', $business->id)->get();
    }

    /**
     * Display a listing of the field.
     *
     * @return \Illuminate\Http\Response
     */
    public function getField(Request $request)
    {
        if (!$request->kbli || !$request->sector) {
            return response()->json(['errors' => 'kbli atau sector dibutuhkan', 400]);
        }

        $sector = Business::where('value', $request->sector)->first();

        if (!$sector) {
            return response()->json(['errors' => 'Sector Tidak Ditemukan Silahkan Coba Sector Lainnya', 404]);
        }

        $business = Business::findOrFail($sector->parent_id);

        return Business::where('parent_id', $sector->id)->get();
    }

    public function calculateDoc(Request $request)
    {
        $params = $request->all();

        $result = '';
        $type = $request->type;

        $choosenProject = '';
        $choosenProjectAmdalType = '';

        foreach ($params['listSubProject'] as $key => $subPro) {
            $sresult = '';
            $sresult_risk = '';
            $samdal_type = '';
            // validate kbli
            $business = Business::where('value', $subPro['kbli'])->first();

            if (!$business) {
                return response()->json(['errors' => 'KBLI Tidak Ditemukan Silahkan Coba KBLI Lainnya', 404]);
            }

            $sector = Business::find($subPro['sector_id']);

            if (!$sector) {
                return response()->json(['errors' => 'Sector Tidak Valid', 400]);
            }

            $field = Business::find($subPro['field_id']);

            if (!$field) {
                return response()->json(['errors' => 'Field Tidak Valid', 400]);
            }

            $value = new stdClass();


            // validating doc by param
            foreach ($subPro['params'] as $keyy => $subPar) {
                //get business env param
                $beps = BusinessEnvParam::where('business_id', $field->id)->where('id_param', $subPar['id_param'])->get();
                foreach ($beps as $bep) {
                    $item = preg_replace("/[\[\"\]]/", '', $bep->condition);
                    $items = explode(',', $item);

                    $prefix = $subPar['value'] . ' ';
                    $item_flat = $prefix . implode("::" . $prefix, $items);

                    $items = explode("::", $item_flat);

                    $bep['conditions'] = $items;

                    // check result doc
                    $tempStatus = true;

                    foreach ($bep['conditions'] as $cond) {
                        if ($this->checkIfTrue($cond)) {
                            $tempStatus = $tempStatus && true;
                        } else {
                            $tempStatus = $tempStatus && false;
                        }
                    }

                    if ($tempStatus) {
                        $value->result = $bep->doc_req;
                        $value->result_risk = $bep->risk_level;
                        $value->amdal_type = $bep->amdal_type;
                    }
                }

                $params['listSubProject'][$key]['params'][$keyy]['result'] = $value;

                // validate doc from all params
                if ($value->result === 'AMDAL') {
                    $sresult = $value->result;
                    $sresult_risk = $value->result_risk;
                    $samdal_type = $value->amdal_type;
                    break;
                } else if ($value->result === 'UKL-UPL') {
                    $sresult = $value->result;
                    $sresult_risk = $value->result_risk;
                } else if ($value->result === 'SPPL' && $sresult !== 'UKL-UPL') {
                    $sresult = $value->result;
                    $sresult_risk = $value->result_risk;
                }
            }

            $params['listSubProject'][$key]['result'] = $sresult;
            $params['listSubProject'][$key]['result_risk'] = $sresult_risk;
            $params['listSubProject'][$key]['amdal_type'] = $samdal_type;

            //validate tunggal or terpadu
            if ($params['study_approach'] === 'tunggal') {
                if ($subPro['jenis'] === 'utama') {
                    if ($sresult === 'AMDAL') {
                        $choosenProject = 'AMDAL';
                        $choosenProjectAmdalType = $samdal_type;
                    } else if ($sresult === 'UKL-UPL' && ($choosenProject === '' || $choosenProject !== 'AMDAL')) {
                        $choosenProject = 'UKL-UPL';
                    } else if ($sresult === 'SPPL' && ($choosenProject !== 'AMDAL' && $choosenProject !== 'UKL-UPL')) {
                        $choosenProject = 'SPPL';
                    }
                }
            } else if ($params['study_approach'] === 'terpadu') {
                if ($sresult === 'AMDAL') {
                    $choosenProject = 'AMDAL';
                    $choosenProjectAmdalType = $samdal_type;
                } else if ($sresult === 'UKL-UPL' && $choosenProject !== 'AMDAL') {
                    $choosenProject = 'UKL-UPL';
                } else if ($sresult === 'SPPL' && $choosenProject !== 'AMDAL' && $choosenProject !== 'UKL-UPL') {
                    $choosenProject = 'SPPL';
                }
            }
        }

        $params['final_result'] = $choosenProject;
        $params['final_result_amdal_type'] = $choosenProjectAmdalType;
        return $params;
    }

    public function checkIfTrue($item)
    {
        $splits = explode(" ", $item);

        // return floatval($splits[0]) >= floatval($splits[2]);
        // const [data1, operator, data2] = item.split(/\s+/);

        switch ($splits[1]) {
            case '<=':
                return floatval($splits[0]) <= floatval($splits[2]);
            case '>=':
                return floatval($splits[0]) >= floatval($splits[2]);
            case '<':
                return floatval($splits[0]) < floatval($splits[2]);
            case '>':
                return floatval($splits[0]) > floatval($splits[2]);

            default:
                break;
        }
    }

    public function getParamByFieldId($id){
        $bparam = BusinessEnvParam::select('business_env_params.business_id','params.id as params_id', 'params.name as params_name', 'units.id as unit_id', 'units.name as unit_name')
        ->leftJoin('params', 'business_env_params.id_param', '=', 'params.id')
        ->leftJoin('units', 'business_env_params.id_unit', '=', 'units.id')
        ->where('business_id',$id)->where('id_param', '!=', 0)->get();



        return $bparam;
    }

    public function receiveNib(Request $request)
    {
        if (!$request->accepts(['application/json'])) {
            return response()->json([
                'responreceiveNIB' => [
                    'status' => false,
                    'keterangan' => 'Data yang diterima bukan JSON.',
                ]
            ], 400);
        }
        $validator = Validator::make(
            $request->all(),
            [
                'dataNIB' => 'required',
            ]
        );
        $token = $request->bearerToken();

        if ($validator->fails()) {
            return response()->json([
                'responreceiveNIB' => [
                    'status' => false,
                    'keterangan' => 'Format data JSON tidak valid.',
                ]
            ], 400);
        }
        $validated = $request->only('dataNIB');
        $data = $validated['dataNIB'];
        $nib = $data['nib'];
        // check token
        // $sha1 = sha1(env('OSS_REQUEST_USERNAME') . env('OSS_REQUEST_PASSWORD') . $nib . date('Ymd'));
        // if ($token != $sha1) {
        //     return response()->json([
        //         'status' => 401,
        //         'message' => 'Token tidak valid.',
        //         'submitted_token' => $token,
        //         'submitted_data' => $request->getContent(),
        //         // 'submitted_json' => json_decode($request->getContent()),
        //     ], 401);
        // }
        $existing = OssNib::where('nib', $nib)->first();
        $saved = false;
        $errorMessage = '-';
        DB::beginTransaction();
        if ($existing) {
            if ($existing->nib_updated_date != $data['tgl_perubahan_nib']) {
                // update
                $existing->nib_submit_date = $data['tgl_pengajuan_nib'];
                $existing->nib_published_date = $data['tgl_terbit_nib'];
                $existing->nib_updated_date = $data['tgl_perubahan_nib'];
                $existing->oss_id = $data['oss_id'];
                $existing->id_izin = $data['id_izin'];
                $existing->kd_izin = $data['kd_izin'];
                $existing->company_name = $data['nama_perseroan'];
                $existing->company_email = $data['email_perusahaan'];
                $existing->json_content = $data;
                try {
                    $existing->save();
                    DB::commit();
                    $saved = true;
                } catch (Exception $e) {
                    $errorMessage = $e->getMessage();
                }
            }            
        } else {
            // insert
            try {
                $created = OssNib::create([
                    'nib' => $data['nib'],
                    'nib_submit_date' => $data['tgl_pengajuan_nib'],
                    'nib_published_date' => $data['tgl_terbit_nib'],
                    'nib_updated_date' => $data['tgl_perubahan_nib'],
                    'oss_id' => $data['oss_id'],
                    'id_izin' => $data['id_izin'],
                    'kd_izin' => $data['kd_izin'],
                    'company_name' => $data['nama_perseroan'],
                    'company_email' => $data['email_perusahaan'],
                    'json_content' => $data,
                ]);
                if ($created) {
                    DB::commit();
                    $saved = true;
                } else {
                    DB::rollBack();
                }
            } catch (Exception $e) {
                $errorMessage = $e->getMessage();
            }
        }
        if ($saved) {
            return response()->json([
                'responreceiveNIB' => [
                    'status' => true,
                    'keterangan' => 'Data NIB berhasil disimpan.',
                ]
            ], 200);
        }
        return response()->json([
            'responreceiveNIB' => [
                'status' => false,
                'keterangan' => 'Gagal menyimpan data NIB. Error msg: ' . $errorMessage,
            ]
        ], 500);
    }

    public function receiveLicenseStatus(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'dataLicenseStatus' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'status' => 400,
                'message' => 'Invalid JSON input'
            ], 400);
        }
        $validated = $request->only('dataLicenseStatus');
        $project = Project::findOrFail($validated['dataLicenseStatus']['id_project']);
        $success = OssService::receiveLicenseStatus($project, $validated['dataLicenseStatus']['status_code']);
        if ($success) {
            return response()->json([
                'code' => 200,
                'status' => 200,
                'message' => 'ReceiveLicenseStatus Success',
            ], 200);
        }
        return response()->json([
            'code' => 500,
            'status' => 500,
            'message' => 'ReceiveLicenseStatus Failed',
        ], 500);
    }
}
