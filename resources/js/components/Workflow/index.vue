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
    <el-step v-if="!isPenapisan" title="Formulir KA" />
    <el-step v-if="!isPenapisan" title="Penyusunan ANDAL" />
    <el-step v-if="!isPenapisan" title="Penyusunan RKL RPL" />
    <el-step v-if="!isPenapisan" title="Pengujian" />
    <el-step v-if="!isPenapisan" title="Surat Keputusan" />
  </el-steps>
</template>

<script>
export default {
  name: 'Workflow',
  props: {
    isPenapisan: {
      type: Boolean,
      default: false,
    },
  },
  computed: {
    getStep() {
      console.log(this.$store.getters.step);
      return this.$store.getters.step;
    },
  },
};
</script>
