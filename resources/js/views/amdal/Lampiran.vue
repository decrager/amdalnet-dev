<template>
  <div style="padding: 13px 0">
    <el-row v-loading="loading" :gutter="32">
      <el-col :sm="24" :md="24">
        <div
          style="
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            margin-bottom: 10px;
          "
        >
          <label>
            Silahkan Lengkapi Formulir Kerangka Acuan dengan Lampiran Data
            Pendukung Deskripsi Kegiatan (Opsional)
          </label>
          <el-button type="primary" @click="handleAdd"> Tambah </el-button>
        </div>
        <el-table
          :data="attachment"
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
                  @click="download(scope.row.file)"
                >
                  Download
                </el-button>
              </span>
              <span v-else>
                <el-upload
                  action="#"
                  :auto-upload="false"
                  :on-change="handleUpload"
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
                @click="handleDelete(scope.row.id, scope.$index)"
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
const kaAttachmentResource = new Resource('ka-attachments');

export default {
  name: 'LampiranKa',
  data() {
    return {
      attachment: [],
      loading: false,
      loadingSubmit: false,
      deleted: [],
      selectedUpload: null,
    };
  },
  created() {
    this.getData();
  },
  methods: {
    async getData() {
      this.loading = true;
      await kaAttachmentResource
        .list({
          idProject: this.$route.params.id,
        })
        .then((res) => {
          this.attachment = res;
        })
        .finally(() => {
          this.loading = false;
        });
    },
    async handleSubmit() {
      this.loadingSubmit = true;
      const attachments = this.attachment.filter((x) => {
        return x.id === null && x.file !== null;
      });

      const names = attachments.map((x) => x.name);

      const formData = new FormData();
      formData.append('type', 'attachment');
      formData.append('idProject', this.$route.params.id);
      formData.append('ka_files', JSON.stringify(names));
      formData.append('deleted', JSON.stringify(this.deleted));

      for (let i = 0; i < attachments.length; i++) {
        let file = null;
        if (attachments[i].file) {
          file = await this.convertBase64(attachments[i].file);
        }
        formData.append(`file-${i}`, file);
      }

      await kaAttachmentResource.store(formData);

      this.$message({
        message: 'Data berhasil disimpan',
        type: 'success',
        duration: 5 * 1000,
      });
      this.getData();
      this.loadingSubmit = false;
    },
    handleAdd() {
      this.attachment.push({
        id: null,
        name: null,
        file: null,
        fileName: null,
      });
    },
    handleDelete(id, idx) {
      this.attachment.splice(idx, 1);

      if (id) {
        this.deleted.push(id);
      }
    },
    download(url) {
      window.open(url, '_blank').focus();
    },
    handleUpload(file, fileList) {
      if (file.raw.size > 1048576) {
        this.showFileAlert();
        return;
      }

      this.attachment[this.selectedUpload].file = file.raw;
      this.attachment[this.selectedUpload].fileName = file.name;
    },
    showFileAlert() {
      this.$alert('Ukuran file tidak boleh lebih dari 1 MB', '', {
        center: true,
      });
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
