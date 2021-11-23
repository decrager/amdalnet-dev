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
          v-model="ronaAwal.id_project_stage"
          placeholder="Tahap Kegiatan"
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
          v-model="ronaAwal.id_sub_project"
          placeholder="Pilih Kegiatan"
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
          v-model="ronaAwal.id_sub_project_component"
          placeholder="Pilih Komponen Kegiatan"
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
          v-model="ronaAwal.id_component_type"
          placeholder="Pilih Tipe Komponen Lingkungan"
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
        <el-input v-model="ronaAwal.name" />
      </el-form-item>
      <el-form-item label="Definisi">
        <el-input v-model="ronaAwal.name" />
      </el-form-item>
      <el-form-item label="Umum">
        <el-input v-model="ronaAwal.description_common" />
      </el-form-item>
      <el-form-item label="Khusus">
        <el-input v-model="ronaAwal.description_specific" />
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
const projectStageResource = new Resource('project-stages');
const componentTypeResource = new Resource('component-types');
const scopingResource = new Resource('scoping');

export default {
  name: 'AddRonaAwalDialog',
  props: {
    show: Boolean,
    subProjects: {
      type: Array,
      default: () => [],
    },
    subProjectComponents: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      ronaAwal: {},
      subProjectsArray: [],
      subProjectComponentsArray: [],
      projectStages: [],
      componentTypes: [],
    };
  },
  mounted() {
    this.getData();
  },
  methods: {
    submitRonaAwal() {
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
          // reload PelingkupanTable
          this.$emit('handleCloseAddRonaAwal', true);
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
    },
  },
};
</script>

