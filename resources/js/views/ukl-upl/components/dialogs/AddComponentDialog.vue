<template>
  <el-dialog
    title="Tambah Komponen Kegiatan"
    :visible.sync="show"
    width="40%"
    :before-close="handleClose"
  >

    <el-form label-position="top" :model="component">
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
        <el-input v-model="component.name" />
      </el-form-item>
      <el-form-item label="Umum">
        <el-input
          v-model="component.description_common"
          :disabled="disableDescCommon"
        />
      </el-form-item>
      <el-form-item label="Khusus">
        <el-input v-model="component.description_specific" />
      </el-form-item>
      <el-form-item label="Besaran">
        <el-input v-model="component.description_specific" type="textarea" :rows="2" />
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
const projectStageResource = new Resource('project-stages');
const scopingResource = new Resource('scoping');
const subProjectComponentResource = new Resource('sub-project-components');

export default {
  name: 'AddComponentDialog',
  props: {
    show: Boolean,
    idProjectStage: {
      type: Number,
      default: () => 0,
    },
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
      component: {},
      subProjectsArray: [],
      projectStages: [],
      disableDescCommon: false,
    };
  },
  mounted() {
    this.getData();
  },
  methods: {
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
          this.$emit('handleCloseAddComponent', true);
          this.$emit('handleSetCurrentIdSubProjectComponent', response.data.id);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    handleClose() {
      this.$emit('handleCloseAddComponent', false);
    },
    async getData() {
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
      // common desc
      const comps = await subProjectComponentResource.list({
        id_sub_project: this.currentIdSubProject,
      });
      if (comps.data.length > 0) {
        const firstComp = comps.data[0];
        this.component.description_common = firstComp.description_common;
        this.disableDescCommon = true;
      }
    },
  },
};
</script>
