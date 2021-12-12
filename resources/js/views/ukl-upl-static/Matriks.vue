<template>
  <div class="app-container">
    <workflow-ukl />

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
        <matriks-ukl-upl />
      </el-collapse-item>
      <el-collapse-item name="2" title="Matriks UPL (Upaya Pemantauan Lingkungan Hidup)">
        <matriks-ukl-upl />
      </el-collapse-item>
      <el-collapse-item name="3" title="Dokumen Pendukung">
        <dokumen-pendukung />
      </el-collapse-item>
    </el-collapse>
  </div>
</template>
<script>
import MatriksUklUpl from './components/MatriksUklUpl';
import DokumenPendukung from './components/DokumenPendukung';

export default {
  name: 'MatriksUKLUPL',
  components: {
    MatriksUklUpl,
    DokumenPendukung,
  },
  data() {
    return {};
  },
  methods: {
    handleSaveForm() {
      return;
    },
  },
};
</script>
