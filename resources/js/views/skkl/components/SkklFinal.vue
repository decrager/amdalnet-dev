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
            <el-input v-model="postForm.number" :disabled="disableEdit" />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="24">
          <el-form-item label="Judul/Perihal Persetujuan Lingkungan" prop="title">
            <el-input v-model="postForm.title" :disabled="disableEdit" />
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
              :disabled="disableEdit"
            />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="24">
          <el-form-item label="Dokumen Persetujuan Lingkungan" prop="file">
            <el-upload
              v-if="!disableEdit"
              action="#"
              :auto-upload="false"
              :on-change="handleUploadChange"
              :show-file-list="true"
              style="display: inline;"
            >
              <el-button :loading="loadingUpload" type="warning" size="mini">Upload</el-button>
            </el-upload>
            <div v-if="disableEdit" style="color: red;">
              <i>*Hanya penguji yang dapat mengunggah dokumen</i>
            </div>
            <el-table
              :data="fileList"
              fit
              highlight-current-row
            >
              <el-table-column align="center" label="Aksi">
                <template slot-scope="scope">
                  <el-button
                    type="text"
                    icon="el-icon-download"
                    @click="download(scope.row.url)"
                  >
                    Download
                  </el-button>
                </template>
              </el-table-column>
              <el-table-column align="left" label="Judul Dokumen">
                <template slot-scope="scope">
                  <span>{{ scope.row.name }}</span>
                </template>
              </el-table-column>
            </el-table>
          </el-form-item>
        </el-col>
      </el-row>
    </el-form>
    <div v-if="!disableEdit" slot="footer" class="dialog-footer">
      <el-button :loading="loadingSubmit" type="primary" @click="handleSubmit()"> Submit </el-button>
    </div>
    <!-- <el-row v-if="!isPemerintah" style="margin-top: 20px;">
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
    </el-row> -->
  </div>
</template>

<script>
import Resource from '@/api/resource';
// import axios from 'axios';
import { mapGetters } from 'vuex';
const skklFinalResource = new Resource('skkl-final');
// const skklResource = new Resource('skkl');
const initiatorResource = new Resource('initiators');

export default {
  name: 'SkklFinal',
  data() {
    return {
      loading: false,
      loadingUpload: false,
      loadingSubmit: false,
      disableEdit: true,
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
      isPemerintah: false,
      isExaminer: false,
      isAdmin: false,
    };
  },
  computed: {
    ...mapGetters({
      'userInfo': 'user',
      'userId': 'userId',
    }),
  },
  async created() {
    if (this.$store.getters.roles[0].split('-')[0] === 'examiner'){
      this.isExaminer = true;
    }
    if (this.$store.getters.roles[0].split('-')[0] === 'admin'){
      this.isAdmin = true;
    }
    if (this.isExaminer || this.isAdmin) {
      this.disableEdit = false;
    }
    const initiators = await initiatorResource.list({
      email: this.userInfo.email,
    });
    if (initiators.data.length === 0) {
      this.isPemerintah = true;
    }
    this.getData();
    // await this.$store.dispatch('getInitiator', this.userInfo.email);
    // if (this.$store.getters.isPemerintah){
    //   this.isPemerintah = true;
    // }
  },
  methods: {
    async getData() {
      // const data = await skklResource.list({
      //   idProject: this.idProject,
      //   skklOss: 'true',
      // });
      // if ('file_url' in data && 'user_key' in data) {
      //   this.fileUrl = data.file_url;
      //   this.userKey = data.user_key;
      // }
      // get skkl final by id_project
      this.loading = true;
      const skklFinals = await skklFinalResource.list({
        id_project: this.idProject,
      });
      console.log(skklFinals);
      this.loading = false;
      this.disableEdit = true;
      if (this.isExaminer || this.isAdmin) {
        this.disableEdit = false;
      }

      if (skklFinals.length > 0) {
        const skklFinal = skklFinals[0];
        this.postForm = skklFinal;
        // get file list
        if (skklFinal !== null && skklFinal.file !== null && skklFinal.file !== '') {
          this.fileList.push({
            name: skklFinal.title,
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
    download(url) {
      window.open(url, '_blank').focus();
    },
    async handleUploadChange(file, fileList) {
      if (file.raw.size > 10485760) {
        this.showFileAlert();
        return;
      }
      this.loadingUpload = true;
      this.fileSkklFinal = file;
      this.loadingUpload = false;
      this.fileList.push({
        name: file.name,
        url: '',
        id: 1,
      });
    },
    showFileAlert() {
      this.$alert('Ukuran file tidak boleh lebih dari 10 MB', '', {
        center: true,
      });
    },
    async handleSubmit() {
      this.loadingSubmit = true;
      const formData = new FormData();
      // if (this.$route.query.isOSS){
      //   formData.append('isOSS', 'true');
      // }
      // formData.append('isOSS', 'true');
      formData.append('id_project', this.idProject);
      formData.append('idProject', this.idProject);
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
