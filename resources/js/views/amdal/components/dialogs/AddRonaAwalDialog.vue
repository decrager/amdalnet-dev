<template>
  <el-dialog
    title="Tambah Komponen Lingkungan"
    :visible.sync="show"
    width="40%"
    :before-close="handleClose"
  >
    <el-form label-position="top" :model="ronaAwal">
      <!-- <el-form-item label="Tahap Kegiatan">
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
      </el-form-item>-->
      <div style="line-height: 180% !important;">
        <div><strong>Tahap Kegiatan</strong></div>
        <div style="font-size:120%">{{ projectStage.name || 'Belum dipilih' }}</div>
      </div>

      <div style="line-height: 180% !important; margin: 2em 0;">
        <div><strong>Kegiatan Utama/Pendukung</strong></div>
        <div style="font-size:120%">{{ currentSubProjectName || 'Belum dipilih' }}</div>
      </div>

      <!-- <el-form-item label="Kegiatan Utama/Pendukung">
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
      </el-form-item> -->

      <el-form-item
        label="Komponen Kegiatan"
        style="padding: 1em; border: 1px solid #e0e0e0; border-radius: 1em;"
      >

        <div style="font-size:120%">{{ currentComponent? currentComponent.name : 'Belum dipilih' }}</div>
        <deskripsi-besaran
          :data="currentComponent"
        />

        <!-- <el-select
          v-model="currentIdSubProjectComponent"
          v-loading="loadingSubProjectComponents"
          placeholder="Pilih Komponen Kegiatan"
          :disabled="true"
        >
          <el-option
            v-for="item of subProjectComponentsArray"
            :key="item.id"
            :label="item.name"
            :value="item.id"
          />
        </el-select> -->
      </el-form-item>
      <el-form-item label="Tipe Komponen Lingkungan">
        <el-select
          v-model="currentIdComponentType"
          v-loading="loadingComponentTypes"
          placeholder="Pilih Tipe Komponen Lingkungan"
          :disabled="true"
        >
          <el-option
            v-for="item of componentTypes"
            :key="item.id"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
      </el-form-item>
      <el-form-item label="Rona Lingkungan">
        <el-autocomplete
          v-model="ronaAwal.name"
          class="inline-input"
          :fetch-suggestions="searchRonaAwal"
          placeholder="Nama Rona Lingkungan"
          :trigger-on-focus="false"
          @select="handleSelectRonaAwal"
        />
      </el-form-item>
      <el-form-item :label="deskripsiKhusus()">
        <hueeditor
          v-model="ronaAwal.description_specific"
          output-format="html"
          :menubar="''"
          :image="false"
          :height="100"
          :toolbar="['bold italic underline bullist numlist  preview undo redo fullscreen']"
          style="width:100%"
        />

        <!-- <el-input v-model="ronaAwal.description_specific" type="textarea" :rows="2" />-->
      </el-form-item>
      <el-form-item label="Besaran">
        <el-input
          v-model="ronaAwal.unit"
          type="textarea"
          :autosize="{ minRows: 3, maxRows: 5}"
        />

        <!-- <el-input v-model="ronaAwal.unit" type="textarea" :rows="2" /> -->
      </el-form-item>
    </el-form>
    <span slot="footer" class="dialog-footer">
      <el-button type="info" @click="handleClose">Batal</el-button>
      <el-button type="primary" @click="submitRonaAwal">Simpan</el-button>
    </span>
  </el-dialog>
</template>

<script>
import Resource from '@/api/resource';
import HDescEditor from '@/components/Tinymce';
import DeskripsiBesaran from './DeskripsiBesaran.vue';
const ronaAwalResource = new Resource('rona-awals');
// const projectStageResource = new Resource('project-stages');
const componentTypeResource = new Resource('component-types');
const scopingResource = new Resource('scoping');
const subProjectComponentResource = new Resource('sub-project-components');
const impactIdtResource = new Resource('impact-identifications');

export default {
  name: 'AddRonaAwalDialog',
  components: {
    'hueeditor': HDescEditor,
    DeskripsiBesaran,
  },
  props: {
    show: Boolean,
    idProject: {
      type: Number,
      default: () => 0,
    },
    idProjectStage: {
      type: Number,
      default: () => 0,
    },
    currentIdSubProject: {
      type: Number,
      default: () => 0,
    },
    currentIdSubProjectComponent: {
      type: Number,
      default: () => 0,
    },
    currentIdComponentType: {
      type: Number,
      default: () => 0,
    },
    subProjects: {
      type: Object,
      default: () => {},
    },
    subProjectComponents: {
      type: Array,
      default: () => [],
    },
    projectStage: {
      type: Object,
      default: () => null,
    },
    currentSubProjectName: {
      type: String,
      default: () => '',
    },
    currentComponent: {
      type: Object,
      default: () => null,
    },
  },
  data() {
    return {
      ronaAwal: {},
      ronaAwalList: [],
      subProjectsArray: [],
      subProjectComponentsArray: [],
      currentSubProjectComponentName: '',
      projectStages: [],
      // currentSubjProject: null,
      componentTypes: [],
      disableDescCommon: false,
      loadingProjectStages: true,
      loadingSubProjects: true,
      loadingSubProjectComponents: true,
      loadingComponentTypes: true,
    };
  },
  mounted() {
    this.getData();
  },
  methods: {
    deskripsiKhusus() {
      if (this.ronaAwal.name === undefined || this.ronaAwal.name === ''){
        return 'Deskripsi';
      } else {
        return 'Deskripsi ' + this.ronaAwal.name + ' terkait ' + this.currentSubProjectComponentName;
      }
    },
    searchRonaAwal(queryString, cb) {
      var ronaAwalList = this.ronaAwalList;
      var results = queryString ? ronaAwalList
        .filter(ra => ra.id_component_type === this.currentIdComponentType)
        .filter(this.createFilter(queryString)) : ronaAwalList;
      cb(results);
    },
    createFilter(queryString) {
      return (ra) => {
        return (ra.value.toLowerCase().indexOf(queryString.toLowerCase()) === 0);
      };
    },
    handleSelectRonaAwal(ronaAwal) {
      const selected = this.ronaAwalList.filter(ra => ra.value === ronaAwal.value);
      if (selected.length > 0) {
        this.ronaAwal['id_rona_awal'] = selected[0].id;
      }
    },
    async submitRonaAwal() {
      this.ronaAwal.id_component_type = this.currentIdComponentType;
      this.ronaAwal.id_sub_project_component = this.currentIdSubProjectComponent;
      scopingResource
        .store({
          rona_awal: this.ronaAwal,
        })
        .then((response) => {
          this.$message({
            message: 'Komponen Lingkungan berhasil disimpan',
            type: 'success',
            duration: 5 * 1000,
          });
          // save impact idt
          this.saveImpactIdentification(response.data.id);
          // reload PelingkupanTable
          this.$emit('handleCloseAddRonaAwal', response.data.id_sub_project_component);
        })
        .catch((error) => {
          this.$message({
            message: 'Gagal menyimpan komponen lingkungan',
            type: 'error',
            duration: 5 * 1000,
          });
          console.log(error);
        });
    },
    async saveImpactIdentification(idSubProjectRonaAwal) {
      impactIdtResource
        .store({
          id_project: this.idProject,
          id_sub_project_component: this.currentIdSubProjectComponent,
          id_sub_project_rona_awal: idSubProjectRonaAwal,
        })
        .then((response) => {
          this.$message({
            message: 'Dampak Lingkungan berhasil disimpan',
            type: 'success',
            duration: 5 * 1000,
          });
        })
        .catch((error) => {
          this.$message({
            message: 'Gagal menyimpan dampak lingkungan',
            type: 'error',
            duration: 5 * 1000,
          });
          console.log(error);
        });
    },
    handleClose() {
      this.$emit('handleCloseAddRonaAwal', this.currentIdSubProjectComponent);
    },
    async getData() {
      const { data } = await componentTypeResource.list({});
      this.componentTypes = data;
      this.loadingComponentTypes = false;
      const ronaAwals = await ronaAwalResource.list({
        all: true,
      });
      const raList = [];
      ronaAwals.data.forEach(ra => {
        raList.push({
          id: ra.id,
          id_component_type: ra.id_component_type,
          value: ra.name,
        });
      });
      this.ronaAwalList = raList;
      /* const ps = await projectStageResource.list({
        ordered: true,
      });
      this.projectStages = ps.data;*/
      this.loadingProjectStages = false;

      this.subProjects.utama.map((u) => {
        this.subProjectsArray.push(u);
      });
      this.subProjects.pendukung.map((p) => {
        this.subProjectsArray.push(p);
      });
      this.loadingSubProjects = false;
      this.subProjectComponents.map((c) => {
        if (c.name === null) {
          c.name = c.component.name;
        }
        this.subProjectComponentsArray.push(c);
      });
      this.subProjectComponentsArray.map((c) => {
        if (c.id === this.currentIdSubProjectComponent) {
          this.currentSubProjectComponentName = c.name;
        }
      });
      // this.currentSubjProject = this.subProjectsArray.find(s => s.id = currentIdSubProject);
      this.loadingSubProjectComponents = false;
      // common desc
      const comps = await subProjectComponentResource.list({
        id_sub_project: this.currentIdSubProject,
      });
      if (comps.data.length > 0) {
        const firstComp = comps.data[0];
        this.ronaAwal.description_common = firstComp.description_common;
        this.disableDescCommon = true;
      }
    },
  },
};
</script>

