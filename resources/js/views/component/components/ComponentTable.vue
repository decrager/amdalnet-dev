<template>
  <el-table
    v-loading="loading"
    :data="list"
    border
    fit
    highlight-current-row
    :header-cell-style="{ background: '#3AB06F', color: 'white' }"
  >
    <el-table-column align="center" label="Tahap Kegiatan">
      <template slot-scope="scope">
        <span>{{ scope.row.project_stage }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="Komponen Kegiatan">
      <template slot-scope="scope">
        <span>{{ scope.row.name }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="Aksi">
      <template slot-scope="scope">
        <el-button
          type="primary"
          size="mini"
          icon="el-icon-edit"
          @click="handleEditForm(scope.row.id)"
        >
          Ubah
        </el-button>
        <el-button
          type="danger"
          size="mini"
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
