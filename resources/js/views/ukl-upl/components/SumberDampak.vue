<template>
  <div class="app-container">
    <el-row :gutter="4">
      <el-col v-for="stage of projectStages" :key="stage.id" :span="6" :xs="24">
        <aside align="center" style="margin-bottom: 0px;">
          {{ stage.name }}
        </aside>
        <component-table
          :data="data[stage.id]"
          @handleUpdateComponents="handleUpdateComponents"
          @handleRenderTable="handleRenderTable"
        />
      </el-col>
    </el-row>
  </div>
</template>

<script>

import Resource from '@/api/resource';
import ComponentTable from './ComponentTable.vue';

const prjstageResource = new Resource('project-stages');
const componentResource = new Resource('components');

export default {
  name: 'SumberDampak',
  components: { ComponentTable },
  data() {
    return {
      komponenKegiatan: [],
      projectStages: [],
      data: {},
    };
  },
  mounted() {
    this.getData();
  },
  methods: {
    async getData() {
      const projects = await prjstageResource.list({});
      this.projectStages = projects.data;

      const components = await componentResource.list({
        all: true,
      });
      this.komponenKegiatan = components.data;
      const n = [];
      const dataPerStep = {};
      this.projectStages.map((s) => {
        n.push(1);
        dataPerStep[s.id] = [];
      });
      this.komponenKegiatan.map((k) => {
        const i = k.id_project_stage - 1;
        k.no = n[i];
        n[i]++;
      });
      const data = {};
      this.projectStages.map((s) => {
        this.komponenKegiatan.map((k) => {
          if (k.id_project_stage === s.id){
            dataPerStep[k.id_project_stage].push(k);
          }
        });
        data[s.id] = dataPerStep[s.id];
      });
      this.data = data;

      this.$emit('handleSaveComponents', this.komponenKegiatan);
    },
    async handleRenderTable(){
      this.getData();
    },
    handleUpdateComponents(data){
      this.komponenKegiatan.push(data);
      this.$emit('handleUpdateComponents', this.komponenKegiatan);
    },
  },
};
</script>
