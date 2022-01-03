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
        <!-- <el-button
          type="text"
          href="#"
          icon="el-icon-view"
          @click="handleView(scope.row)"
        >
          View
        </el-button> -->
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
    <el-table-column label="Judul Materi">
      <template slot-scope="scope">
        <span>{{ scope.row.judul }}</span>
      </template>
    </el-table-column>
    <el-table-column label="Deskripsi">
      <template slot-scope="scope">
        <span>{{ scope.row.deskripsi }}</span>
      </template>
    </el-table-column>
    <el-table-column label="Tanggal Terbit">
      <template slot-scope="scope">
        <span>{{ scope.row.tanggal }}</span>
      </template>
    </el-table-column>
    <el-table-column label="Link">
      <template slot-scope="scope">
        <span>{{ scope.row.link }}</span>
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
      this.$emit('handleEditForm', row);
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
