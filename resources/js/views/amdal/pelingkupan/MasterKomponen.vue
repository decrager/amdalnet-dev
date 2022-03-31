<template>
  <div style="border:1px solid #e0e0e0; border-radius:0.5em; padding: 1em 1.5em 2.5em;">
    <div style="text-align: right;">
      <el-button
        icon="el-icon-refresh"
        @click="refresh()"
      > Muat ulang
      </el-button>
    </div>
    <el-row :gutter="10">
      <el-col :span="8">
        <komponen-kegiatan
          :components="components"
          :project-stages="projectStages"
        />
      </el-col>
      <el-col :span="8">
        <komponen-lingkungan
          :components="hues"
          :component-types="componentTypes"
        />
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
    projectStages: {
      type: Array,
      default: function() {
        return [];
      },
    },
    componentTypes: {
      type: Array,
      default: function() {
        return [];
      },
    },
  },
  methods: {
    refresh(){
      this.$emit('refreshData', true);
    },
  },
};
</script>
<style>

.components-container{
  margin:0 0 1em;
}
.master-komponen .el-card__body p  {
  margin: 0 0 0.2em 0;
  border: 1px solid #e0e0e0;
  border-radius:0.3em;
  padding: 0.5em;
  text-align: center;
}

</style>
