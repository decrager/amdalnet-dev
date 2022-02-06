<template>
  <el-table
    v-loading="loading"
    :data="list"
    fit
    highlight-current-row
    :header-cell-style="{ background: '#3AB06F', color: 'white', border:'0' }"
  >
    <el-table-column label="No." width="54px">
      <template slot-scope="scope">
        <span>{{ scope.$index + 1 }}</span>
      </template>
    </el-table-column>
    <el-table-column label="Jenis SOP">
      <template slot-scope="scope">
        <span>{{ scope.row.template_type }}</span>
      </template>
    </el-table-column>
    <el-table-column label="Download/File">
      <template slot-scope="scope">
        <span>{{ scope.row.file }}</span>
      </template>
    </el-table-column>
    <el-table-column label="Aksi">
      <template slot-scope="scope">
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
        name: 'EditSopPengelolaan',
        params: { row, appParams: currentParam },
      });
    },
    handleDelete(rows) {
      this.$emit('handleDelete', { rows });
    },
    formatDateStr(date) {
      const today = new Date(date);
      var bulan = [
        'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember',
      ];
      const year = today.getFullYear();
      const month = today.getMonth();
      const day = today.getDate();
      const monthID = bulan[month];
      const finalDate = `${day} ${monthID} ${year}`;
      return finalDate;
    },
  },
};
</script>
<style rel="stylesheet/scss" lang="scss" scoped>
  .el-table-column{border: none;}
</style>
