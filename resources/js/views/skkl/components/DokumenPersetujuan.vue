<template>
  <el-table
    v-loading="loading"
    :data="list"
    fit
    highlight-current-row
    :header-cell-style="{ background: '#3AB06F', color: 'white' }"
  >
    <el-table-column label="Dokumen" header-align="center">
      <template slot-scope="scope">
        <a
          class="document_link"
          :href="
            scope.row.file
          "
          download
        >
          <i class="el-icon-download" /> {{ scope.row.name }}
        </a>
        <el-upload
          v-if="(scope.row.name === 'Persetujuan Lingkungan SKKL') && !scope.row.uploaded"
          action="#"
          :auto-upload="false"
          :on-change="handleUploadChange"
          :show-file-list="false"
          style="display: inline;"
          :disabled="loadingUpload"
        >
          <el-button :loading="loadingUpload" type="warning" size="mini">Upload</el-button>
        </el-upload>
      </template>
    </el-table-column>

    <el-table-column label="Tanggal Update" align="center" width="150px">
      <template slot-scope="scope">
        <span>{{ scope.row.updated_at }}</span>
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
import Resource from '@/api/resource';
const skklResource = new Resource('skkl');
import axios from 'axios';

export default {
  name: 'DokumenPersetujuan',
  data() {
    return {
      loading: false,
      loadingUpload: false,
      list: [],
      idProject: this.$route.params.id,
    };
  },
  created() {
    this.getDocuments();
  },
  methods: {
    checkDocument(name) {
      return !(name === 'Persetujuan Lingkungan SKKL' || name === 'Dokumen KA');
    },
    async getDocuments() {
      this.loading = true;
      const data = await skklResource.list({
        idProject: this.idProject,
        document: 'true',
      });
      this.list = data;
      this.loading = false;
    },
    async handleUploadChange(file, fileList) {
      if (file.raw.size > 1048576) {
        this.showFileAlert();
        return;
      }

      this.loadingUpload = true;
      const formData = new FormData();
      formData.append('idProject', this.idProject);
      formData.append('skkl', file.raw);
      this.loading = true;
      await skklResource.store(formData);
      this.getDocuments();
      this.$message({
        message: 'SKKL Berhasil Diupload',
        type: 'success',
        duration: 5 * 1000,
      });
      this.loadingUpload = false;
    },
    exportDocx() {
      const id = this.idProject;
      axios({
        url: `form-ka/${id}/docx`,
        method: 'GET',
        responseType: 'blob',
      }).then((response) => {
        const getHeaders = response.headers['content-disposition'].split('; ');
        const getFileName = getHeaders[1].split('=');
        const getName = getFileName[1].split('=');
        var fileURL = window.URL.createObjectURL(new Blob([response.data]));
        var fileLink = document.createElement('a');
        fileLink.href = fileURL;
        let newName = getName[0].slice(1);
        newName = newName.slice(0, -1);
        fileLink.setAttribute('download', `${newName}`);
        document.body.appendChild(fileLink);
        fileLink.click();
      });
    },
    showFileAlert() {
      this.$alert('Ukuran file tidak boleh lebih dari 1 MB', '', {
        center: true,
      });
    },
  },
};
</script>

<style scoped>
.document_link {
  color: blue;
}

.upload-demo {
  display: inline-block;
}
</style>
