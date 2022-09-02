<template>
  <div class="app-container">
    <div style="text-align: right">
      <el-button size="small" :disabled="loading" @click="getData">
        <i
          :class="loading ? 'el-icon-loading' : 'el-icon-refresh'"
        />&nbsp;&nbsp;Refresh data
      </el-button>
    </div>
    <el-row :gutter="32">
      <el-col :md="8" :sm="24" align="center">
        <div class="text-white fw-bold">
          Surat Pernyataan Pengelolaan Lingkungan Hidup
        </div>
        <el-upload
          type="primary"
          class="upload-demo"
          :auto-upload="false"
          :on-change="handleUpdateSppl"
          :before-remove="handleRemoveSppl"
          action="#"
          :show-file-list="true"
          :file-list="fileListSppl"
        >
          <el-button size="small" type="primary"> Upload </el-button>
        </el-upload>
      </el-col>
      <el-col :md="8" :sm="24" align="center">
        <div class="text-white fw-bold">Dokumen Persetujuan Teknis</div>
        <el-upload
          type="primary"
          class="upload-demo"
          :auto-upload="false"
          :on-change="handleUpdateDpt"
          :on-remove="handleRemoveDpt"
          action="#"
          :show-file-list="true"
          :file-list="fileListDpt"
        >
          <el-button size="small" type="primary"> Upload </el-button>
        </el-upload>
      </el-col>
      <el-col :md="8" :sm="24" align="center">
        <div class="text-white fw-bold">Dokumen Kesesuaian Tata Ruang</div>
        <el-upload
          type="primary"
          class="upload-demo"
          :auto-upload="false"
          :on-change="handleUpdateKtr"
          :on-remove="handleRemoveKtr"
          action="#"
          :show-file-list="true"
          :file-list="fileListKtr"
        >
          <el-button size="small" type="primary"> Upload </el-button>
        </el-upload>
      </el-col>
      <el-col :sm="24" :md="24">
        <div
          style="
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            margin-bottom: 10px;
          "
        >
          <label>Lampiran lainnya</label>
          <el-button type="primary" @click="handleAddOthers">
            Tambah
          </el-button>
        </div>
        <el-table
          v-loading="loadingSubmitOthers"
          :data="fileListOthers"
          fit
          highlight-current-row
          :header-cell-style="{ background: '#3AB06F', color: 'white' }"
        >
          <el-table-column align="center" label="No" width="50px">
            <template slot-scope="scope">
              <span>{{ scope.$index + 1 }}</span>
            </template>
          </el-table-column>

          <el-table-column align="center" label="Nama">
            <template slot-scope="scope">
              <span v-if="scope.row.id">{{ scope.row.name }}</span>
              <el-input v-else v-model="scope.row.name" />
            </template>
          </el-table-column>

          <el-table-column align="center" label="File">
            <template slot-scope="scope">
              <span v-if="scope.row.id">
                <el-button
                  type="text"
                  icon="el-icon-download"
                  @click="download(scope.row.filepath)"
                >
                  Download
                </el-button>
              </span>
              <span v-else>
                <el-upload
                  action="#"
                  :auto-upload="false"
                  :on-change="handleUploadOthers"
                  :show-file-list="false"
                >
                  <el-button
                    size="small"
                    type="warning"
                    @click="selectedUpload = scope.$index"
                  >
                    Upload
                  </el-button>
                  <div slot="tip" class="el-upload__tip">
                    {{ scope.row.fileName }}
                  </div>
                </el-upload>
              </span>
            </template>
          </el-table-column>

          <el-table-column align="center">
            <template slot-scope="scope">
              <el-button
                type="text"
                href="#"
                icon="el-icon-delete"
                @click="handleDeleteOthers(scope.row.id, scope.$index)"
              >
                Hapus
              </el-button>
            </template>
          </el-table-column>
        </el-table>
      </el-col>
      <el-col :md="24" align="right">
        <el-button
          :loading="loadingSubmitOthers"
          type="primary"
          style="margin-top: 10px"
          @click="handleSubmitOthers"
        >
          Simpan Lampiran Lainnya
        </el-button>
      </el-col>
    </el-row>
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
      fileListKtr: [],
      fileListOthers: [],
      deletedOthers: [],
      formData: {
        sppl: null,
        dpt: null,
      },
      idProject: 0,
      loading: false,
      loadingSubmitOthers: false,
      selectedUpload: 0,
    };
  },
  mounted() {
    this.getData();
  },
  methods: {
    async getData() {
      this.loading = true;
      this.fileListSppl = [];
      this.fileListDpt = [];
      this.fileListKtr = [];
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
          const sppl = data.filter((d) => d.type === 'SPPL');
          const dpt = data.filter((d) => d.type === 'DPT');
          const ktr = data.filter((d) => d.type === 'KTR');
          this.fileListOthers = data.filter((d) => d.type === 'OTHERS');
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
          if (ktr.length > 0) {
            this.fileListKtr.push({
              name: ktr[0].filename,
              url: ktr[0].filepath,
              id: ktr[0].id,
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
          {
            id: 0,
            id_project: this.idProject,
            type: 'KTR',
            filepath: '',
            filename: '',
          },
        ];
      }
      this.loading = false;
    },
    resetFormData() {
      this.formData = {
        sppl: null,
        dpt: null,
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
    handleUpdateKtr(file, fileList) {
      this.resetFormData();
      this.formData.ktr = file.raw;
      this.handleUpload('ktr', fileList);
    },
    handleRemoveSppl(file, fileList) {
      console.log(file, fileList);
      this.$confirm('Hapus dokumen SPPL?', 'Menghapus Dokumen SPPL', {
        distinguishCancelAndClose: true,
        confirmButtonText: 'Iya!',
        cancelButtonText: 'Batalkan!',
      })
        .then(() => {
          this.$message({
            type: 'info',
            message: 'Changes saved. Proceeding to a new route.',
          });
        })
        .catch((action) => {
          this.$message({
            type: 'info',
            message:
              action === 'cancel'
                ? 'Changes discarded. Proceeding to a new route.'
                : 'Stay in the current route',
          });
        });
    },
    handleRemoveDpt(file, fileList) {
      console.log(file, fileList);
      return false;
    },
    handleRemoveKtr(file, fileList) {
      console.log(file, fileList);
      return false;
    },
    async handleUpload(type, fileList) {
      const formData = new FormData();
      if (type === 'sppl') {
        if (this.formData.sppl === null) {
          this.$message({
            message: 'Mohon unggah SPPL',
            type: 'error',
            duration: 5 * 1000,
          });
          return;
        }
        formData.append('sppl', this.formData.sppl);
      } else if (type === 'dpt') {
        if (this.formData.dpt === null) {
          this.$message({
            message: 'Mohon unggah DPT',
            type: 'error',
            duration: 5 * 1000,
          });
          return;
        }
        formData.append('dpt', this.formData.dpt);
      } else if (type === 'ktr') {
        if (this.formData.dpt === null) {
          this.$message({
            message: 'Mohon unggah KTR',
            type: 'error',
            duration: 5 * 1000,
          });
          return;
        }
        formData.append('ktr', this.formData.dpt);
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
        .catch((error) => {
          console.log(error);
          this.$message({
            message: 'Gagal mengunggah dokumen ' + type.toUpperCase(),
            type: 'error',
            duration: 5 * 1000,
          });
          fileList.pop();
        });
    },
    async handleSubmitOthers() {
      this.loadingSubmitOthers = true;
      const others = this.fileListOthers.filter((x) => {
        return x.id === null && x.filepath !== null;
      });

      const names = this.fileListOthers.map((x) => x.name);

      const formData = new FormData();
      formData.append('otherAttachments', 'true');
      formData.append('idProject', this.$route.params.id);
      formData.append('others', JSON.stringify(names));
      formData.append('deleted', JSON.stringify(this.deletedOthers));

      for (let i = 0; i < others.length; i++) {
        let otherFile = null;
        if (others[i].filepath) {
          otherFile = await this.convertBase64(others[i].filepath);
        }
        formData.append(`file-${i}`, otherFile);
      }

      this.fileListOthers = await envManageDocsResource.store(formData);

      this.$message({
        message: 'Data berhasil disimpan',
        type: 'success',
        duration: 5 * 1000,
      });

      this.loadingSubmitOthers = false;
    },
    handleAddOthers() {
      this.fileListOthers.push({
        id: null,
        id_project: this.idProject,
        name: null,
        type: 'OTHERS',
        filepath: null,
        fileName: null,
      });
    },
    handleUploadOthers(file, fileList) {
      this.fileListOthers[this.selectedUpload].filepath = file.raw;
      this.fileListOthers[this.selectedUpload].fileName = file.name;
    },
    handleDeleteOthers(id, idx) {
      this.fileListOthers.splice(idx, 1);

      if (id) {
        this.deletedOthers.push(id);
      }
    },
    download(url) {
      window.open(url, '_blank').focus();
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
