<template>
  <div v-loading="loading">
    <el-form
      ref="postForm"
      :model="postForm"
      label-position="top"
      label-width="200px"
    >
      <el-row>
        <el-col :span="24">
          <el-form-item label="Nomor Persetujuan Lingkungan" prop="number">
            <el-input v-model="postForm.number" />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="24">
          <el-form-item label="Judul/Perihal Persetujuan Lingkungan" prop="title">
            <el-input v-model="postForm.title" />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="24">
          <el-form-item label="Tanggal Terbit" prop="date_published">
            <el-date-picker
              v-model="postForm.date_published"
              type="date"
              format="dd/MM/yyyy"
              value-format="yyyy-MM-dd"
              placeholder="dd/mm/yyyy"
            />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="24">
          <el-form-item label="Unggah Dokumen Persetujuan Lingkungan" prop="file">
            <el-upload
              action="#"
              :auto-upload="false"
              :on-change="handleUploadChange"
              :show-file-list="true"
              :file-list="fileList"
              style="display: inline;"
              :disabled="loadingUpload"
            >
              <el-button :loading="loadingUpload" type="warning" size="mini">Upload</el-button>
            </el-upload>
          </el-form-item>
        </el-col>
      </el-row>
    </el-form>
    <div slot="footer" class="dialog-footer">
      <el-button :loading="loadingSubmit" type="primary" @click="handleSubmit()"> Submit </el-button>
    </div>
    <el-row style="margin-top: 20px;">
      <el-col :span="24">
        <span style="margin-right: 10px;"><h4>Unduh SKKL Final Dari OSS</h4></span>
        <el-button
          type="warning"
          size="small"
          icon="el-icon-download"
          @click="handleDownload()"
        >
          Unduh
        </el-button>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import Resource from '@/api/resource';
// import axios from 'axios';
const skklFinalResource = new Resource('skkl-final');
const skklResource = new Resource('skkl');

export default {
  name: 'SkklFinal',
  data() {
    return {
      loading: false,
      loadingUpload: false,
      loadingSubmit: false,
      fileUrl: null,
      fileList: [],
      fileSkklFinal: null,
      userKey: null,
      idProject: this.$route.params.id,
      postForm: {
        number: null,
        title: null,
        date_published: null,
      },
    };
  },
  created() {
    this.getData();
  },
  methods: {
    async getData() {
      const data = await skklResource.list({
        idProject: this.idProject,
        skklOss: 'true',
      });
      if ('file_url' in data && 'user_key' in data) {
        this.fileUrl = data.file_url;
        this.userKey = data.user_key;
      }
      // get skkl final by id_project
      this.loading = true;
      const skklFinals = await skklFinalResource.list({
        id_project: this.idProject,
      });
      this.loading = false;
      if (skklFinals.length > 0) {
        const skklFinal = skklFinals[0];
        this.postForm = skklFinal;
        // get file list
        if (skklFinal !== null && skklFinal.file !== null && skklFinal.file !== '') {
          const spl = skklFinal.file.split('/');
          const idx = spl.length - 1;
          this.fileList.push({
            name: spl[idx],
            url: skklFinal.file,
            id: skklFinal.id,
          });
        }
      }
    },
    async handleDownload() {
      if (this.fileUrl !== null) {
        window.open(this.fileUrl, '_blank').focus();
      } else {
        this.$message({
          message: 'URL file tidak ada.',
          type: 'error',
          duration: 5 * 1000,
        });
      }
    },
    async handleUploadChange(file, fileList) {
      if (file.raw.size > 1048576) {
        this.showFileAlert();
        return;
      }
      this.loadingUpload = true;
      this.fileSkklFinal = file;
      this.loadingUpload = false;
    },
    showFileAlert() {
      this.$alert('Ukuran file tidak boleh lebih dari 1 MB', '', {
        center: true,
      });
    },
    async handleSubmit() {
      this.loadingSubmit = true;
      const formData = new FormData();
      formData.append('id_project', this.idProject);
      formData.append('number', this.postForm.number);
      formData.append('title', this.postForm.title);
      formData.append('date_published', this.postForm.date_published);
      formData.append('skkl_final', this.fileSkklFinal.raw);
      skklFinalResource
        .store(formData)
        .then((response) => {
          this.loadingSubmit = false;
          if (response.code === 200) {
            this.$message({
              message: 'SKKL Final Berhasil Disimpan',
              type: 'success',
              duration: 5 * 1000,
            });
          } else {
            this.$message({
              message: 'Gagal menyimpan SKKL Final',
              type: 'error',
              duration: 5 * 1000,
            });
          }
        })
        .catch((error) => {
          this.loadingSubmit = false;
          this.$message({
            message: 'Gagal menyimpan SKKL Final',
            type: 'error',
            duration: 5 * 1000,
          });
          console.log(error);
        });
    },
  },
};
</script>
