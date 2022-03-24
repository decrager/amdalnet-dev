<template>
  <div>
    <el-dialog
      :title="'Master Data Komponen Kegiatan'"
      :visible.sync="show"
      width="40%"
      height="450"
      :destroy-on-close="true"
      :before-close="handleClose"
      :close-on-click-modal="false"
      @open="onOpen"
    >
      <div :loading="saving">
        <el-form label-position="top">

          <el-form-item
            v-if="mode === 0"
            label="Tahap"
          >
            <el-select
              v-model="data.id_project_stage"
              placeholder="Pilih Tahap"
              style="width:100%"
              @change="onChangeStage"
            >
              <el-option
                v-for="stage in stages"
                :key="'scoping_stage_'+ stage.id"
                :label="stage.name"
                :value="stage.id"
              />
            </el-select>

          </el-form-item>
          <div v-else>
            <div><strong>Tahap</strong></div>
            <div style="font-size:120%; color:#202020;">
              {{ data.project_stage_name }}
            </div>
          </div>

          <el-form-item
            v-if="mode===0"
            label="Komponen Kegiatan"
            :loading="loading"
          >
            <el-select
              v-model="name"
              style="width:100%"
              clearable
              filterable
              :disabled="noMaster"
              :loading="loading"
              loading-text="Memuat data..."
              @change="handleSelect"
            >
              <el-option
                v-for="item in master"
                :key="item.value"
                :label="item.name"
                :value="item.id"
              />
            </el-select>
            <el-checkbox v-model="noMaster"><span style="font-size: 90%;">Menambahkan Komponen Kegiatan</span></el-checkbox>
            <el-input
              v-if="noMaster"
              v-model="data.name"
              placeholder="Nama Komponen Kegiatan..."
            />

          </el-form-item>

          <div v-else style="margin: 2em 0;">
            <div><strong>Komponen Kegiatan</strong></div>
            <div style="font-size:120%; color:#202020;">
              {{ data.name }} <i v-if="data.is_master" class="el-icon-success" style="color:#2e6b2e;" />
            </div>
          </div>

          <!-- -->

          <el-form-item label="Deskripsi">
            <desceditor
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
            />
          </el-form-item>
        </el-form>
      </div>
      <span slot="footer" class="dialog-footer">
        <el-button type="danger" @click="handleClose">Batal</el-button>
        <el-button type="primary" @click="handleSaveForm">Simpan</el-button>
      </span>
    </el-dialog>
  </div>
</template>
<script>
import Resource from '@/api/resource';
import DescEditor from '@/components/Tinymce';

const componentResource = new Resource('components');
const projectComponentResource = new Resource('project-components');

export default {
  name: 'FormKomponenKegiatan',
  components: { 'desceditor': DescEditor },
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
    stages: {
      type: Array,
      default: function() {
        return [];
      },
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
     *   id, name, is_master, id_project_stage, project_stage_name,  // from components table
     *     description, measurement // to go to projects_component
     * }
     **/
      this.master = [];
      this.name = '';
      this.data = {
        id: null,
        name: '',
        is_master: false,
        id_project_stage: null,
        project_stage_name: '',
        description: '',
        measurement: '',
        value: '',
        is_selected: false,
        id_project_component: 0,
      };
    },
    onOpen(){
      console.log('opening!');
      switch (this.mode){
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
    onChangeStage(val){
      // console.log(val);
      this.master = [];
      this.name = '';
      this.data.id = null;
      const stage = this.stages.find(s => s.id === parseInt(val));
      this.data.project_stage_name = stage.name;
      this.getComponents();
    },
    async handleSaveForm(){
      // save data in this form!
      this.saving = true;
      await projectComponentResource.store({
        id_project: this.project_id,
        component: this.data,
      }).then((res) => {
        this.data.id_project_component = res.data.id;
      }).catch((err) => {
        console.log(err);
      })
        .finally(() => {
          this.saving = false;
        });

      console.log(this.data);
      this.$emit('onSave', this.data);
      this.handleClose();
    },
    async querySearch(queryString, cb) {
      if (queryString.length < 3) {
        return cb([]);
      }
      var results = await componentResource.list({
        q: queryString,
      });
      // var results = queryString ? options.filter(this.createFilter(queryString)) : options;
      // call callback function to return suggestions
      if (results.length === 1) {
        console.log('result: ', results);
      }
      /* if (results.length === 0){
        this.data.id = 0;
        this.data.is_master = false;
        this.data.name = queryString;
      }*/
      cb(results);
    },
    async getComponents(){
      console.log('yeah!');
      // if (data.id_project_stage === 0) return [];
      this.loading = true;
      this.master = [];
      const project_id = parseInt(this.$route.params && this.$route.params.id);
      await componentResource.list({
        stage_id: this.data.id_project_stage,
        project_id: project_id,
      }).then((res) => {
        this.master = res;
      }).finally(() => {
        this.loading = false;
      });
    },
    handleSelect(val){
      const item = this.master.find(i => i.id === parseInt(val));
      console.log(val, item);
      this.data.id = item.id;
      this.data.name = item.name;
      this.data.is_master = item.is_master;
      this.data.value = item.name;
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
  },
};
</script>
<style scoped>
.header{
  font-weight:bold;
  text-transform: uppercase;
  font-size:110%;
  color:#202020;
}
</style>
