<template>
  <el-table
    v-loading="loading"
    :data="list"
    fit
    highlight-current-row
    :header-cell-style="{ background: '#3AB06F', color: 'white', border: '0' }"
  >
    <el-table-column type="expand">
      <template slot-scope="scope">
        <div>Penerbit SK : {{ scope.row.publisher }}</div>
        <div>File : {{ scope.row.file }}</div>
        <el-button
          type="text"
          href="#"
          icon="el-icon-edit"
          @click="handleEditForm(scope.row)"
        >
          Ubah
        </el-button>
        <el-button
          type="text"
          href="#"
          icon="el-icon-delete"
          @click="handleDelete(scope.row)"
        >
          Hapus
        </el-button>
      </template>
    </el-table-column>
    <el-table-column label="No." width="54px">
      <template slot-scope="scope">
        <span>{{ scope.$index + 1 }}</span>
      </template>
    </el-table-column>
    <el-table-column
      label="Nama Pemrakarsa Usaha/Kegiatan"
      prop="pemarkasa_name"
      sortable
    />
    <el-table-column
      label="Nama Usaha/Kegiatan (SKKL/Izin Lingkungan)"
      prop="kegiatan_name"
      sortable
    />
    <el-table-column label="Nomor SK" prop="sk_number" sortable />
    <el-table-column label="Tanggal Berlaku SK" prop="date" sortable />
  </el-table>
</template>

<script>
export default {
  name: 'ComponentTable',
  props: {
    list: {
      type: Array,
      default: () => [],
    },
    loading: Boolean,
  },
  methods: {
    handleEditForm(row) {
      const currentParam = row;
      this.$router.push({
        name: 'EditIzin',
        params: { row, appParams: currentParam },
      });
    },
    handleDelete(rows) {
      this.$emit('handleDelete', { rows });
    },
  },
};
</script>
<style rel="stylesheet/scss" lang="scss" scoped>
.el-table-column {
  border: none;
}
</style>
