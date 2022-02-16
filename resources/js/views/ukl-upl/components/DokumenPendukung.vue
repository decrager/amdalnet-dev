<template>
  <div class="app-container">
    <div class="text-white fw-bold">Surat Pernyataan Pengelolaan Lingkungan Hidup</div>
    <el-upload
      type="primary"
      class="upload-demo"
      :auto-upload="false"
      :on-change="handleUpdateSppl"
      action="#"
      :show-file-list="true"
      :file-list="fileListSppl"
    >
      <el-button size="small" type="primary">
        Pilih dokumen
      </el-button>
    </el-upload>
    <div class="text-white fw-bold">Dokumen Persetujuan Teknis</div>
    <el-upload
      type="primary"
      class="upload-demo"
      :auto-upload="false"
      :on-change="handleUpdateDpt"
      action="#"
      :show-file-list="true"
      :file-list="fileListDpt"
    >
      <el-button size="small" type="primary">
        Pilih dokumen
      </el-button>
    </el-upload>
  </div>
</template>

<script>
import Resource from '@/api/resource';
import axios from 'axios';
import _ from 'lodash';
const envManageDocsResource = new Resource('env-manage-docs');

export default {
  name: 'DokumenPendukung',
  data() {
    return {
      data: [],
      fileListSppl: [],
      fileListDpt: [],
      formData: {
        'sppl': null,
        'dpt': null,
      },
      idProject: 0,
    };
  },
  mounted() {
    this.getData();
  },
  methods: {
    async getData() {
      this.idProject = parseInt(this.$route.params && this.$route.params.id);
      const { data } = await envManageDocsResource.list({
        id_project: this.idProject,
      });
      if (data.length > 0) {
        if (data.length > 2) {
          this.$message({
            message: 'Data dokumen pendukung butuh perapihan',
            type: 'error',
            duration: 5 * 1000,
          });
        } else {
          const sppl = data.filter(d => d.type === 'SPPL');
          const dpt = data.filter(d => d.type === 'DPT');
          if (sppl.length > 0) {
            this.fileListSppl.push({
              name: sppl[0].filename,
              url: sppl[0].filepath,
              id: sppl[0].id,
            });
            // this.data.push(sppl[0]);
          }
          if (dpt.length > 0) {
            this.fileListDpt.push({
              name: dpt[0].filename,
              url: dpt[0].filepath,
              id: dpt[0].id,
            });
            // this.data.push(dpt[0]);
          }
        }
      } else {
        this.data = [
          {
            id: 0,
            id_project: this.idProject,
            type: 'SPPL',
            filepath: '',
            filename: '',
          },
          {
            id: 0,
            id_project: this.idProject,
            type: 'DPT',
            filepath: '',
            filename: '',
          },
        ];
      }
    },
    resetFormData() {
      this.formData = {
        'sppl': null,
        'dpt': null,
      };
    },
    handleUpdateSppl(file, fileList) {
      this.resetFormData();
      this.formData.sppl = file.raw;
      this.handleUpload('sppl', fileList);
    },
    handleUpdateDpt(file, fileList) {
      this.resetFormData();
      this.formData.dpt = file.raw;
      this.handleUpload('dpt', fileList);
    },
    async handleUpload(type, fileList) {
      const formData = new FormData();
      if (type === 'sppl'){
        if (this.formData.sppl === null) {
          this.$message({
            message: 'Mohon unggah SPPL',
            type: 'error',
            duration: 5 * 1000,
          });
          return;
        }
        formData.append('sppl', this.formData.sppl);
      } else if (type === 'dpt'){
        if (this.formData.dpt === null) {
          this.$message({
            message: 'Mohon unggah DPT',
            type: 'error',
            duration: 5 * 1000,
          });
          return;
        }
        formData.append('dpt', this.formData.dpt);
      }
      formData.append('id_project', this.idProject);
      const headers = { 'Content-Type': 'multipart/form-data' };
      const { data } = await envManageDocsResource.list({
        id_project: this.idProject,
        type: type.toUpperCase(),
      });
      if (data.length > 0) {
        // update
        formData.append('id', data[0].id);
        formData.append('is_update', 1);
      } else {
        formData.append('is_update', 0);
      }
      _.each(this.formData, (value, key) => {
        formData.append(key, value);
      });
      await axios
        .post('api/env-manage-docs', formData, { headers })
        .then((response) => {
          this.$message({
            message: 'Dokumen ' + type.toUpperCase() + ' berhasil disimpan',
            type: 'success',
            duration: 5 * 1000,
          });
          if (type === 'dpt') {
            this.$emit('handleDokPendukungUploaded');
          }
          // rename
          if (fileList.length > 0) {
            fileList[0].name = response.data.data.filename;
          }
          // handle multiple uploads
          if (fileList.length > 1) {
            for (let i = 1; i < fileList.length; i++) {
              fileList.pop();
            }
          }
        })
        .catch(error => {
          console.log(error);
          this.$message({
            message: 'Gagal mengunggah dokumen ' + type.toUpperCase(),
            type: 'error',
            duration: 5 * 1000,
          });
          fileList.pop();
        });
    },
  },
};
</script>
