<?php
/*
 * Service for calling OSS API
 */

namespace App\Services;

use App\Entity\Initiator;
use App\Entity\OssNib;
use App\Entity\SubProject;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class OssService
{
    public static function receiveLicense($project = null, $fileUrl = '')
    {
        $dummy = [
            'IZINFINAL' => [
                'nib' => '9120309952269',
                'id_produk' => '01234567890',
                'id_proyek' => 'R-202110220716199227111',
                'oss_id' => 'P-201912311331538836804',
                'id_izin' => "I-202202231710411927449",
                'kd_izin' => "029000000001",
                'kd_daerah' => "1604000000",
                'kewenangan' => "01",
                'nomor_izin' => null, // di luar amdalnet
                'tgl_terbit_izin' => '2022-03-08',
                'tgl_berlaku_izin' => '2022-03-08',
                'nama_ttd' => 'GAYA ANDALAN KAMI', // diisi di OSS
                'nip_ttd' => '927824979067000', // diisi di OSS
                'jabatan_ttd' => 'Pemrakarsa', // diisi di OSS
                'status_izin' => '50',
                'file_izin' => Storage::url('skkl/test-skkl.pdf'),
                'keterangan' => 'Diterima',
                'file_lampiran' => Storage::url('skkl/test-Andal.pdf'),
                'nomenklatur_nomor_izin' => '',
                'bln_berlaku_izin' => '1',
                'thn_berlaku_izin' => '5',
                'data_pnbp' => [
                    'kd_akun' => '12345678',
                    'kd_penerimaan' => '99999999',
                    'nominal' => '20000000000',
                ],
            ]
        ];
    
        // Lookup table oss_nibs
        // filter by NIB
        // get json_content, parse, filter data_proyek by projects.id_project
        $initiator = Initiator::find($project->id_applicant);
        if (!$initiator) {
            Log::error('Initiator not found');
            return false;
        }
        $ossNib = OssNib::where('nib', $initiator->nib)->first();
        if (!$ossNib) {
            Log::error('OSSNib not found');
            return false;
        }
        $jsonContent = $ossNib->json_content;
        $subProjects = $jsonContent['data_proyek'];
        $subProjectsAmdalnet = SubProject::where('id_project', $project->id)->get();
        if (empty($subProjectsAmdalnet)) {
            Log::error('Sub projects not found');
            return false;
        }
        $subProjectsAmdalnetIdProyeks = [];
        foreach ($subProjectsAmdalnet as $sp) {
            array_push($subProjectsAmdalnetIdProyeks, $sp->id_proyek);
        }

        foreach ($subProjects as $dataProject) {
            $dataProduct = null;
            $idProduct = 0;
            if (count($dataProject['data_proyek_produk']) > 0) {
                $dataProduct = $dataProject['data_proyek_produk'][0];
                $idProduct = $dataProduct['id_produk'];
            }
            if (in_array($dataProject['id_proyek'], $subProjectsAmdalnetIdProyeks)) {
                // call endpoint receiveLicense
                $statusIzin = OssService::getStatusIzin($project, $fileUrl);
                $data = [
                    'IZINFINAL' => [
                        'nib' => $initiator->nib,
                        'id_produk' => $idProduct,
                        'id_proyek' => $dataProject['id_proyek'],
                        'oss_id' => $ossNib->oss_id,
                        'id_izin' => $ossNib->id_izin,
                        'kd_izin' => $ossNib->kd_izin,
                        'kd_daerah' => $ossNib->kd_daerah,
                        'kewenangan' => $ossNib->kewenangan,
                        'nomor_izin' => null, // NIP pemroses (TUK) ? Need confirm
                        'tgl_terbit_izin' => null,  // nambah kolom di tabel amdalnet
                        'tgl_berlaku_izin' => (string)$project->updated_at,
                        'nama_ttd' => null, // nambah kolom di tabel amdalnet
                        'nip_ttd' => null, // nambah kolom di tabel amdalnet
                        'jabatan_ttd' => null, // nambah kolom di tabel amdalnet
                        'status_izin' => $statusIzin['status_izin'],
                        'file_izin' => $statusIzin['file_izin'],
                        'keterangan' => $statusIzin['keterangan'],
                        'file_lampiran' => $statusIzin['file_lampiran'],
                        'nomenklatur_nomor_izin' => $statusIzin['nomenklatur_nomor_izin'],
                        'bln_berlaku_izin' => $statusIzin['bln_berlaku_izin'],
                        'thn_berlaku_izin' => $statusIzin['thn_berlaku_izin'],
                        'data_pnbp' => null,
                    ]
                ];
                // print_r($data);
                $response = Http::withHeaders([
                    'user_key' => env('OSS_USER_KEY'),
                ])->post(env('OSS_ENDPOINT') . '/kl/rba/receiveLicense', $data);
                $respJson = $response->json();
                print_r($respJson);
                // if ((int)$respJson['responreceiveLicense']['kode'] == 200) {

                // }
            }
        }
        return true;
    }

    private static function getStatusIzin($project, $fileUrl)
    {
        return [
            'status_izin' => '50',
            'file_izin' => $fileUrl, // skkl
            'keterangan' => 'Diterima',
            'file_lampiran' => null,
            'nomenklatur_nomor_izin' => null,
            'bln_berlaku_izin' => null,
            'thn_berlaku_izin' => null,
        ];
    }

    public static function receiveLicenseStatus($project = null, $fileUrl = '')
    {
        $dummy = [
            'IZINSTATUS' => [
                'nib' => '9120309952269',
                'id_produk' => '01234567890',
                'id_proyek' => 'R-202110220716199227111',
                'oss_id' => 'P-201912311331538836804',
                'id_izin' => "I-202202231710411927449",
                'kd_izin' => "029000000001",
                'kd_instansi' => "1604000000", // kode sektor (032)
                'kd_status' => '10',
                'tgl_status' => '2022-03-08',
                'nip_status' => null, // NULL
                'nama_status' => 'Dokumen Lengkap',
                'keterangan' => 'Dokumen Lengkap', // tambahan keterangan utk pelaku usaha di OSS (nama_status di amdalnet?)
                'data_pnbp' => [ // perlu diisi ga? NULL
                    'kd_akun' => '12345678',
                    'kd_penerimaan' => '99999999',
                    'kd_billing' => '987654321',
                    'tgl_billing' => '2022-03-08',
                    'tgl_expire' => '2022-04-08',
                    'nominal' => '2000000000',
                    'url_dokumen' => Storage::url('formulir/test-KA.pdf'),
                ],
            ],
        ];

        $response = Http::withHeaders([
            'user_key' => env('OSS_USER_KEY'),
        ])->post(env('OSS_ENDPOINT') . '/kl/rba/receiveLicenseStatus', $dummy);
        $respJson = $response->json();
        // print_r($respJson);
        if ((int)$respJson['responreceiveLicenseStatus']['kode'] == 200) {
            return true;
        }
        return false;
    }
}
?>