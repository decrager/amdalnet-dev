<template>
  <div class="app-container">
    <el-row :gutter="4">
      <el-col v-for="stage of projectStages" :key="stage.id" :span="6" :xs="24">
        <aside align="center" style="margin-bottom: 0px; background-color: #def5cf;">
          {{ stage.name }}
        </aside>
        <component-table
          :data="data[stage.id]"
          @handleUpdateComponents="handleUpdateComponents"
          @handleDeleteComponent="handleDeleteComponent"
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
      components: [],
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
      this.components = components.data;
      this.reloadData();

      this.$emit('handleSaveComponents', this.components);
    },
    reloadData() {
      const dataPerStep = {};
      this.projectStages.map((s) => {
        dataPerStep[s.id] = [];
      });
      const data = {};
      this.projectStages.map((s) => {
        this.components.map((k) => {
          if (k.id_project_stage === s.id){
            dataPerStep[k.id_project_stage].push(k);
          }
        });
        data[s.id] = dataPerStep[s.id];
      });
      this.data = data;
    },
    async handleRenderTable() {
      this.getData();
    },
    handleUpdateComponents(data) {
      this.components.push(data);
      this.reloadData();
      this.$emit('handleUpdateComponents', this.components);
    },
    handleDeleteComponent(id) {
      this.components = this.components.filter(component => component.id !== id);
      this.reloadData();
      this.$emit('handleUpdateComponents', this.components);
    },
  },
};
</script>
