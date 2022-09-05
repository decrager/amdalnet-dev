<template>
  <div class="app-container">
    <div style="text-align: right">
      <el-button size="small" :disabled="loading" @click="getData">
        <i
          :class="loading ? 'el-icon-loading' : 'el-icon-refresh'"
        />&nbsp;&nbsp;Refresh data
      </el-button>
    </div>
    <el-row v-loading="loading" :gutter="32">
      <el-col :md="8" :sm="24" align="center">
        <div class="text-white fw-bold" style="margin-bottom: 4px">
          Surat Pernyataan Pengelolaan Lingkungan Hidup
        </div>
        <a
          v-if="attachment.sppl.id"
          style="display: block; margin-bottom: 6px"
          :href="attachment.sppl.filepath"
          download
        >
          <i class="el-icon-download" />
          Download
        </a>
        <el-upload
          type="primary"
          class="upload-demo"
          :auto-upload="false"
          :on-change="handleUpdateSppl"
          action="#"
          :show-file-list="false"
        >
          <el-button size="small" type="primary"> Upload </el-button>
        </el-upload>
        <div class="el-upload__tip">
          {{ sppl.name }}
        </div>
      </el-col>
      <el-col :md="8" :sm="24" align="center">
        <div class="text-white fw-bold" style="margin-bottom: 4px">
          Dokumen Persetujuan Teknis
        </div>
        <a
          v-if="attachment.dpt.id"
          style="display: block; margin-bottom: 6px"
          :href="attachment.dpt.filepath"
          download
        >
          <i class="el-icon-download" />
          Download
        </a>
        <el-upload
          type="primary"
          class="upload-demo"
          :auto-upload="false"
          :on-change="handleUpdateDpt"
          action="#"
          :show-file-list="false"
        >
          <el-button size="small" type="primary"> Upload </el-button>
        </el-upload>
        <div class="el-upload__tip">
          {{ dpt.name }}
        </div>
      </el-col>
      <el-col :md="8" :sm="24" align="center">
        <div class="text-white fw-bold" style="margin-bottom: 4px">
          Dokumen Kesesuaian Tata Ruang
        </div>
        <a
          v-if="attachment.ktr.id"
          style="display: block; margin-bottom: 6px"
          :href="attachment.ktr.filepath"
          download
        >
          <i class="el-icon-download" />
          Download
        </a>
        <el-upload
          type="primary"
          class="upload-demo"
          :auto-upload="false"
          :on-change="handleUpdateKtr"
          action="#"
          :show-file-list="false"
        >
          <el-button size="small" type="primary"> Upload </el-button>
        </el-upload>
        <div class="el-upload__tip">
          {{ ktr.name }}
        </div>
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
          :data="attachment.others"
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
          :loading="loadingSubmit"
          type="primary"
          style="margin-top: 10px"
          @click="handleSubmit"
        >
          Simpan
        </el-button>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import Resource from '@/api/resource';
const envManageDocsResource = new Resource('env-manage-docs');

export default {
  name: 'DokumenPendukung',
  data() {
    return {
      attachment: {
        sppl: {},
        dpt: {},
        ktr: {},
        others: [],
      },
      sppl: {
        name: null,
        file: null,
      },
      dpt: {
        name: null,
        file: null,
      },
      ktr: {
        name: null,
        file: null,
      },
      deletedOthers: [],
      idProject: 0,
      loading: false,
      loadingSubmit: false,
      selectedUpload: 0,
    };
  },
  mounted() {
    this.getData();
  },
  methods: {
    async getData() {
      this.loading = true;
      this.idProject = parseInt(this.$route.params && this.$route.params.id);
      const { data } = await envManageDocsResource.list({
        id_project: this.idProject,
      });
      this.attachment.sppl = data.find((d) => d.type === 'SPPL')
        ? data.find((d) => d.type === 'SPPL')
        : {};
      this.attachment.dpt = data.find((d) => d.type === 'DPT')
        ? data.find((d) => d.type === 'DPT')
        : {};
      this.attachment.ktr = data.find((d) => d.type === 'KTR')
        ? data.find((d) => d.type === 'KTR')
        : {};
      this.attachment.others = data.filter((d) => d.type === 'OTHERS');

      this.loading = false;
    },
    resetFormData() {
      this.sppl = {
        name: null,
        file: null,
      };
      this.dpt = {
        name: null,
        file: null,
      };
      this.ktr = {
        name: null,
        file: null,
      };
      this.deletedOthers = [];
    },
    handleUpdateSppl(file, fileList) {
      if (!this.checkPDF(file)) {
        return;
      }
      console.log(file.name);
      this.sppl.file = file.raw;
      this.sppl.name = file.name;
    },
    handleUpdateDpt(file, fileList) {
      if (!this.checkPDF(file)) {
        return;
      }
      this.dpt.file = file.raw;
      this.dpt.name = file.name;
    },
    handleUpdateKtr(file, fileList) {
      if (!this.checkPDF(file)) {
        return;
      }
      this.ktr.file = file.raw;
      this.ktr.name = file.name;
    },
    async handleSubmit() {
      this.loadingSubmit = true;
      const others = this.attachment.others.filter((x) => {
        return x.id === null && x.filepath !== null;
      });

      const names = this.attachment.others.map((x) => x.name);

      const formData = new FormData();
      formData.append('idProject', this.$route.params.id);
      formData.append('others', JSON.stringify(names));
      formData.append('deleted', JSON.stringify(this.deletedOthers));

      if (this.sppl.file) {
        formData.append('sppl', await this.convertBase64(this.sppl.file));
      }

      if (this.dpt.file) {
        formData.append('dpt', await this.convertBase64(this.dpt.file));
      }

      if (this.ktr.file) {
        formData.append('ktr', await this.convertBase64(this.ktr.file));
      }

      for (let i = 0; i < others.length; i++) {
        let otherFile = null;
        if (others[i].filepath) {
          otherFile = await this.convertBase64(others[i].filepath);
        }
        formData.append(`file-${i}`, otherFile);
      }

      await envManageDocsResource.store(formData);

      this.$message({
        message: 'Data berhasil disimpan',
        type: 'success',
        duration: 5 * 1000,
      });

      this.getData();
      this.resetFormData();
      this.loadingSubmit = false;
    },
    handleAddOthers() {
      this.attachment.others.push({
        id: null,
        id_project: this.idProject,
        name: null,
        type: 'OTHERS',
        filepath: null,
        fileName: null,
      });
    },
    handleUploadOthers(file, fileList) {
      this.attachment.others[this.selectedUpload].filepath = file.raw;
      this.attachment.others[this.selectedUpload].fileName = file.name;
    },
    handleDeleteOthers(id, idx) {
      this.attachment.others.splice(idx, 1);

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
    checkPDF(file) {
      const extension = file.name.split('.');
      if (extension[extension.length - 1].toLowerCase() !== 'pdf') {
        this.$alert('File harus berformat PDF', '', {
          center: true,
        });
        return false;
      }

      return true;
    },
  },
};
</script>
