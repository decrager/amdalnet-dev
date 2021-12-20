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
    <rona-awal-table
      @handleSaveRonaAwals="handleSaveRonaAwals"
      @handleUpdateRonaAwals="handleUpdateRonaAwals"
    />
  </div>
</template>

<script>
import Resource from '@/api/resource';
import RonaAwalTable from './tables/RonaAwalTable.vue';
const projectRonaAwalResource = new Resource('project-rona-awals');

export default {
  name: 'RonaLingkunganAwal',
  components: { RonaAwalTable },
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
      this.storeRonaAwals();
    },
    storeRonaAwals() {
      this.ronaAwals.map((ronaAwal) => {
        if (!('id_rona_awal' in ronaAwal)) {
          ronaAwal['id_rona_awal'] = ronaAwal['id'];
        }
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
          this.$emit('handleReloadVsaList', 'matriks-identifikasi-dampak');
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
