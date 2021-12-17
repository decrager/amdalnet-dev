<template>
  <el-dialog :title="'Map Service Kategori'" :visible.sync="showCategory" :close-on-click-modal="false" :show-close="true">
    <el-table
      v-loading="loading"
      :data="category"
      fit
      highlight-current-row
      :header-cell-style="{ background: '#3AB06F', color: 'white', border:'0' }"
    >
      <el-table-column label="No." width="54px">
        <template slot-scope="scope">
          <span>{{ scope.$index + 1 }}</span>
        </template>
      </el-table-column>

      <el-table-column label="Nama Kategori">
        <template slot-scope="scope">
          <span>{{ scope.row.category_name }}</span>
        </template>
      </el-table-column>

      <el-table-column label="Status">
        <template slot-scope="scope">
          <el-tag :type="scope.row.active === 1 ? 'success' : 'danger'">{{ scope.row.active === 1 ? 'Active' : 'Tidak Aktif' }}</el-tag>
        </template>
      </el-table-column>

      <el-table-column label="Aksi">
        <template slot-scope="scope">
          <el-button
            type="text"
            href="#"
            icon="el-icon-edit"
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

  </el-dialog>
</template>

<script>
export default {
  props: {
    showCategory: Boolean,
    category: {
      type: Object,
      default: () => [],
    },
  },
  methods: {
    handleSubmitComponent() {
      this.$emit('handleSubmitComponent');
    },
    handleCancelComponent() {
      this.$emit('handleCancelComponent');
    },
  },
};
</script>
