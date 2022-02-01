<?php

namespace App\Http\Controllers;

use App\Entity\Business;
use App\Entity\BusinessEnvParam;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
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
        }

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
}
