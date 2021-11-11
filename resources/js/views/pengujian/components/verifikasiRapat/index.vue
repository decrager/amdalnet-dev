<template>
  <div>
    <div class="filter-container" align="right">
      <el-button type="primary" style="font-size: 0.8rem">{{
        'Simpan Perubahan'
      }}</el-button>
      <el-button type="info" style="font-size: 0.8rem">{{
        'Kirim Undangan Rapat'
      }}</el-button>
    </div>
    <el-row :gutter="32">
      <el-col :sm="12" :md="12">
        <FormKelengkapan
          :list="kelengkapan"
          :penyetujuan="penyetujuan"
          :pemeriksaan="pemeriksaan"
        />
      </el-col>
      <el-col :sm="12" :md="12">
        <UndanganRapat />
      </el-col>
    </el-row>
  </div>
</template>

<script>
import FormKelengkapan from '@/views/pengujian/components/verifikasiRapat/formKelengkapan';
import UndanganRapat from '@/views/pengujian/components/verifikasiRapat/undanganRapat';

export default {
  name: 'VerifikasiRapat',
  components: {
    FormKelengkapan,
    UndanganRapat,
  },
  data() {
    return {
      kelengkapan: [
        {
          data: 'Komponen Kegiatan',
          kelengkapan: 'Lengkap',
          kesesuaian: 'Sesuai',
          keterangan: '',
        },
        {
          data: 'Rona Lingkungan Awal',
          kelengkapan: 'Lengkap',
          kesesuaian: 'Sesuai',
          keterangan: '',
        },
        {
          data: 'Matriks Identifikasi Dampak',
          kelengkapan: 'Lengkap',
          kesesuaian: 'Sesuai',
          keterangan: '',
        },
        {
          data: 'Matriks UKL',
          kelengkapan: 'Lengkap',
          kesesuaian: 'Sesuai',
          keterangan: '',
        },
        {
          data: 'Matriks UPL',
          kelengkapan: 'Lengkap',
          kesesuaian: 'Sesuai',
          keterangan: '',
        },
        {
          data: 'SPH Peta Tapak Proyek',
          kelengkapan: 'Lengkap',
          kesesuaian: 'Sesuai',
          keterangan: '',
        },
        {
          data: 'SPH Peta Batas Wilayah Studi',
          kelengkapan: 'Lengkap',
          kesesuaian: 'Sesuai',
          keterangan: '',
        },
        {
          data: 'SPH Peta Batas Ekologi',
          kelengkapan: 'Lengkap',
          kesesuaian: 'Sesuai',
          keterangan: '',
        },
        {
          data: 'SPH Peta Batas Sosial',
          kelengkapan: 'Lengkap',
          kesesuaian: 'Sesuai',
          keterangan: '',
        },
      ],
      penyetujuan: {
        jabatan: 'Kasi. Penilaian Amdan dan Izin Lingkungan',
        lokasi: 'Jakarta',
        nama: 'Dra. Laksmi Widyajayanti, M.Sc.',
      },
      pemeriksaan: {
        lokasi: 'Jakarta',
        oleh: 'Validator Administrasi',
      },
    };
  },
};
</script>
