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
        <div style="text-align: center">
          <span>{{ scope.$index + 1 }}</span>
        </div>
      </template>
    </el-table-column>

    <el-table-column label="Nama">
      <template slot-scope="scope">
        <span>{{ scope.row.name }}</span>
      </template>
    </el-table-column>

    <el-table-column label="Kategori">
      <template slot-scope="scope">
        <div style="text-align: center">
          <span>{{ scope.row.category.category_name }}</span>
        </div>
      </template>
    </el-table-column>

    <el-table-column label="URL Service">
      <template slot-scope="scope">
        <a style="color: blue; text-decoration: underline" :href="scope.row.url_service" target="blank" :title="scope.row.url_service">{{ scope.row.url_service }}</a>
      </template>
    </el-table-column>

    <el-table-column label="Organisasi / Instansi">
      <template slot-scope="scope">
        <div style="text-align: center">
          <span>{{ scope.row.organization }}</span>
        </div>
      </template>
    </el-table-column>

    <el-table-column label="Status">
      <template slot-scope="scope">
        <div style="text-align: center">
          <el-tag effect="dark" :type="scope.row.active === 1 ? 'success' : 'danger'">{{ scope.row.active === 1 ? 'Active' : 'Tidak Aktif' }}</el-tag>
        </div>
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
          @click="handleDelete(scope.row.id, scope.row.url_service)"
        >
          Hapus
        </el-button>
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
export default {
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
    handleDelete(id, url_service) {
      this.$emit('handleDelete', { id, url_service });
    },
  },
};
</script>
<style rel="stylesheet/scss" lang="scss" scoped>
  .el-table-column{border: none;}
</style>
