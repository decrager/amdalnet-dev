<template>
  <div class="master-komponen-kegiatan">
    <el-card shadow="never" header="Komponen Kegiatan" class="clearfix">
      <div>
        <p v-for="comp in components" :key="comp.id+'_cp'" style="text-align:center; background: #fff; border: 1px solid #e0e0e0; border-radius:0.3em; padding: 0.5em;">
          <el-button type="danger" icon="el-icon-close" size="mini" plain square style="float:left; width:20px; padding: 3px 0;" @click="removeComponent(comp.id)" />
          {{ comp.nama }}  <i v-if="comp.isMaster" class="el-icon-success" style="color:#2e6b2e;" />
          <i class="el-icon-edit" size="mini" style="float:right; padding:5px; cursor:pointer;" @click="editComponent(comp.id)" />
        </p>
      </div>

      <!-- form -->
      <el-button icon="el-icon-plus" size="mini" circle type="primary" plain @click="showForm = true" />
      <form-komponen-kegiatan :show="showForm" @onClose="showForm = false" />

    </el-card>
  </div>
</template>
<script>
import FormKomponenKegiatan from './forms/FormKomponenKegiatan.vue';

export default {
  name: 'KomponenKegiatan',
  components: { FormKomponenKegiatan },
  data(){
    return {
      selectedData: null,
      components: [],
      mode: 0,
      showForm: false,
    };
  },
  methods: {
    onSaveData(obj){
      console.log('obj:', obj);
      console.log('selectedData', this.selectedData);

      const index = this.components.findIndex(e => e.id === this.selectedData.id);
      if (index < 0){
        this.components.push(this.selectedData);
      }
      this.selectedData = null;
    },
    addComponent(){

    },
    editComponent(id){
      this.selectedData = this.components.find(e => e.id === id);
      this.mode = 1;
      this.showForm = true;
    },
    removeComponent(id){
      if (this.components.length === 0) {
        return;
      }
      const idx = this.components.findIndex(e => e.id === id);
      if (idx >= 0){
        this.components.splice(idx, 1);
      }
    },
  },
};
</script>
<style lang="scss" scoped>
.master-komponen-kegiatan .el-card__body {
  background: #f0f0f0 !important;
}
</style>
