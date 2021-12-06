<template>
  <el-dialog
    title="Tambah Komponen Lingkungan"
    :visible.sync="show"
    width="40%"
    :before-close="handleClose"
  >
    <el-form label-position="top" :model="ronaAwal">
      <el-form-item label="Tahap Kegiatan">
        <el-select
          v-model="idProjectStage"
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
      <el-form-item label="Komponen Kegiatan">
        <el-select
          v-model="currentIdSubProjectComponent"
          placeholder="Pilih Komponen Kegiatan"
          :disabled="true"
        >
          <el-option
            v-for="item of subProjectComponentsArray"
            :key="item.id"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
      </el-form-item>
      <el-form-item label="Tipe Komponen Lingkungan">
        <el-select
          v-model="currentIdComponentType"
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
          @select="handleSelectRonaAwal"
        />
      </el-form-item>
      <el-form-item :label="deskripsiKhusus()">
        <el-input v-model="ronaAwal.description_specific" type="textarea" :rows="2" />
      </el-form-item>
      <el-form-item label="Besaran Dampak">
        <el-input v-model="ronaAwal.unit" type="textarea" :rows="2" />
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
const ronaAwalResource = new Resource('rona-awals');
const projectStageResource = new Resource('project-stages');
const componentTypeResource = new Resource('component-types');
const scopingResource = new Resource('scoping');
const subProjectComponentResource = new Resource('sub-project-components');
const impactIdtResource = new Resource('impact-identifications');

export default {
  name: 'AddRonaAwalDialog',
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
  },
  data() {
    return {
      ronaAwal: {},
      ronaAwalList: [],
      subProjectsArray: [],
      subProjectComponentsArray: [],
      currentSubProjectComponentName: '',
      projectStages: [],
      componentTypes: [],
      disableDescCommon: false,
    };
  },
  mounted() {
    this.getData();
  },
  methods: {
    deskripsiKhusus() {
      if (this.ronaAwal.name === undefined || this.ronaAwal.name === ''){
        return 'Deskripsi Khusus';
      } else {
        return 'Deskripsi Khusus ' + this.ronaAwal.name + ' terkait ' + this.currentSubProjectComponentName;
      }
    },
    searchRonaAwal(queryString, cb) {
      var ronaAwalList = this.ronaAwalList;
      var results = queryString ? ronaAwalList.filter(this.createFilter(queryString)) : ronaAwalList;
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
          this.$emit('handleCloseAddRonaAwal', true);
        })
        .catch((error) => {
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
          console.log(error);
        });
    },
    handleClose() {
      this.$emit('handleCloseAddRonaAwal', false);
    },
    async getData() {
      const { data } = await componentTypeResource.list({});
      this.componentTypes = data;
      const ronaAwals = await ronaAwalResource.list({
        all: true,
      });
      const raList = [];
      ronaAwals.data.forEach(ra => {
        raList.push({
          id: ra.id,
          value: ra.name,
        });
      });
      this.ronaAwalList = raList;
      const ps = await projectStageResource.list({
        ordered: true,
      });
      this.projectStages = ps.data;
      this.subProjects.utama.map((u) => {
        this.subProjectsArray.push(u);
      });
      this.subProjects.pendukung.map((p) => {
        this.subProjectsArray.push(p);
      });
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

