<template>
  <div v-loading="isSaving">
    <el-dialog
      :title="title[mode] + ' Komponen Lingkungan'"
      :visible.sync="show"
      width="50%"
      height="450"
      :destroy-on-close="true"
      :before-close="handleClose"
      :close-on-click-modal="false"
      @open="onOpen"
    >
      <div v-loading="isSaving">
        <el-form v-if="(data !== null) && (data.sub_projects != null) && (data.component !== null) " style="padding: 0 2em;">
          <div style="line-height: 140%;">
            <div><strong>Tahap</strong></div>
            <div>{{ data.project_stage.name }}</div>
          </div>

          <div style="line-height: 140%; margin-top: 1em; ">
            <div><strong>Kegiatan Utama/Pendukung</strong></div>
            <div>{{ data.sub_projects.name }}</div>
          </div>

          <div v-if="masterComponent" style="line-height: 140%; margin-top: 1.5em; padding: 1em; border:1px solid #cccccc; border-radius: 0.5em;">
            <div><strong>Komponen Kegiatan</strong></div>
            <div>{{ masterComponent.name }}</div>
            <deskripsi :description="masterComponent.description" :measurement="masterComponent.measurement" />
          </div>
          <div style="line-height: 140%; margin-top: 1.5em; padding: 1em; border:1px solid #cccccc; border-radius: 0.5em;">
            <div><strong><u>Komponen {{ masterComponent.name }}</u> pada <u>Kegiatan {{ data.sub_projects.name }}</u></strong></div>
            <div style="margin-top:1em;">
              <deskripsi :description="data.component.description" :measurement="data.component.measurement" />
            </div>
          </div>

          <el-form-item label="Rona Lingkungan" style="line-height: 140%; margin-top:1.5em; padding:1em; border:1px solid #cccccc; border-radius: 0.5em;">
            <el-select
              v-if="master === null"
              v-model="hue.id"
              placeholder="Pilih Rona Lingkungan"
              style="width:100%"
              @change="onChangeHue"
            >
              <el-option
                v-for="item in masterHues"
                :key="'scoping_opt_hue_'+ item.id"
                :label="item.name"
                :value="item.id"
              />
            </el-select>
            <div v-else style="font-size:110%: float:none; clear: both;">{{ master.name || '' }} &nbsp;<i v-if="master.is_master" class="el-icon-success" style="color:#2e6b2e;" /></div>
            <div style="margin-top:1em;">
              <deskripsi v-if="selected !== null" :description="selected.description" :measurement="selected.measurement" />
            </div>
          </el-form-item>
          <div v-if="selected !== null" style="margin: 2em 0 1em; font-weight:bold;">
            {{ 'Deskripsi '+ hue.name +' terkait '+ masterComponent.name + ' pada Kegiatan '+ data.sub_projects.name }}
          </div>
          <el-form-item v-if="selected !== null" style="margin: 1em 0;">
            <hueeditor
              :key="'hue_scoping_editor_3'"
              v-model="hue.description"
              output-format="html"
              :menubar="''"
              :image="false"
              :height="100"
              :toolbar="['bold italic underline bullist numlist  preview undo redo fullscreen']"
              style="width:100%"
            />
          </el-form-item>
          <el-form-item v-if="selected !== null " label="Besaran">

            <el-input
              v-model="hue.measurement"
              type="textarea"
              :autosize="{ minRows: 3, maxRows: 5}"
            />

          </el-form-item>
        </el-form>
      </div>
      <span slot="footer" class="dialog-footer">
        <el-button type="danger" :disabled="isSaving" @click="handleClose">Batal</el-button>
        <el-button type="primary" :disabled="isSaving" @click="handleSaveForm">Simpan</el-button>
      </span>
    </el-dialog>
  </div>
</template>
<script>
import Resource from '@/api/resource';
import Deskripsi from '../helpers/Deskripsi.vue';
import SCHEditor from '@/components/Tinymce';
const subProjectHueResource = new Resource('sub-project-rona-awals');

export default {
  name: 'FormAddHue',
  components: { Deskripsi, 'hueeditor': SCHEditor },
  props: {
    show: { type: Boolean, default: false },
    data: {
      type: Object,
      default: null,
    },
    masterComponent: {
      type: Object,
      default: null,
    },
    master: {
      type: Object,
      default: null,
    },
    masterHues: {
      type: Array,
      default: function() {
        return [];
      },
    },
  },
  data(){
    return {
      hue: {
        id: null,
        name: '',
        value: '',
        description: '',
        measurement: '',
        id_sub_project_component: null,
        id_sub_project_rona_awal: null,
        id_project: null,
        id_component: null,
      },
      selected: null,
      isSaving: false,
      title: ['Tambah', 'Edit'],
      mode: 0,
    };
  },
  mounted(){
    this.onOpen();
  },
  methods: {
    async handleSaveForm(){
      this.isSaving = true;
      this.hue.id_sub_project_component = this.data.component.id_sub_project_component;
      this.hue.id_project = this.data.id_project;
      this.hue.id_project_component = this.masterComponent.id_project_component;
      this.hue.id_project_rona_awal = this.selected.id_project_rona_awal;
      await subProjectHueResource.store(this.hue)
        .then((res) => {
          this.hue.id_sub_project_rona_awal = res.id_sub_project_rona_awal;
          this.$message({
            message: 'Komponen lingkungan berhasil disimpan',
            type: 'success',
            duration: 5 * 1000,
          });

          this.$emit('onSave', {
            id: this.hue.id,
            name: this.hue.name,
            value: this.hue.value,
            id_sub_project_rona_awal: this.hue.id_sub_project_rona_awal,
            id_sub_project_component: this.hue.id_sub_project_component,
            description: this.hue.description,
            measurement: this.hue.measurement,
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
    handleClose(){
      this.initData();
      this.$emit('onClose', true);
    },
    initData(){
      this.hue.id = null;
      this.hue.name = '';
      this.hue.description = '';
      this.hue.measurement = '';
      this.hue.id_sub_project_rona_awal = null;
      this.hue.id_project = null;
      this.hue.id_sub_project_component = null;
      this.hue.id_component = null;
      this.selected = null;
    },
    onOpen(){
      this.isSaving = false;
      if (this.master === null) {
        this.mode = 0;
        this.selected = null;
        this.hue = {
          id: null,
          name: '',
          description: '',
          measurement: '',
          id_sub_project_component: this.data.rona_awal.id_sub_project_component,
          id_sub_project_rona_awal: null,
          id_project: this.data.sub_projects.id_project,
          id_component: null,
        };
        console.log('add...', this.hue);
      } else {
        this.mode = 1;
        this.hue = {
          id: this.master.id,
          name: this.master.name,
          description: this.data.rona_awal.description,
          measurement: this.data.rona_awal.measurement,
          id_sub_project_component: this.data.rona_awal.id_sub_project_component,
          id_sub_project_rona_awal: this.data.rona_awal.id_sub_project_rona_awal,
          id_project: this.data.sub_projects.id_project,
          id_component: this.master.id,
        };
        this.selected = this.master;
      }
    },
    onChangeHue(val){
      this.selected = this.masterHues.find(h => h.id === val);
      this.hue.name = this.selected.name;
    },
  },
};
</script>
