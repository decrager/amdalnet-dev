<template>
  <div>
    <el-button
      type="success"
      size="small"
      icon="el-icon-check"
      @click="handleSaveForm()"
    >
      Simpan Perubahan
    </el-button>
    <h3>Komponen Kegiatan yang Menjadi Sumber Dampak</h3>
    <sumber-dampak
      @handleSaveComponents="handleSaveComponents"
      @handleUpdateComponents="handleUpdateComponents"
    />
    <h3 style="margin-top:20px;">Rona Lingkungan Awal</h3>
    <rona-awal-table
      @handleSaveRonaAwals="handleSaveRonaAwals"
      @handleUpdateRonaAwals="handleUpdateRonaAwals"
    />
  </div>
</template>

<script>
import Resource from '@/api/resource';
import SumberDampak from './SumberDampak.vue';
import RonaAwalTable from './RonaAwalTable.vue';
const projectComponentResource = new Resource('project-components');
const projectRonaAwalResource = new Resource('project-rona-awals');

export default {
  name: 'RonaLingkunganAwal',
  components: { SumberDampak, RonaAwalTable },
  data() {
    return {
      idProject: 0,
      components: [],
      ronaAwals: [],
    };
  },
  mounted() {
    this.idProject = parseInt(this.$route.params && this.$route.params.id);
  },
  methods: {
    handleSaveForm(){
      this.storeComponents();
      this.storeRonaAwals();
    },
    storeComponents() {
      this.components.map((component) => {
        component['id_component'] = component['id'];
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
        })
        .catch((error) => {
          console.log(error);
        });
    },
    storeRonaAwals() {
      this.ronaAwals.map((ronaAwal) => {
        ronaAwal['id_rona_awal'] = ronaAwal['id'];
        ronaAwal['id_project'] = this.idProject;
      });
      projectRonaAwalResource
        .store({
          'rona_awals': this.ronaAwals,
        })
        .then((response) => {
          var message = (response.code === 200) ? 'Rona awal berhasil disimpan' : 'Terjadi kesalahan pada server';
          var message_type = (response.code === 200) ? 'success' : 'error';
          this.$message({
            message: message,
            type: message_type,
            duration: 5 * 1000,
          });
          // reload accordion
          this.$emit('handleReloadVsaList', 2);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    async handleSaveComponents(data){
      this.components = await data;
    },
    handleUpdateComponents(data){
      this.components = data;
    },
    async handleSaveRonaAwals(data){
      this.ronaAwals = await data;
    },
    handleUpdateRonaAwals(data){
      this.ronaAwals = data;
    },
  },
};
</script>
