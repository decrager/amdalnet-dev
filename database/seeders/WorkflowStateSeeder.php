<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Entity\WorkflowState;

class WorkflowStateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $states = [
            [
                'state' => 'init',
                'public_tracking' => 'Pengajuan Persetujuan Lingkungan',
                'code' => 'PRO-1'
            ],
            [
                'state' => 'info-filling',
                'public_tracking' => 'Pengajuan Persetujuan Lingkungan',
                'code' => 'PRO-1'
            ],
            [
                'state' => 'in-screening',
                'public_tracking' => 'Penapisan',
                'code' => 'PRO-1.5'
            ],
            [
                'state' => 'screening-completed',
                'public_tracking' => 'Penapisan Selesai',
                'code' => 'PRO-2'
            ],
            [
                'state' => 'formulator-assignment',
                'public_tracking' => 'Pembentukan Tim Penyusun',
                'code' => 'PRO-3'
            ],
            [
                'state' => 'announcement-drafting',
                'public_tracking' => 'Penyusunan Pengumuman',
                'code' => 'PRO-4'
            ],
            [
                'state' => 'announcement',
                'public_tracking' => 'Penerimaan SPT Masyarakat',
                'code' => 'PRO-5'
            ],
            [
                'state' => 'announcement',
                'public_tracking' => 'Konsultasi Public',
                'code' => 'AMD-1',
                'flag' => 'pubcons',
            ],
            [
                'state' => 'announcement-completed',
                'public_tracking' => 'Pengumuman Selesai',
                'code' => 'PRO-6'
            ],
            [
                'state' => 'uklupl-mr.pkplh-published',
                'public_tracking' => 'Penerbitan PKPLH',
                'code' => 'UKL-13'
            ],
            [
                'state' => 'uklupl-mt.form',
                'public_tracking' => 'Penyusunan Formulir UKL-UPL',
                'code' => 'UKL-1'
            ],
            [
                'state' => 'uklupl-mt.matrix-ukl',
                'public_tracking' => 'Penyusunan Matriks UKL-UPL',
                'code' => 'UKL-2'
            ],
            [
                'state' => 'uklupl-mt.matrix-upl',
                'public_tracking' => 'Penyusunan Matriks UKL-UPL',
                'code' => 'UKL-2'
            ],
            [
                'state' => 'uklupl-mt.sent',
                'public_tracking' => 'Formulir UKL/UPL Terkirim',
                'code' => 'UKL-3'
            ],
            [
                'state' => 'uklupl-mt.adm-review',
                'public_tracking' => 'Pemeriksaan Administrasi UKL-UPL',
                'code' => 'UKL-4'
            ],
            [
                'state' => 'uklupl-mt.returned',
                'public_tracking' => 'UKL-UPL Dikembalikan',
                'code' => 'UKL-5'
            ],
            [
                'state' => 'uklupl-mt.examination-invitation-drafting',
                'public_tracking' => 'UKL-UPL dinyatakan lengkap dan benar',
                'code' => 'UKL-6'
            ],
            [
                'state' => 'uklupl-mt.examination-invitation-sent',
                'public_tracking' => 'Undangan rapat pemeriksaan UKL-UPL terkirim',
                'code' => 'UKL-7'
            ],
            [
                'state' => 'uklupl-mt.examination',
                'public_tracking' => 'Pemeriksaan Formulir UKL-UPL',
                'code' => 'UKL-8'
            ],
            [
                'state' => 'uklupl-mt.examination-meeting',
                'public_tracking' => 'Rapat Pemeriksaan Formulir UKL-UPL',
                'code' => 'UKL-9'
            ],
            [
                'state' => 'uklupl-mt.ba-drafting',
                'public_tracking' => 'Penyusunan Berita Acara',
            ],
            [
                'state' => 'uklupl-mt.ba-signed',
                'public_tracking' => 'Penerbitan Berita Acara',
                'code' => 'UKL-12'
            ],
            [
                'state' => 'uklupl-mt.recommendation-drafting',
                'public_tracking' => 'Penyusunan Rekomendasi',
            ],
            [
                'state' => 'uklupl-mt.recommendation-signed',
                'public_tracking' => 'Penerbitan Rekomendasi',
                'code' => 'UKL-12.5'
            ],
            // amdal
            [
                'state' => 'amdal.form-ka-drafting',
                'public_tracking' => 'Penyusunan Formulir Kerangka Acuan',
                'code' => 'AMD-2'
            ],
            [
                'state' => 'amdal.form-ka-submitted',
                'public_tracking' => 'Formulir Kerangka Acuan Terkirim',
                'code' => 'AMD-3'
            ],
            [
                'state' => 'amdal.form-ka-adm-review',
                'public_tracking' => 'Pemeriksaan Berkas Kelengkapan Formulir Kerangka Acuan',
                'code' => 'AMD-4'
            ],
            [
                'state' => 'amdal.form-ka-adm-returned',
                'public_tracking' => 'Berkas Formulir Kerangka Acuan Dikembalikan',
                'code' => 'AMD-5'
            ],
            [
                'state' => 'amdal.form-ka-adm-approved',
                'public_tracking' => 'Berkas Formulir Kerangka Acuan Sesuai',
                'code' => 'AMD-6'
            ],
            [
                'state' => 'amdal.form-ka-examination-invitation-drafting',
                'public_tracking' => 'Penyusunan Undangan Rapat Pemeriksaan Formulir Kerangka Acuan',
                // 'code' => ''
            ],
            [
                'state' => 'amdal.form-ka-examination-invitation-sent',
                'public_tracking' => 'Undangan Rapat Pemeriksaan Formulir Kerangka Acuan Terkirim',
                'code' => 'AMD-7'
            ],
            [
                'state' => 'amdal.form-ka-examination',
                'public_tracking' => 'Pemeriksaan Formulir Kerangka Acuan',
                'code' => 'AMD-8'
            ],
            [
                'state' => 'amdal.form-ka-examination-meeting',
                'public_tracking' => 'Rapat Pemeriksaan Formulir Kerangka Acuan',
                'code' => 'AMD-9'
            ],
            [
                'state' => 'amdal.form-ka-returned',
                'public_tracking' => 'Formulir Kerangka Acuan Dikembalikan',
                'code' => 'AMD-10'
            ],
            [
                'state' => 'amdal.form-ka-approved',
                'public_tracking' => 'Formulir Kerangka Acuan Disetujui',
                'code' => 'AMD-11'
            ],
            [
                'state' => 'amdal.form-ka-ba-drafting',
                'public_tracking' => 'Penyusunan Berita Acara Formulir Kerangka Acuan',
                // 'code' => ''
            ],
            [
                'state' => 'amdal.form-ka-ba-signed',
                'public_tracking' => 'Berita Acara Formulir Kerangka Acuan Disetujui',
                'code' => 'AMD-12'
            ],
            [
                'state' => 'amdal.andal-drafting',
                'public_tracking' => 'Penyusunan Andal',
                'code' => 'AMD-13'
            ],
            [
                'state' => 'amdal.rklrpl-drafting',
                'public_tracking' => 'Penyusunan RKL RPL',
                'code' => 'AMD-14'
            ],
            [
                'state' => 'amdal.submitted',
                'public_tracking' => 'Andal dan RKL RPL Terkirim',
                'code' => 'AMD-15'
            ],
            [
                'state' => 'amdal.adm-review',
                'public_tracking' => 'Pemeriksaan Berkas Administrasi Andal dan RKL RPL',
                'code' => 'AMD-16'
            ],
            [
                'state' => 'amdal.adm-returned',
                'public_tracking' => 'Dokumen Andal dan RKL RPL Dikembalikan',
                'code' => 'AMD-17'
            ],
            [
                'state' => 'amdal.adm-approved',
                'public_tracking' => 'Dokumen Andal dan RKL RPL Dinyatakan Lengkap dan Benar',
                'code' => 'AMD-18'
            ],
            [
                'state' => 'amdal.examination',
                'public_tracking' => 'Dalam Masa Pengujian',
                // 'code' => ''
            ],
            [
                'state' => 'amdal.feasibility-invitation-drafting',
                'public_tracking' => 'Penyusunan Undangan Rapat Uji Kelayakan Andal dan RKL RPL',
                // 'code' => ''
            ],
            [
                'state' => 'amdal.feasibility-invitation-sent',
                'public_tracking' => 'Undangan Rapat Uji Kelayakan Andal dan RKL RPL Terkirim',
                'code' => 'AMD-19'
            ],
            [
                'state' => 'amdal.feasibility-review',
                'public_tracking' => 'Uji Kelayakan Andal dan RKL RPL',
                'code' => 'AMD-20'
            ],
            [
                'state' => 'amdal.feasibility-review-meeting',
                'public_tracking' => 'Rapat Uji Kelayakan Andal dan RKL RPL Terkirim',
                'code' => 'AMD-21'
            ],
            [
                'state' => 'amdal.feasibility-returned',
                'public_tracking' => 'Tidak Lulus Uji Kelayakan Andal dan RKL RPL',
                // 'code' => 'AMD-19'
            ],
            [
                'state' => 'amdal.feasibility-ba-drafting',
                'public_tracking' => 'Penyusunan Berita Acara Uji Kelayakan Andal dan RKL RPL',
                // 'code' => ''
            ],
            [
                'state' => 'amdal.feasibility-ba-signed',
                'public_tracking' => 'Penerbitan Berita Acara Rapat Uji Kelayakan Andal dan RKL RPL',
                'code' => 'AMD-22'
            ],
            [
                'state' => 'amdal.recommendation-drafting',
                'public_tracking' => 'Penyusunan Surat Rekomendasi',
                // 'code' => ''
            ],
            [
                'state' => 'amdal.recommendation-signed',
                'public_tracking' => 'Penerbitan Surat Rekomendasi',
                'code' => 'AMD-23'
            ],
            [
                'state' => 'amdal.skkl-published',
                'public_tracking' => 'Penerbitan SKKL',
                'code' => 'AMD-24'
            ],

        ];

        foreach($states as $state){
            WorkflowState::create($state);
        }
    }
}
