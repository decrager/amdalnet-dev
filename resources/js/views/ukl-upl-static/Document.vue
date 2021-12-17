<template>
  <div class="app-container">
    <h2>Dokumen UKL UPL</h2>
    <el-tabs type="card">
      <el-tab-pane label="Dokumen UKL-UPL" />
      <el-tab-pane label="Verifikasi &amp; Rapat">Config</el-tab-pane>
      <el-tab-pane label="Berita Acara"><berita-acara /></el-tab-pane>
    </el-tabs>
  </div>
</template>
<script>
import BeritaAcara from './components/BeritaAcara.vue';
export default {
  name: 'DokumenUKLUPL',
  components: { BeritaAcara },
};
</script>
