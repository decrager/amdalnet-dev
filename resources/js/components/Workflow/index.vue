<template>
  <el-steps
    :active="getStep"
    finish-status="success"
    align-center
    style="margin-bottom: 20px"
  >
    <el-step title="1" description="Rencana Usaha Kegiatan" icon="el-icon-edit" />
    <el-step title="2" description="Hasil Penapisan" icon="el-icon-edit" />
    <el-step title="3" description="SPT dari Masyarakat" icon="el-icon-edit" />
    <el-step title="4" description="Formulir UKL UPL" />
    <el-step title="5" description="Matriks UKL UPL" />
    <el-step title="6" description="Dokumen UKL UPL" />
    <el-step title="7" description="Surat Keputusan" />
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
