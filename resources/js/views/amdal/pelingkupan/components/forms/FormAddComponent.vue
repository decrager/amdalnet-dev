<template>
  <el-dialog
    :title=" title[mode] + ' Komponen Lingkungan'"
    :visible.sync="show"
    width="50%"
    :before-close="handleClose"
    @open="onOpen"
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

          <el-select
            v-if="master === null"
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
            >
              <span>{{ item.name }} &nbsp;<i v-if="item.is_master" class="el-icon-success" style="color:#2e6b2e;" /></span>
            </el-option>
          </el-select>
          <div v-else>
            <div style="font-size:110%">{{ master.name || '' }} &nbsp;<i v-if="master.is_master" class="el-icon-success" style="color:#2e6b2e;" /></div>
          </div>
          <div style="margin: 1em 0;">
            <deskripsi v-if="selected !== null" :description="selected.description" :measurement="selected.measurement" />
          </div>
          <!--
            <div v-if="selected !== null" style="line-height: 130%; margin-top:1em; word-break:break-word;">
            <el-row :gutter="20">
              <el-col :span="12">
                <div><b>Deskripsi</b></div>
                <div v-html="selected.description" />
              </el-col>
              <el-col :span="12">
                <div><b>Besaran</b></div>
                <div style="margin-top: 1em;">{{ selected.measurement}}</div>
              </el-col>
            </el-row>
          </div>
        </div>
        -->
        </el-form-item>

        <el-form-item label="Deskripsi">
          <scopingceditor
            :key="'comp_editor_scoping_3'"
            v-model="component.description"
            output-format="html"
            :menubar="''"
            :image="false"
            :height="100"
            :toolbar="['bold italic underline bullist numlist  preview undo redo fullscreen']"
            style="width:100%"
          />
        <!--
          <el-input
            v-model="component.description"
            type="textarea"
            :autosize="{ minRows: 3, maxRows: 5}"
          /> -->
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
import SCEditor from '@/components/Tinymce';
import Deskripsi from '../helpers/Deskripsi.vue';
const subProjectComponentResource = new Resource('sub-project-components');

export default {
  name: 'FormAddComponent',
  components: { 'scopingceditor': SCEditor, Deskripsi },
  props: {
    data: {
      type: Object,
      default: null,
    },
    show: {
      type: Boolean,
      default: false,
    },
    master: {
      type: Object,
      default: () => null,
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
      title: ['Tambah', 'Edit'],
      selected: null,
    };
  },
  methods: {
    handleClose(){
      this.$emit('onClose', true);
    },
    onOpen(){
      if (this.master === null){ // not yet having component ref
        this.mode = 0;
        this.selected = null;
        this.component = {
          id: null,
          id_component: null,
          id_sub_project: null,
          name: '',
          description: '',
          measurement: '',
          id_sub_project_component: null,
        };
      } else {
        this.mode = 1;
        this.selected = this.master;
        this.component = {
          id: this.master.id,
          id_component: this.master.id,
          id_sub_project: this.data.sub_projects.id,
          name: this.master.name,
          description: this.data.component.description,
          measurement: this.data.component.measurement,
          id_sub_project_component: this.data.component.id_sub_project_component,
        };
      }
      console.log('edit component', this.component);
      this.isSaving = false;
    },
    handleSelectComponent(val){
      this.selected = this.masterComponents.find(e => e.id === val);
      // this.component.name = this.selected.name;
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
          id_sub_project: this.component.id_sub_project,
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
