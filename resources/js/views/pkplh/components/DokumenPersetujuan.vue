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
        <a class="document_link" :href="scope.row.file" download>
          <i class="el-icon-download" /> {{ scope.row.name }}
        </a>
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
        uklUpl: 'true',
      });
      this.list = data;
      this.loading = false;
    },
    async handleUploadChange(file, fileList) {
      const formData = new FormData();
      formData.append('idProject', this.idProject);
      formData.append('pkplh', file.raw);
      formData.append('uklUpl', 'true');
      this.loading = true;
      await skklResource.store(formData);
      this.getDocuments();
      this.$message({
        message: 'Data is saved Successfully',
        type: 'success',
        duration: 5 * 1000,
        uklUpl: 'true',
      });
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
