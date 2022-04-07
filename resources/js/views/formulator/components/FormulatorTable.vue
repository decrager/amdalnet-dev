<template>
  <el-table
    v-loading="loading"
    :data="list"
    fit
    highlight-current-row
    :header-cell-style="{ background: '#3AB06F', color: 'white' }"
  >
    <el-table-column align="center" label="Nama" prop="name" sortable />
    <el-table-column
      align="center"
      label="No. Registrasi"
      prop="reg_no"
      sortable
    />
    <el-table-column
      align="center"
      label="No. Sertifikat"
      prop="cert_no"
      sortable
    />
    <el-table-column
      align="center"
      label="Sertifikasi"
      prop="membership_status"
      sortable
    />

    <el-table-column align="center" label="Status" prop="status" sortable>
      <template slot-scope="scope">
        <el-tag :type="scope.row.status | statusFilter">
          {{ scope.row.status }}
        </el-tag>
      </template>
    </el-table-column>

    <el-table-column label="File">
      <template slot-scope="scope">
        <el-button
          type="text"
          size="medium"
          icon="el-icon-download"
          style="color: blue"
          @click.prevent="download(scope.row.cert_file)"
        >
          Sertifikat
        </el-button>
        <el-button
          type="text"
          size="medium"
          icon="el-icon-download"
          style="color: blue"
          @click.prevent="download(scope.row.cv_file)"
        >
          CV
        </el-button>
        <el-button
          v-if="certificate"
          type="warning"
          size="mini"
          @click="handleCertificate(scope.row.id)"
        >
          Update
        </el-button>
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
export default {
  name: 'FormulatorTable',
  filters: {
    statusFilter(status) {
      const statusMap = {
        Aktif: 'success',
        NonAktif: 'danger',
      };
      return statusMap[status];
    },
  },
  props: {
    list: {
      type: Array,
      default: () => [],
    },
    loading: Boolean,
    certificate: Boolean,
  },
  methods: {
    download(url) {
      window.open(url, '_blank').focus();
    },
    handleEditForm(id) {
      this.$emit('handleEditForm', id);
    },
    handleDelete(id, nama) {
      this.$emit('handleDelete', { id, nama });
    },
    handleCertificate(id) {
      // eslint-disable-next-line object-curly-spacing
      this.$router.push({ name: 'certificateFormulator', params: { id } });
    },
  },
};
</script>

<style scoped>
.expand-container {
  display: flex;
  justify-content: space-around;
}
.expand-container div {
  width: 50%;
  padding: 1rem 3rem;
}
.expand-container__right {
  text-align: right;
}
</style>
