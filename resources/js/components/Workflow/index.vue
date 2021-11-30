<template>
  <el-steps
    :active="getStep"
    finish-status="success"
    align-center
    style="margin-bottom: 20px"
  >
    <el-step title="Rencana Usaha Kegiatan" />
    <el-step title="Hasil Penapisan" />
    <el-step title="SPT dari Masyarakat" />
    <el-step title="Formulir KA" />
    <el-step title="Penyusunan ANDAL" />
    <el-step title="Penyusunan RKL RPL" />
    <el-step title="Pengujian" />
    <el-step title="Surat Keputusan" />
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
