<template>
  <div class="app-container">
    <!-- <workflow-ukl /> -->

    <h2>Matriks UKL UPL</h2>
    <el-button
      class="pull-right"
      type="success"
      size="small"
      icon="el-icon-check"
      @click="handleSaveForm()"
    > Simpan &amp; Lanjutkan </el-button>
    <p style="clear:both; height:1px;">&nbsp;</p>

    <el-collapse v-model="activeName" :accordion="true">
      <el-collapse-item name="1" title="Matriks UKL (Upaya Pengelolaan Lingkungan Hidup)">
        <matriks-ukl-upl :mtype="'ukl'" :data="ukl" />
      </el-collapse-item>
      <el-collapse-item name="2" title="Matriks UPL (Upaya Pemantauan Lingkungan Hidup)">
        <matriks-ukl-upl :mtype="'upl'" :data="upl" />
      </el-collapse-item>
      <el-collapse-item name="3" title="Dokumen Pendukung">
        <dokumen-pendukung />
      </el-collapse-item>
    </el-collapse>
  </div>
</template>
<script>
// import WorkflowUkl from '@/components/WorkflowUkl';
import MatriksUklUpl from './components/MatriksUklUpl';
import DokumenPendukung from './components/DokumenPendukung';

export default {
  name: 'MatriksUKLUPL',
  components: {
    MatriksUklUpl,
    DokumenPendukung,
    // WorkflowUkl,
  },
  data() {
    return {
      activeName: 1,
      stages: ['Pra Konstruksi', 'Konstruksi', 'Operasi', 'Pasca Operasi'],
      ukl: [
        // pra konstruksi
        {
          stage: { id: 1, name: 'Pra Konstruksi' },
          dampak: [
            {
              sumber: 'Pembebasan Lahan',
              jenis: 'Perubahan',
              rona_awal: 'Arus Lalu Lintas',
              besaran: null,
              standar_pengelolaan: {
                bentuk: ['Memasang Rambu', 'Melakukan Pembebasan Lahan'],
                lokasi: null,
                periode: null,
              },
              institusi: {
                pelaksana: null,
                pengawas: null,
                penerima_laporan: null,
              },
              keterangan: null,
            },
            {
              sumber: 'Pengamanan Perairan',
              jenis: 'Perubahan',
              rona_awal: 'Terumbu Karang',
              besaran: null,
              standar_pengelolaan: {
                bentuk: ['Melakukan sosialisasi'],
                lokasi: null,
                periode: null,
              },
              institusi: {
                pelaksana: null,
                pengawas: null,
                penerima_laporan: null,
              },
              keterangan: null,
            },
            {
              sumber: 'Sosialiasi',
              jenis: 'Penurunan',
              rona_awal: 'Air Limbah',
              besaran: null,
              standar_pengelolaan: {
                bentuk: ['Melakukan sosialisasi'],
                lokasi: null,
                periode: null,
              },
              institusi: {
                pelaksana: null,
                pengawas: null,
                penerima_laporan: null,
              },
              keterangan: null,
            },
          ],
        },
        // konstruksi
        {
          stage: { id: 2, name: 'Konstruksi' },
          dampak: [
            {
              sumber: 'Mobilisasi Alat dan Bahan',
              jenis: 'Peningkatan',
              rona_awal: 'Kebisingan',
              besaran: 'Radius 500 m dari lokasi proyek',
              standar_pengelolaan: {
                bentuk: [],
                lokasi: null,
                periode: null,
              },
              institusi: {
                pelaksana: null,
                pengawas: null,
                penerima_laporan: null,
              },
              keterangan: null,
            },
          ],
        },
        // operasi
        {
          stage: { id: 3, name: 'Operasi' },
          dampak: [],
        },
        // pasca operasi
        {
          stage: { id: 4, name: 'Pasca Operasi' },
          dampak: [],
        },
      ],
      // data-upl
      upl: [
        // pra konstruksi
        {
          stage: { id: 1, name: 'Pra Konstruksi' },
          dampak: [
            {
              sumber: 'Pembebasan Lahan',
              jenis: 'Perubahan',
              rona_awal: 'Arus Lalu Lintas',
              besaran: null,
              standar_pengelolaan: {
                bentuk: ['Memantau Arus Lalu Lintas'],
                lokasi: null,
                periode: null,
              },
              institusi: {
                pelaksana: null,
                pengawas: null,
                penerima_laporan: null,
              },
              keterangan: null,
            },
            {
              sumber: 'Pengamanan Perairan',
              jenis: 'Perubahan',
              rona_awal: 'Terumbu Karang',
              besaran: null,
              standar_pengelolaan: {
                bentuk: [],
                lokasi: null,
                periode: null,
              },
              institusi: {
                pelaksana: null,
                pengawas: null,
                penerima_laporan: null,
              },
              keterangan: null,
            },
            {
              sumber: 'Sosialiasi',
              jenis: 'Penurunan',
              rona_awal: 'Air Limbah',
              besaran: null,
              standar_pengelolaan: {
                bentuk: ['Mengecek Berkala Sungai X'],
                lokasi: null,
                periode: null,
              },
              institusi: {
                pelaksana: null,
                pengawas: null,
                penerima_laporan: null,
              },
              keterangan: null,
            },
          ],
        },
        // konstruksi
        {
          stage: { id: 2, name: 'Konstruksi' },
          dampak: [
            {
              sumber: 'Mobilisasi Alat dan Bahan',
              jenis: 'Peningkatan',
              rona_awal: 'Kebisingan',
              besaran: 'Radius 500 m dari lokasi proyek',
              standar_pengelolaan: {
                bentuk: [],
                lokasi: null,
                periode: null,
              },
              institusi: {
                pelaksana: null,
                pengawas: null,
                penerima_laporan: null,
              },
              keterangan: null,
            },
          ],
        },
        // operasi
        {
          stage: { id: 3, name: 'Operasi' },
          dampak: [],
        },
        // pasca operasi
        {
          stage: { id: 4, name: 'Pasca Operasi' },
          dampak: [],
        },
      ],
    };
  },
  methods: {
    mounted() {
    },
    handleSaveForm() {
      return;
    },
  },
};
</script>
