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

    <el-table-column label="Nama Parameter">
      <template slot-scope="scope">
        <span>{{ scope.row.parameter_name }}</span>
      </template>
    </el-table-column>

    <el-table-column label="Title">
      <template slot-scope="scope">
        <span>{{ scope.row.title }}</span>
      </template>
    </el-table-column>

    <el-table-column label="Value">
      <template slot-scope="scope">
        <span>{{ scope.row.value }}</span>
      </template>
    </el-table-column>

    <el-table-column label="Aksi" width="250px">
      <template slot-scope="scope">
        <el-button
          type="text"
          href="#"
          icon="el-icon-view"
          @click="handleEditForm(scope.row.id)"
        >
          View
        </el-button>
        <el-button
          type="text"
          href="#"
          icon="el-icon-view"
          @click="handleEditForm(scope.row.id)"
        >
          Ubah
        </el-button>
        <el-button
          type="text"
          href="#"
          icon="el-icon-delete"
          @click="handleDelete(scope.row.id, scope.row.name)"
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
    handleEditForm(id) {
      this.$emit('handleEditForm', id);
    },
    handleDelete(id, nama) {
      this.$emit('handleDelete', { id, nama });
    },
  },
};
</script>
<style rel="stylesheet/scss" lang="scss" scoped>
  .el-table-column{border: none;}
</style>
