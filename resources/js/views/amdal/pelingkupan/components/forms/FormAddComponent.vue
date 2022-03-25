<template>
  <el-dialog
    :title="'Tambah Komponen Lingkungan'"
    :visible.sync="show"
    width="50%"
    :before-close="handleClose"
  >
    <div v-loading="isSaving">
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
            v-model="component.id_component"
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

        <el-form-item label="Deskripsi">
          <!-- <scopingceditor
          v-model="component.description_specific"
          output-format="html"
          :menubar="''"
          :image="false"
          :height="100"
          :toolbar="['bold italic underline bullist numlist  preview undo redo fullscreen']"
          style="width:100%"
        /> -->
          <el-input
            v-model="component.description"
            type="textarea"
            :autosize="{ minRows: 3, maxRows: 5}"
          />
        </el-form-item>
        <el-form-item label="Besaran">
          <el-input
            v-model="component.measurement"
            type="textarea"
            :autosize="{ minRows: 3, maxRows: 5}"
          />
        </el-form-item>

      </el-form>
    </div>
    <span slot="footer" class="dialog-footer">
      <el-button type="danger" @click="handleClose">Batal</el-button>
      <el-button type="primary" @click="submitComponent">Simpan</el-button>
    </span>
  </el-dialog>
</template>
<script>

import Resource from '@/api/resource';
// import DeskripsiBesaran from '../../../components/dialogs/DeskripsiBesaran.vue';
// import SCEditor from '@/components/Tinymce';

const subProjectComponentResource = new Resource('sub-project-components');
export default {
  name: 'FormAddComponent',
  //  components: { 'scopingceditor': SCEditor  },
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
        id_component: null,
        id_sub_project: null,
        name: '',
        description: '',
        measurement: '',
        id_sub_project_component: null,
      },
      isSaving: false,
    };
  },
  methods: {
    handleClose(){
      this.$emit('onClose', true);
    },
    initComponent(){
      this.component = {
        id: null,
        id_component: null,
        id_sub_project: null,
        name: '',
        description: '',
        measurement: '',
        id_sub_project_component: null,
      };
      this.isSaving = false;
    },
    handleSelectComponent(val){
      this.data.component = this.masterComponents.find(e => e.id === val);
      this.component.name = this.data.component.name;
    },
    async submitComponent() {
      this.isSaving = true;
      this.component.id = this.component.id_component;
      this.component.id_sub_project = this.data.sub_projects.id;
      await subProjectComponentResource.store(
        this.component
      ).then((res) => {
        this.component.id_sub_project_component = res;
        this.$message({
          message: 'Komponen kegiatan berhasil disimpan',
          type: 'success',
          duration: 5 * 1000,
        });
        this.$emit('onSave', {
          id: this.component.id,
          name: this.component.name,
          value: this.component.value,
          id_sub_project_component: res,
          description: this.component.description,
          measurement: this.component.measurement,
          id_project_stage: this.data.project_stage.id,
        });
      })
        .catch((err) => {
          this.$message({
            message: 'Gagal menyimpan komponen kegiatan',
            type: 'error',
            duration: 5 * 1000,
          });
          console.log(err);
        })
        .finally(() => {
          this.isSaving = false;
          this.handleClose();
        });
    },
  },

};
</script>
