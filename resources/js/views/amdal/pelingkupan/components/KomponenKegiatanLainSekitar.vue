<template>
  <div class="master-komponen">
    <el-card shadow="never">
      <div slot="header" class="clearfix card-header" style="text-align:center; font-weight:bold; text-transform: uppercase;">
        <span>Komponen Kegiatan Lain Sekitar ({{ components.length }})</span>
      </div>
      <div>
        <components-list
          :id="'kegiatan_lain_sekitar_119'"
          :components="components"
          :is-master-component="true"
          @edit="editComponent"
          @delete="removeComponent"
        />
      </div>
      <!-- form -->
      <el-button icon="el-icon-plus" size="mini" circle type="primary" plain @click="addComponent" />
      <form-komponen-kegiatan-lain-sekitar
        :show="showForm"
        :mode="mode"
        :form-mode="formMode"
        :input="selectedData"
        @onSave="onSaveData"
        @onClose="showForm = false"
      />
      <form-delete-component
        v-if="deleted !== null"
        :component="deleted"
        :show="showDelete"
        :resource="'project-kegiatan-lain-sekitar'"
        @close="showDelete = false"
        @delete="afterDeleteComponent"
      />
    </el-card>
  </div>
</template>
<script>
import FormKomponenKegiatanLainSekitar from './forms/FormKomponenKegiatanLainSekitar.vue';
import ComponentsList from './tables/ComponentsList.vue';
import FormDeleteComponent from './forms/FormDeleteComponent.vue';

export default {
  name: 'KomponenKegiatanLainSekitar',
  components: { FormKomponenKegiatanLainSekitar, ComponentsList, FormDeleteComponent },
  props: {
    components: {
      type: Array,
      default: function() {
        return [];
      },
    },
    componentTypes: {
      type: Array,
      default: function() {
        return [];
      },
    },
    mode: {
      type: Number,
      default: 0,
    },
  },
  data(){
    return {
      showForm: false,
      selectedData: null,
      formMode: 0,
      deleted: null,
      showDelete: false,
    };
  },
  methods: {
    onSaveData(obj){
      console.log('save component', obj);
      const index = this.components.findIndex(e => e.id === obj.id);
      if (index < 0){
        this.components.push(obj);
      }
      this.selectedData = null;
    },
    addComponent(){
      this.selectedData = null;
      this.formMode = 0;
      this.showForm = true;
    },
    editComponent(id){
      this.selectedData = this.components.find(e => e.id === id);
      this.formMode = 1;
      console.log(id);
      this.showForm = true;
    },
    removeComponent(id) {
      if (this.components.length === 0) {
        return;
      }
      const co = this.components.find(c => c.id === id);
      this.deleted = co;
      this.showDelete = true;
    },
    afterDeleteComponent(val){
      const idx = this.components.findIndex(e => e.id === val);
      if (idx >= 0){
        this.components.splice(idx, 1);
      }
      this.deleted = null;
      this.showDelete = false;
      this.$emit('delete', true);
    },
  },
};
</script>
<style>

</style>
