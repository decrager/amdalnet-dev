<template>
  <el-steps
    :active="getStep"
    finish-status="success"
    align-center
    style="margin-bottom: 20px"
  >
    <el-step icon="el-icon-bangzhu" title="Rencana Usaha Kegiatan" />
    <el-step icon="el-icon-bangzhu" title="Hasil Penapisan" />
    <el-step icon="el-icon-bangzhu" title="SPT dari Masyarakat" />
    <el-step icon="el-icon-bangzhu" title="Formulir UKL UPL" />
    <el-step icon="el-icon-bangzhu" title="Matriks UKL UPL" />
    <el-step icon="el-icon-bangzhu" title="Dokumen UKL UPL" />
    <el-step icon="el-icon-bangzhu" title="Surat Keputusan" />
  </el-steps>
</template>

<script>
export default {
  name: 'Workflow',
  computed: {
    getStep() {
      console.log(this.$store.getters.step);
      return this.$store.getters.step;
    },
  },
};
</script>
