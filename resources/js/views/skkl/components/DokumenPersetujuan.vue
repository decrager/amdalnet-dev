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
            scope.row.file ? scope.row.file : '/document/Template-SKKL.docx'
          "
          download
        >
          <i class="el-icon-download" /> {{ scope.row.name }}
        </a>
        &nbsp;
        <el-upload
          v-if="scope.row.name === 'Persetujuan Lingkungan SKKL'"
          class="upload-demo"
          :auto-upload="false"
          :on-change="handleUploadChange"
          action="#"
          :show-file-list="false"
        >
          <el-button type="info" size="mini">Update</el-button>
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
      const formData = new FormData();
      formData.append('idProject', this.idProject);
      formData.append('skkl', file.raw);
      this.loading = true;
      await skklResource.store(formData);
      this.getDocuments();
      this.$message({
        message: 'Data is saved Successfully',
        type: 'success',
        duration: 5 * 1000,
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
