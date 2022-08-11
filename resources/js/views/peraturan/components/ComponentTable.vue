<!-- eslint-disable vue/html-indent -->
<template>
  <el-table
    v-loading="loading"
    :data="list"
    fit
    highlight-current-row
    :header-cell-style="{ background: '#3AB06F', color: 'white', border: '0' }"
  >
    <el-table-column label="No." width="70px">
      <template slot-scope="scope">
        <span>{{ scope.$index + 1 }}</span>
      </template>
    </el-table-column>

    <el-table-column label="Nama Peraturan" prop="name" sortable />

    <el-table-column
      v-if="
        checkPermission(['manage materials and policies']) ||
        checkRole(['admin'])
      "
      label="Aksi"
      width="250px"
    >
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
import checkPermission from '@/utils/permission';
import checkRole from '@/utils/role';

export default {
  name: 'ComponentTable',
  props: {
    list: {
      type: Object,
      default: () => [],
    },
    loading: Boolean,
  },
  methods: {
    checkPermission,
    checkRole,
    handleEditForm(row) {
      const currentParam = row;
      console.log(currentParam.name);
      this.$router.push({
        name: 'editPeraturan',
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
