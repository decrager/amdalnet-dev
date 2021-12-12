<template>
  <div class="app-container">
    <el-button
      type="success"
      size="small"
      icon="el-icon-check"
      @click="handleSaveForm()"
    >
      Simpan Perubahan
    </el-button>
    <el-row :gutter="4">
      <el-col v-for="stage of projectStages" :key="stage.id" :span="6" :xs="24">
        <aside align="center" style="margin-bottom: 0px; background-color: #def5cf;">
          {{ stage.name }}
        </aside>
        <component-table
          :data="data[stage.id]"
          :dummy-id="dummyId"
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
const projectComponentResource = new Resource('project-components');
const componentResource = new Resource('components');

export default {
  name: 'SumberDampak',
  components: { ComponentTable },
  data() {
    return {
      idProject: 0,
      components: [],
      projectStages: [],
      data: {},
      dummyId: 99999999,
    };
  },
  mounted() {
    this.getData();
  },
  methods: {
    handleSaveForm(){
      this.storeComponents();
    },
    storeComponents() {
      this.components.map((component) => {
        if (!('id_component' in component)) {
          component['id_component'] = component['id'];
        }
        component['id_project'] = this.idProject;
      });
      projectComponentResource
        .store({
          'components': this.components,
        })
        .then((response) => {
          var message = (response.code === 200) ? 'Komponen kegiatan berhasil disimpan' : 'Terjadi kesalahan pada server';
          var message_type = (response.code === 200) ? 'success' : 'error';
          this.$message({
            message: message,
            type: message_type,
            duration: 5 * 1000,
          });
          // reload accordion
          this.$emit('handleReloadVsaList', 'rona-lingkungan-awal');
        })
        .catch((error) => {
          console.log(error);
        });
    },
    async getData() {
      this.idProject = parseInt(this.$route.params && this.$route.params.id);
      const projects = await prjstageResource.list({});
      this.projectStages = projects.data;
      const projectComponents = await projectComponentResource.list({
        id_project: this.idProject,
      });
      if (projectComponents.data.length > 0){
        projectComponents.data.map((c) => {
          if (c.id_project_stage === null) {
            c.id_project_stage = c.id_project_stage_master;
          }
          if (c.name === null) {
            c.name = c.name_master;
          }
        });
        this.components = projectComponents.data;
      } else {
        const components = await componentResource.list({
          all: true,
        });
        this.components = components.data;
      }
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
      this.dummyId = this.dummyId + 1;
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
