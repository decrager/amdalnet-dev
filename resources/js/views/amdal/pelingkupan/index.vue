<template>
  <div>
    <master-komponen
      :components="components"
      :hues="hues"
      :component-types="component_types"
      :project-stages="project_stages"
    />
    <tabel-pelingkupan
      :components="components"
      :hues="hues"
      :component-types="component_types"
      :project-stages="project_stages"
    />
  </div>
</template>
<script>
import TabelPelingkupan from './TabelPelingkupan.vue';
import MasterKomponen from './MasterKomponen.vue';

import Resource from '@/api/resource';
// const ronaAwalResource = new Resource('rona-awals');

export default {
  name: 'ModulPelingkupan',
  components: { TabelPelingkupan, MasterKomponen },
  data(){
    return {
      // master data
      component_types: [],
      project_stages: [],
      components: [], // master lokal
      hues: [], // master lokal
    };
  },
  mounted() {
    this.getComponentTypes();
    this.getProjectStages();
  },
  methods: {
    async initData(){

    },
    async getComponentTypes(){
      const ctResource = new Resource('component-types');
      await ctResource.list({}).then((res) => {
        this.component_types = res.data;
      }).finally({});
    },
    async getProjectStages(){
      const ctResource = new Resource('project-stages');
      await ctResource.list({}).then((res) => {
        this.project_stages = res.data;
      }).finally({});
    },
  },
};
</script>
<style>

.master-komponen .el-card__header {
      padding: 5px 0 !important;
      background: #39773b;
      color: #ffffff;

}

.scoupingModule .el-card__header {
      padding: 5px 0 !important;
      background: #216221;
      color: #ffffff;

}

.scoupingModule .el-card__body{
  padding: 8px 5px;
}

.master-komponen  .el-card__body {
  background: #f5f5f5;
}

.master-komponen .el-card__body p, .scoping p {
  font-size:90%;
  margin: 0 0 0.2em 0;
  border: 1px solid #e0e0e0;
  border-radius:0.3em;
  padding: 0.5em;
}

.master-komponen .el-card__body .components-container {
  max-height: 350px;
  overflow-y: auto;
  padding: 5px 0;
  margin: 0 auto 0.6em;

}

.master-komponen .el-card__body .components-container p{
  text-align:center; background: #fff;
}

</style>

