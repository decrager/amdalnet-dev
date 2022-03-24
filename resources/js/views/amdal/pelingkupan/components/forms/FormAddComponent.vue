<template>
  <el-dialog
    :visible.sync="show"
    width="60%"
    :before-close="handleClose"
  >
    <el-form label-position="top" :model="data">
      <!-- tahap -->
      <div style="line-height: 150% !important;">
        <div><strong>Tahap Kegiatan</strong></div>
        <div style="font-size:110%">{{ data.project_stage.name || '' }}</div>
      </div>

      <div style="line-height: 150% !important; margin-top: 1em;">
        <div><strong>Kegiatan Utama/Pendukung</strong></div>
        <div style="font-size:110%">{{ data.sub_projects.name || '' }}</div>
      </div>

      <el-form-item
        label="Komponen Kegiatan"
        prop="name"
        style="padding: 0.5em 0.8em; border: 1px solid #e0e0e0; border-radius: 1em; margin-top: 1em;"
      >
        <!-- <div v-if="isEdit" style="font-size:120%;">
          <div>{{ component.name || component.component.name }}</div>
        </div> -->
        <el-select
          v-model="component.id"
          style="width:100%"
          clearable
          filterable
          @change="handleSelectComponent"
        >
          <el-option
            v-for="item in masterComponents"
            :key="item.value"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
        <!-- <deskripsi-besaran v-if="component.id !== null "
          :data="masterComponents.find(e => e.id === component.id)"
        /> -->
        <div v-if="data.component !== null" style="line-height: 130%; margin-top:1em; word-break:break-word;">
          <el-row :gutter="20">
            <el-col :span="12">
              <div><b>Deskripsi</b></div>
              <div v-html="data.component.description" />
            </el-col>
            <el-col :span="12">
              <div><b>Besaran</b></div>
              <div style="margin-top: 1em;">{{ data.component.measurement }}</div>
            </el-col>
          </el-row>
        </div>
      </el-form-item>
      <!--
      <div style="font-weight:bold; ">{{ component.name }} pada {{ data.sub_projects.name }}</div>
      <el-form-item label="Deskripsi">
        <componenteditor
          v-model="component.description_specific"
          output-format="html"
          :menubar="''"
          :image="false"
          :height="100"
          :toolbar="['bold italic underline bullist numlist  preview undo redo fullscreen']"
          style="width:100%"
        />
      </el-form-item>
      <el-form-item label="Besaran">
        <el-input
          v-model="component.unit"
          type="textarea"
          :autosize="{ minRows: 3, maxRows: 5}"
        />
      </el-form-item> -->

    </el-form>

  </el-dialog>
</template>
<script>
// import DeskripsiBesaran from '../../../components/dialogs/DeskripsiBesaran.vue';
// import Editor from '@/components/Tinymce';

export default {
  name: 'FormAddComponent',
  components: { /* 'componenteditor': Editor */ },
  props: {
    data: {
      type: Object,
      default: null,
    },
    show: {
      type: Boolean,
      default: false,
    },
    masterComponents: {
      type: Array,
      default: function(){
        return [];
      },
    },
  },
  data(){
    return {
      component: {
        id: null,
        id_sub_project: null,
        name: '',
        description_specific: '',
        unit: '',
      },
    };
  },
  methods: {
    handleClose(){
      this.$emit('onClose', true);
    },
    initComponent(){

    },
    handleSelectComponent(val){
      this.data.component = this.masterComponents.find(e => e.id === val);
    },
  },

};
</script>
