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
      console.log(this.components);
      console.log(this.ronaAwals);
      this.storeComponents();
      this.storeRonaAwals();
    },
    storeComponents() {
      this.components.map((component) => {
        // var inserted = 0;
        component['id_component'] = component['id'];
        component['id_project'] = this.idProject;
        projectComponentResource
          .store(component)
          .then((response) => {
            // inserted++;
          })
          .catch((error) => {
            console.log(error);
          });
        // if (inserted === this.components.length) {
        // }
      });
      this.$message({
        message: 'Komponen Kegiatan Berhasil Disimpan',
        type: 'success',
        duration: 5 * 1000,
      });
    },
    storeRonaAwals() {
      this.ronaAwals.map((ronaAwal) => {
        // var inserted = 0;
        ronaAwal['id_rona_awal'] = ronaAwal['id'];
        ronaAwal['id_project'] = this.idProject;
        projectRonaAwalResource
          .store(ronaAwal)
          .then((response) => {
            // inserted++;
          })
          .catch((error) => {
            console.log(error);
          });
        // if (inserted === this.ronaAwals.length) {
        // }
      });
      this.$message({
        message: 'Rona Lingkungan Awal Berhasil Disimpan',
        type: 'success',
        duration: 5 * 1000,
      });
    },
    async handleSaveComponents(data){
      this.components = await data;
      this.$emit('handleSaveComponents', data);
    },
    handleUpdateComponents(data){
      this.components = data;
      this.$emit('handleUpdateComponents', data);
    },
    async handleSaveRonaAwals(data){
      this.ronaAwals = await data;
      this.$emit('handleSaveRonaAwals', data);
    },
    handleUpdateRonaAwals(data){
      this.ronaAwals = data;
      this.$emit('handleUpdateRonaAwals', data);
    },
  },
};
</script>
