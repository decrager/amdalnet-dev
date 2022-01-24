<template>
  <el-table
    v-loading="loading"
    :data="list"
    fit
    highlight-current-row
    :header-cell-style="{ background: '#3AB06F', color: 'white', border:'0' }"
  >
    <el-table-column type="expand">
      <template slot-scope="scope">
        <div>Penerbit SK : Menteri</div>
        <div>File : -</div>
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
    <el-table-column label="Nama Pemrakarsa Usaha/Kegiatan">
      <template slot-scope="scope">
        <span>{{ scope.row.nama }}</span>
      </template>
    </el-table-column>
    <el-table-column label="Nama Usaha/Kegiatan (SKKL/Izin Lingkungan)">
      <template slot-scope="scope">
        <span>{{ scope.row.usaha }}</span>
      </template>
    </el-table-column>
    <el-table-column label="Nomor SK">
      <template slot-scope="scope">
        <span>{{ scope.row.nomor }}</span>
      </template>
    </el-table-column>
    <el-table-column label="Tanggal Berlaku SK">
      <template slot-scope="scope">
        <span>{{ scope.row.tanggal }}</span>
      </template>
    </el-table-column>
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
    handleView(row) {
      this.$emit('handleView', row);
    },
    handleEditForm(row) {
      const currentParam = row;
      this.$router.push({
        name: 'editMateri',
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
  .el-table-column{border: none;}
</style>
