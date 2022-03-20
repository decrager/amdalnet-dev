<template>
  <div>
    <el-dialog
      :title="operations[mode] + ' Komponen Lingkungan'"
      :visible.sync="show"
      width="60%"
      height="450"
      :destroy-on-close="true"
      :before-close="handleClose"
      :close-on-click-modal="false"
      @open="onOpen"
    >
      <el-form label-position="top">

        <el-form-item
          v-if="mode === 0"
          label="Tipe Komponen Lingkungan"
        >
        <!--
          <el-select
            v-model="value"
            placeholder="Select"
          >
            <el-option
              v-for="item in options"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select> -->

        </el-form-item>
        <div v-else>
          <div>Tahap</div>
          <div class="header" style="font-weght:bold; text-transform: uppercase;font-size:110%; color:#202020;">
            {{ data.project_stage_name || 'UNKNOWN' }}
          </div>
        </div>

        <el-form-item label="Komponen Kegiatan">
          <el-autocomplete
            v-if="mode===0"
            v-model="data.name"
            class="inline-input"
            clearable
            :fetch-suggestions="querySearch"
            placeholder="Please Input"
            :trigger-on-focus="false"
            style="width:60%;"
            @clear="clear"
            @select="handleSelect"
          />
          <span v-else>
            {{ data.name }}
          </span>
          <i v-if="data.is_master" class="el-icon-success" />
        </el-form-item>
        <el-row :gutter="20">
          <el-col :span="12">
            <el-form-item label="Deskripsi">
              <el-input
                v-model="data.description"
                type="textarea"
                :autosize="{ minRows: 3, maxRows: 5}"
                placeholder="Deskripsi komponen kegiatan..."
              />
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="Besaran">
              <el-input
                v-model="data.measurement"
                type="textarea"
                :autosize="{ minRows: 3, maxRows: 5}"
                placeholder="Deskripsi komponen kegiatan..."
              />
            </el-form-item>
          </el-col>
        </el-row>
      </el-form>

      <span slot="footer" class="dialog-footer">
        <el-button @click="handleClose">Batal</el-button>
        <el-button type="primary" @click="handleSaveForm">Simpan</el-button>
      </span>
    </el-dialog>
  </div>
</template>
<script>
import Resource from '@/api/resource';
const ronaAwalResource = new Resource('rona-awals');

export default {
  name: 'FormKomponenLingkungan',
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
      this.data = {
        id: 0,
        name: '',
        id_component_type: 0,
        is_master: false,
        description: '',
        measurement: '',
        value: '',
      };
    },
    onOpen(){
      console.log('opening!');
      switch (this.mode){
        case 0:
          break;
        case 1:
          // edit mode
          this.data = this.input;
          break;
      }
      console.log(this.data);
    },
    handleSaveForm(){
      // save data in this form!

      this.$emit('onSave', this.data);
      this.handleClose();
    },
    async querySearch(queryString, cb) {
      if (queryString.length < 3) {
        return cb([]);
      }
      var response = await ronaAwalResource.list({
        q: queryString,
      });

      var results = response.data;

      // var results = queryString ? options.filter(this.createFilter(queryString)) : options;
      // call callback function to return suggestions
      if (results.length === 1) {
        console.log('result: ', results);
      }
      if (results.length === 0){
        this.data.id = 0;
        this.data.is_master = false;
        this.data.name = queryString;
        this.data.value = queryString;
      }
      cb(results);
    },
    handleSelect(item){
      this.data.id = item.id;
      this.data.name = item.name;
      this.data.id_component_type = item.id_component_type;
      this.data.is_master = item.is_master;
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
