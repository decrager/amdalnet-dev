<template>
  <div>
    <el-dialog
      :title="'Master Komponen Lingkungan'"
      :visible.sync="show"
      width="40%"
      height="450"
      :destroy-on-close="true"
      :before-close="handleClose"
      :close-on-click-modal="false"
      @open="onOpen"
    >
      <el-form v-loading="saving" label-position="top">

        <el-form-item
          v-if="formMode === 0"
          label="Kategori Komponen Lingkungan"
        >

          <el-select
            v-model="data.id_component_type"
            placeholder="Pilih Kategori Komponen Lingkungan"
            @change="onChangeType"
          >
            <el-option
              v-for="item in componentTypes"
              :key="'master_hue_option_' + item.id"
              :label="item.name"
              :value="item.id"
            />
          </el-select>

        </el-form-item>
        <div v-else>
          <div><strong>Kategori Komponen Lingkungan</strong></div>
          <div style="font-size:120%; color:#202020;">
            {{ getComponentTypeName(data.id_component_type) }}
          </div>
        </div>
        <el-form-item
          v-if="formMode === 0"
          label="Rona Lingkungan"
        >
          <el-select
            v-model="name"
            style="width:100%"
            clearable
            filterable
            :disabled="noMaster"
            placeholder="Pilih Rona Lingkungan"
            :loading="loading"
            loading-text="Memuat data..."
            @change="handleSelect"
          >
            <el-option
              v-for="item in master"
              :key="item.value"
              :label="item.name"
              :value="item.name"
            >
              <span>{{ item.name }} &nbsp;<i v-if="item.is_master" class="el-icon-success" style="color:#2e6b2e;" /></span>
            </el-option>
          </el-select>
          <el-checkbox v-model="noMaster"><span style="font-size: 90%;">Menambahkan Rona Lingkungan</span></el-checkbox>
          <el-input
            v-if="noMaster"
            v-model="data.name"
            placeholder="Nama Rona Lingkungan..."
          />
        </el-form-item>
        <div v-else-if="data.is_master === true" style="margin: 2em 0;">
          <div><strong>Rona Lingkungan</strong></div>
          <div style="font-size:120%; color:#202020;">
            {{ data.name }} <i class="el-icon-success" style="color:#2e6b2e;" />
          </div>
        </div>
        <el-form-item v-else label="Rona Lingkungan">
          <el-input
            v-model="data.name"
            placeholder="Nama Rona Lingkungan..."
          />
        </el-form-item>
        <el-form-item label="Deskripsi">
          <huedesceditor
            v-model="data.description"
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
            v-model="data.measurement"
            type="textarea"
            :autosize="{ minRows: 3, maxRows: 5}"
            placeholder="Besaran Rona Lingkungan..."
          />
        </el-form-item>

      </el-form>

      <span slot="footer" class="dialog-footer">
        <div v-if="pdfError || pdfName" style="display: inline-block;">
          <small v-if="pdfError" style="color:red;">{{ pdfError }}</small>
          <small v-if="pdfName">{{ pdfName }}</small>
        </div>
        <el-upload
          action="#"
          :auto-upload="false"
          :on-change="handleUploadPDF"
          :show-file-list="false"
          style="display: inline-block; margin-right: 11px;"
        >
          <el-button type="warning"> Upload PDF </el-button>
        </el-upload>
        <el-button type="danger" @click="handleClose">Batal</el-button>
        <el-button type="primary" :disabled="disableSave()" @click="handleSaveForm">Simpan</el-button>
      </span>
    </el-dialog>
  </div>
</template>
<script>
import Resource from '@/api/resource';
import HueDescEditor from '@/components/Tinymce';
const ronaAwalResource = new Resource('rona-awals');
const projectRonaAwalResource = new Resource('project-rona-awals');

export default {
  name: 'FormKomponenLingkungan',
  components: { 'huedesceditor': HueDescEditor },
  props: {
    mode: {
      type: Number,
      default: 0,
    },
    show: {
      type: Boolean,
      default: false,
    },
    input: {
      type: Object,
      default: function(){
        return null;
      },
    },
    componentTypes: {
      type: Array,
      default: function(){
        return [];
      },
    },
    formMode: {
      type: Number,
      default: 0,
    },
  },
  data(){
    return {
      project_id: 0,
      loading: false,
      operations: ['Tambah', 'Edit'],
      options: [],
      name: '',
      showForm: false,
      data: null,
      master: [],
      noMaster: false,
      saving: false,
      pdfName: null,
      pdfFile: null,
      pdfError: null,
    };
  },
  /* watch: {
    show: function(val){
      console.log(val);
    },
  },*/
  mounted() {
    this.project_id = parseInt(this.$route.params && this.$route.params.id);
    this.options = [];
    this.initData();
  },
  methods: {
    initData(){
    /**
     * Big Notes:
     * data = {
     *   id, name, id_component_type, is_master, // from rona_awal table
     *     description, measurement // to go to projects_rona_awals
     * }
     **/
      this.master = [];
      this.name = '';
      this.data = {
        id: null,
        name: '',
        id_component_type: null,
        is_master: false,
        description: '',
        measurement: '',
        value: '',
        component_type_name: '',
        id_project: parseInt(this.$route.params && this.$route.params.id),
        id_project_rona_awal: null,
      };
      this.pdfName = null;
      this.pdfFile = null;
      this.pdfError = null;
    },
    onOpen(){
      console.log('opening!');
      switch (this.formMode){
        case 0:
          this.initData();
          break;
        case 1:
          // edit mode
          this.data = this.input;
          break;
      }
      console.log(this.data);
    },
    async handleSaveForm(){
      // save data in this form!
      this.saving = true;
      const formData = new FormData();
      formData.append('id_project', this.project_id);
      formData.append('component', JSON.stringify(this.data));
      formData.append('mode', this.mode);

      if (this.pdfFile) {
        formData.append('file', await this.convertBase64(this.pdfFile));
      }

      await projectRonaAwalResource.store(formData)
        .then((res) => {
          this.data.id_project_rona_awal = res.data.id;
        })
        .catch((err) => {
          console.log(err);
        })
        .finally(() => {
          this.saving = false;
        });

      this.$emit('onSave', this.data);
      this.handleClose();
    },
    async getHues(){
      this.master = [];
      this.loading = true;
      const project_id = parseInt(this.$route.params && this.$route.params.id);
      await ronaAwalResource.list({
        id_component_type: this.data.id_component_type,
        project_id: project_id,
      })
        .then((res) => {
          this.master = res.data;
        })
        .finally(() => {
          this.loading = false;
        });
    },
    handleSelect(val){
      const item = this.master.find(i => i.name === val);
      this.data.id = item.id;
      this.data.name = item.name;
      this.data.value = item.name;
      this.data.is_master = item.is_master;
      console.log(this.data);
    },
    handleClose(){
      this.initData();
      this.close();
    },
    close(){
      this.$emit('onClose', true);
    },
    clear(){
      this.initData();
    },
    onChangeType(val){
      this.master = [];
      this.data.id = null;
      this.name = '';

      const ctype = this.componentTypes.find(c => c.id === parseInt(val));
      this.data.component_type_name = ctype.name;
      this.getHues();
    },
    getComponentTypeName(id){
      const ctype = this.componentTypes.find(c => c.id === id);
      if (ctype){
        return ctype.name;
      }
      return '';
    },
    disableSave(){
      const emptyTexts = (this.data.description === null) ||
        ((this.data.description).trim() === '') ||
        (this.data.measurement === null) ||
        ((this.data.measurement).trim() === '');

      if (this.noMaster){
        return ((this.data.name).trim() === '') || emptyTexts;
      }
      return (this.data.id === null) || (this.data.id <= 0) || emptyTexts;
    },
    handleUploadPDF(file, filelist) {
      this.pdfError = null;
      this.pdfFile = null;
      this.pdfName = null;
      if (file.raw.size > 2097152) {
        this.pdfError = 'Ukuran File Tidak Boleh Melebihi 2 MB';
        return;
      }

      const extension = file.name.split('.');
      if (extension[extension.length - 1].toLowerCase() !== 'pdf') {
        this.pdfError = 'File Harus Berformat PDF';
        return;
      }

      this.pdfFile = file.raw;
      this.pdfName = file.name;
    },
    convertBase64(file) {
      return new Promise((resolve, reject) => {
        const fileReader = new FileReader();
        fileReader.readAsDataURL(file);

        fileReader.onload = () => {
          resolve(fileReader.result);
        };

        fileReader.onerror = (error) => {
          reject(error);
        };
      });
    },
  },
};
</script>
