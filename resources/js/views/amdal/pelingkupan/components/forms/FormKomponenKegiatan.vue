<template>
  <div>
    <el-dialog
      :title="operations[mode] + ' Komponen Kegiatan'"
      :visible.sync="show"
      width="60%"
      height="450"
      :destroy-on-close="true"
      :before-close="handleClose"
      :close-on-click-modal="false"
    >
      <el-form label-position="top">
        <el-form-item label="Tahap">
          <el-select v-model="value" placeholder="Select">
            <el-option
              v-for="item in options"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </el-form-item>
        <el-form-item label="Komponen Kegiatan">
          <el-autocomplete
            v-model="name"
            class="inline-input"
            clearable
            :fetch-suggestions="querySearch"
            placeholder="Please Input"
            :trigger-on-focus="false"
            style="width:60%;"
            @select="handleSelect"
          /> <i v-if="data.isMaster" class="el-icon-success" />
        </el-form-item>
        <el-row :gutter="20">
          <el-col :span="12">
            <el-form-item label="Deskripsi">
              <el-input
                v-model="data.deskripsi"
                type="textarea"
                :autosize="{ minRows: 3, maxRows: 5}"
                placeholder="Deskripsi komponen kegiatan..."
              />
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="Besaran">
              <el-input
                v-model="data.besaran"
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
const componentResource = new Resource('components');

export default {
  name: 'FormKomponenKegiatan',
  props: {
    mode: {
      type: Number,
      default: 0,
    },
    show: {
      type: Boolean,
      default: false,
    },
  },
  data(){
    return {
      loading: false,
      operations: ['Tambah', 'Edit'],
      options: [],
      name: '',
      showForm: false,
      data: {
        id: 0,
        nama: '',
        deskripsi: 'Deskripsi',
        besaran: 'Besaran',
      },
    };
  },
  watch: {
    show: function(val){
      console.log(val);
    },
  },
  mounted() {
    this.options = [
      { id: 1, name: 'Pilihan1', value: 'Pilihanku', isMaster: true },
      { id: 2, name: 'Pilihan2', value: 'Pilihan2', isMaster: true },
      { id: 3, name: 'Pilihan3', value: 'Pilihan3', isMaster: false },
      { id: 4, name: 'Pilihan4', value: 'Pilihan4', isMaster: true },
      { id: 5, name: 'Pilihan5', value: 'Pilihannya', isMaster: true },
    ];
  },
  methods: {
    handleSaveForm(){
      // save data in this form!

      this.$emit('onSave', this.data);
      this.handleClose();
    },
    /* createFilter(q){
      return (option) => {
        return (option.name).toLowerCase().includes(q.toLowerCase());
      };
    }, */
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
      if (results.length === 0){
        this.data.id = 0;
        this.data.isMaster = false;
        this.data.nama = this.name;
      }
      cb(results);
    },
    handleSelect(item){
      this.data.id = item.id;
      this.data.nama = item.name;
      this.data.isMaster = item.isMaster;
    },
    handleClose(){
      this.data = {
        id: 0,
        nama: '',
        deskripsi: '',
        besaran: '',
      };
      this.name = '';
      this.close();
    },
    close(){
      this.$emit('onClose', true);
    },
  },
};
</script>
