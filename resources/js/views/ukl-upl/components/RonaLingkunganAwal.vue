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
import SumberDampak from './SumberDampak.vue';
import RonaAwalTable from './RonaAwalTable.vue';

export default {
  name: 'RonaLingkunganAwal',
  components: { SumberDampak, RonaAwalTable },
  data() {
    return {
      components: [],
      ronaAwals: [],
    };
  },
  mounted() {
  },
  methods: {
    handleSaveForm(){
      this.$emit('handleSaveRonaAwalData', this.rona_awal);
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
