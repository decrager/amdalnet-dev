<template>
  <div class="master-komponen">
    <el-card shadow="never">
      <div slot="header" class="clearfix card-header" style="text-align:center; font-weight:bold; text-transform: uppercase;">
        <span>Komponen Kegiatan ({{ components.length }})</span>
      </div>

      <components-list
        :id="'kegiatan'"
        :components="components"
        :is-master-component="true"
        @edit="editComponent"
        @delete="removeComponent"
      />
      <!-- form -->
      <el-button icon="el-icon-plus" size="mini" circle type="primary" plain @click="addComponent" />
      <form-komponen-kegiatan
        :show="showForm"
        :mode="mode"
        :stages="projectStages"
        :input="selectedData"
        @onSave="onSaveData"
        @onClose="showForm = false"
      />

    </el-card>
  </div>
</template>
<script>
import FormKomponenKegiatan from './forms/FormKomponenKegiatan.vue';
import ComponentsList from './tables/ComponentsList.vue';

export default {
  name: 'KomponenKegiatan',
  components: { FormKomponenKegiatan, ComponentsList },
  props: {
    components: {
      type: Array,
      default: function() {
        return [];
      },
    },
    projectStages: {
      type: Array,
      default: function() {
        return [];
      },
    },
  },
  data(){
    return {
      selectedData: null,
      // components: [],
      mode: 0,
      showForm: false,
    };
  },
  methods: {
    onSaveData(obj){
      console.log('obj:', obj);
      console.log('selectedData', this.selectedData);

      const index = this.components.findIndex(e => e.id === obj.id);
      if (index < 0){
        this.components.push(obj);
      }
      this.selectedData = null;
    },
    addComponent() {
      this.showForm = true;
      this.mode = 0;
    },

    editComponent(id) {
      this.selectedData = this.components.find(e => e.id === id);
      this.mode = 1;
      this.showForm = true;
    },
    removeComponent(id) {
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
