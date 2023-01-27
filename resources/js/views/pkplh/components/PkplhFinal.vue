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
            <el-input v-model="postForm.number" :disabled="disableEdit || pkplhFinalNumber != null " />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="24">
          <el-form-item label="Judul/Perihal Persetujuan Lingkungan" prop="title">
            <el-input v-model="postForm.title" :disabled="disableEdit || pkplhFinalTitle != null " />
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
              :disabled="disableEdit || pkplhFinalDate != null "
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
              :file-list="fileList"
              style="display: inline;"
            >
              <el-button :loading="loadingUpload" :disabled="pkplhFinalFile != null" type="warning" size="mini">Upload</el-button>
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
      <el-button :disabled="isPkplhFinals.length > 0" type="primary" @click="handleSubmit()"> Submit </el-button>
    </div>
    <!-- <el-row v-if="!isPemerintah" style="margin-top: 20px;">
      <el-col :span="24">
        <span style="margin-right: 10px;"><h4>Unduh PKPLH Final Dari OSS</h4></span>
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
const pkplhFinalResource = new Resource('pkplh-final');
// const skklResource = new Resource('skkl');
const initiatorResource = new Resource('initiators');

export default {
  name: 'PkplhFinal',
  data() {
    return {
      loading: false,
      loadingUpload: false,
      loadingSubmit: false,
      disableEdit: true,
      fileUrl: null,
      fileList: [],
      filePkplhFinal: null,
      pkplhFinalNumber: null,
      pkplhFinalFile: null,
      pkplhFinalTitle: null,
      pkplhFinalDate: null,
      isPkplhFinals: null,
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
  mounted() {
    this.getData();
    this.isPkplh();
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
      //   type: 'pkplh',
      // });
      // if ('file_url' in data && 'user_key' in data) {
      //   this.fileUrl = data.file_url;
      //   this.userKey = data.user_key;
      // }
      // get skkl final by id_project
      this.loading = true;
      const pkplhFinals = await pkplhFinalResource.list({
        id_project: this.idProject,
      });
      if (pkplhFinals.length > 0) {
        const pkplhFinal = pkplhFinals[0];
        this.postForm = pkplhFinal;
        // get file list
        if (pkplhFinal !== null && pkplhFinal.file !== null && pkplhFinal.file !== '') {
          this.fileList.push({
            name: pkplhFinal.title,
            url: pkplhFinal.file,
            id: pkplhFinal.id,
          });
        }
      }
      this.loading = false;
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
      if (file.raw.size > 1048576) {
        this.showFileAlert();
        return;
      }
      this.loadingUpload = true;
      this.filePkplhFinal = file;
      this.loadingUpload = false;

      this.fileList.push({
        name: file.name,
        url: '',
        id: 1,
      });
    },
    showFileAlert() {
      this.$alert('Ukuran file tidak boleh lebih dari 1 MB', '', {
        center: true,
      });
    },
    async handleSubmit() {
      this.loadingSubmit = true;
      this.loading = true;
      const formData = new FormData();
      formData.append('id_project', this.idProject);
      formData.append('number', this.postForm.number);
      formData.append('title', this.postForm.title);
      formData.append('date_published', this.postForm.date_published);
      formData.append('pkplh_final', this.filePkplhFinal.raw);
      pkplhFinalResource
        .store(formData)
        .then((response) => {
          this.isPkplh();
          if (response.code === 200) {
            this.$message({
              message: 'PKPLH Final Berhasil Disimpan',
              type: 'success',
              duration: 5 * 1000,
            });
          } else {
            this.$message({
              message: 'Gagal menyimpan PKPLH Final',
              type: 'error',
              duration: 5 * 1000,
            });
          }
        })
        .catch((error) => {
          this.loadingSubmit = false;
          this.loading = false;
          this.$message({
            message: 'Gagal menyimpan PKPLH Final',
            type: 'error',
            duration: 5 * 1000,
          });
          console.log(error);
        });
    },
    async isPkplh() {
      const pkplhFinals = await pkplhFinalResource.list({
        id_project: this.idProject,
      });
      this.isPkplhFinals = pkplhFinals;
      this.pkplhFinalNumber = pkplhFinals[0].number;
      this.pkplhFinalFile = pkplhFinals[0].file;
      this.pkplhFinalTitle = pkplhFinals[0].title;
      this.pkplhFinalDate = pkplhFinals[0].date_published;
      this.loading = false;
    },
  },
};
</script>
