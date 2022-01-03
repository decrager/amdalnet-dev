<template>
  <el-dialog
    :visible.sync="show"
    width="40%"
    :before-close="handleClose"
  >
    <span slot="title" style="font-size: 14pt; font-weight: bold;">{{ title }}</span>
    <el-form label-position="top" :model="component">
      <el-form-item label="Tahap Kegiatan">
        <el-select
          v-model="idProjectStage"
          v-loading="loadingProjectStages"
          placeholder="Tahap Kegiatan"
          :disabled="true"
        >
          <el-option
            v-for="item of projectStages"
            :key="item.id"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
      </el-form-item>
      <el-form-item label="Kegiatan Utama/Pendukung">
        <el-select
          v-model="currentIdSubProject"
          v-loading="loadingSubProjects"
          placeholder="Pilih Kegiatan"
          :disabled="true"
        >
          <el-option
            v-for="item of subProjectsArray"
            :key="item.id"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
      </el-form-item>
      <el-form-item label="Komponen Kegiatan" prop="name">
        <el-autocomplete
          v-model="component.name"
          class="inline-input"
          :fetch-suggestions="searchComponent"
          placeholder="Nama Komponen"
          :trigger-on-focus="false"
          @select="handleSelectComponent"
        />
      </el-form-item>
      <el-form-item :label="deskripsiKhusus()">
        <el-input v-model="component.description_specific" type="textarea" :rows="2" />
      </el-form-item>
      <el-form-item label="Besaran">
        <el-input v-model="component.unit" type="textarea" :rows="2" />
      </el-form-item>
    </el-form>
    <span slot="footer" class="dialog-footer">
      <el-button type="info" @click="handleClose">Batal</el-button>
      <el-button type="primary" @click="submitComponent">Simpan</el-button>
    </span>
  </el-dialog>
</template>

<script>
import Resource from '@/api/resource';
const componentResource = new Resource('components');
const projectStageResource = new Resource('project-stages');
const scopingResource = new Resource('scoping');
// const subProjectComponentResource = new Resource('sub-project-components');

export default {
  name: 'AddComponentDialog',
  props: {
    show: Boolean,
    idProjectStage: {
      type: Number,
      default: () => 0,
    },
    subProjectComponent: {
      type: Object,
      default: () => {},
    },
    isEdit: Boolean,
    currentIdSubProject: {
      type: Number,
      default: () => 0,
    },
    subProjects: {
      type: Object,
      default: () => {},
    },
  },
  data() {
    return {
      title: 'Tambah Komponen Kegiatan',
      component: {},
      componentList: [],
      subProjectsArray: [],
      currentSubProjectName: '',
      projectStages: [],
      disableDescCommon: false,
      loadingProjectStages: true,
      loadingSubProjects: true,
    };
  },
  mounted() {
    this.getData();
  },
  methods: {
    deskripsiKhusus() {
      if (this.component.name === undefined || this.component.name === ''){
        return 'Deskripsi';
      } else {
        return 'Deskripsi ' + this.component.name + ' terkait ' + this.currentSubProjectName;
      }
    },
    searchComponent(queryString, cb) {
      var componentList = this.componentList;
      var results = queryString ? componentList.filter(this.createFilter(queryString)) : componentList;
      cb(results);
    },
    createFilter(queryString) {
      return (cmp) => {
        return (cmp.value.toLowerCase().indexOf(queryString.toLowerCase()) === 0);
      };
    },
    handleSelectComponent(component) {
      const selected = this.componentList.filter(cmp => cmp.value === component.value);
      if (selected.length > 0) {
        this.component['id_component'] = selected[0].id;
      }
    },
    submitComponent() {
      this.component.id_project_stage = this.idProjectStage;
      this.component.id_sub_project = this.currentIdSubProject;
      scopingResource
        .store({
          component: this.component,
        })
        .then((response) => {
          this.$message({
            message: 'Komponen kegiatan berhasil disimpan',
            type: 'success',
            duration: 5 * 1000,
          });
          // reload PelingkupanTable
          this.$emit('handleCloseAddComponent', response.data.id);
          this.$emit('handleSetCurrentIdSubProjectComponent', response.data.id);
        })
        .catch((error) => {
          this.$message({
            message: 'Gagal menyimpan komponen kegiatan',
            type: 'error',
            duration: 5 * 1000,
          });
          console.log(error);
        });
    },
    handleClose() {
      this.$emit('handleCloseAddComponent', false);
    },
    async getData() {
      // if edit
      if (this.isEdit) {
        this.title = 'Edit Komponen Kegiatan';
        this.component = this.subProjectComponent;
        this.currentIdSubProject = this.component.id_sub_project;
        if (this.component.name === null) {
          this.component.name = this.component.component.name;
        }
        if (this.component.id_project_stage !== null) {
          this.idProjectStage = this.component.id_project_stage;
        } else {
          this.idProjectStage = this.component.component.id_project_stage;
        }
      }
      const ps = await projectStageResource.list({
        ordered: true,
      });
      this.projectStages = ps.data;
      this.loadingProjectStages = false;
      const compMaster = await componentResource.list({
        all: true,
      });
      const cmpList = [];
      compMaster.data.forEach(cmp => {
        if (cmp.id_project_stage === this.idProjectStage) {
          cmpList.push({
            id: cmp.id,
            value: cmp.name,
          });
        }
      });
      this.componentList = cmpList;
      this.subProjects.utama.map((u) => {
        this.subProjectsArray.push(u);
      });
      this.subProjects.pendukung.map((p) => {
        this.subProjectsArray.push(p);
      });

      this.subProjectsArray.map((s) => {
        if (s.id === this.currentIdSubProject) {
          this.currentSubProjectName = s.name;
        }
      });
      this.loadingSubProjects = false;
      // common desc
      // const comps = await subProjectComponentResource.list({
      //   id_sub_project: this.currentIdSubProject,
      // });
      // if (comps.data.length > 0) {
      //   const firstComp = comps.data[0];
      //   this.component.description_common = firstComp.description_common;
      //   this.disableDescCommon = true;
      // }
    },
  },
};
</script>
