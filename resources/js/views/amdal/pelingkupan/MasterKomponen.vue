<template>
  <div>
    <el-row :gutter="20">
      <el-col :span="8">
        <komponen-kegiatan />
      </el-col>
      <el-col :span="8">
        <el-card shadow="never" />
      </el-col>
      <el-col :span="8">
        <el-card shadow="never" />
      </el-col>
    </el-row>
  </div>
</template>
<script>
import KomponenKegiatan from './components/KomponenKegiatan.vue';

export default {
  name: 'MasterKomponen',
  components: { KomponenKegiatan },
  data() {
    return {

    };
  },
  methods: {

  },
};
</script>
<style lang="scss" scoped>

</style>
