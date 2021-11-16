<template>
  <div class="app-container">
    <el-card>
      <h3 align="center">Surat Keputusan Kelayakan Lingkungan</h3>
      <el-row :gutter="32">
        <el-col :sm="24" :md="12">
          <h4>Informasi Rencana Usaha/Kegiatan</h4>
          <Information />
        </el-col>
        <el-col :sm="24" :md="12">
          <h4>Dokumen Persetujuan Lingkungan</h4>
          <DokumenPersetujuan />
        </el-col>
      </el-row>
    </el-card>
  </div>
</template>

<script>
import Information from '@/views/skkl/components/Information';
import DokumenPersetujuan from '@/views/skkl/components/DokumenPersetujuan';

export default {
  name: 'SKKL',
  components: {
    Information,
    DokumenPersetujuan,
  },
};
</script>
