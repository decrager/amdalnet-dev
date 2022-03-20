<template>
  <div style="border:1px solid #e0e0e0; border-radius:0.5em; padding: 2.5em 1.5em;">
    <el-row :gutter="10">
      <el-col :span="8">
        <komponen-kegiatan :components="components" />
      </el-col>
      <el-col :span="8">
        <komponen-lingkungan :components="hues" />
      </el-col>
      <el-col :span="8">
        &nbsp;
      </el-col>
    </el-row>
  </div>
</template>
<script>
import KomponenKegiatan from './components/KomponenKegiatan.vue';
import KomponenLingkungan from './components/KomponenLingkungan.vue';

export default {
  name: 'MasterKomponen',
  components: { KomponenKegiatan, KomponenLingkungan },
  props: {
    components: {
      type: Array,
      default: function() {
        return [];
      },
    },
    hues: {
      type: Array,
      default: function() {
        return [];
      },
    },
  },
  data() {
    return {
      // kegiatan: [],
    };
  },
  methods: {

  },
};
</script>
<style lang="scss" scoped>

</style>
