<template>
  <el-steps
    :active="3"
    finish-status="success"
    align-center
    style="margin-bottom: 20px"
  >
    <el-step title="Rencana Usaha Kegiatan" />
    <el-step title="Hasil Penapisan" />
    <el-step title="SPT dari Masyarakat" />
    <el-step title="Formulir UKL UPL" />
    <el-step title="Mariks UKL UPL" />
    <el-step title="Dokumen UKL UPL" />
    <el-step title="Surat Keputusan" />
  </el-steps>
</template>

<script>
export default {
  name: 'WorkflowUkl',
  computed: {
    getStep() {
      console.log(this.$store.getters.step);
      return this.$store.getters.step;
    },
  },
};
</script>
