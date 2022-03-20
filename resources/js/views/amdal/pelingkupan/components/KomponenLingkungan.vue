<template>
  <div class="master-komponen">
    <el-card shadow="never">
      <div slot="header" class="clearfix card-header" style="text-align:center; font-weight:bold; text-transform: uppercase;">
        <span>Komponen Lingkungan ({{ components.length }})</span>
      </div>
      <components-list
        :id="'rona'"
        :components="components"
        @edit="editComponent"
        @delete="removeComponent"
      />

      <!-- form -->
      <el-button icon="el-icon-plus" size="mini" circle type="primary" plain @click="addComponent" />
      <form-komponen-lingkungan
        :show="showForm"
        :mode="mode"
        :input="selectedData"
        @onSave="onSaveData"
        @onClose="showForm = false"
      />

    </el-card>
  </div>
</template>
<script>
import FormKomponenLingkungan from './forms/FormKomponenLingkungan.vue';
import ComponentsList from './tables/ComponentsList.vue';

export default {
  name: 'KomponenLingkungan',
  components: { FormKomponenLingkungan, ComponentsList },
  props: {
    components: {
      type: Array,
      default: function() {

      },
    },
  },
  data(){
    return {
      showForm: false,
      selectedData: null,
      mode: 0,
    };
  },
  methods: {
    onSaveData(obj){
      const index = this.components.findIndex(e => e.id === obj.id);
      if (index < 0){
        this.components.push(obj);
      }
      this.selectedData = null;
    },
    addComponent(){
      this.mode = 0;
      this.showForm = true;
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
<style>

</style>
