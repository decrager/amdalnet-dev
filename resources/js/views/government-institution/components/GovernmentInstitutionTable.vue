<template>
  <el-table
    v-loading="loading"
    :data="list"
    fit
    highlight-current-row
    :header-cell-style="{ background: '#3AB06F', color: 'white' }"
  >
    <el-table-column align="center" label="No" width="50px">
      <template slot-scope="scope">
        <span>{{ scope.$index + 1 }}</span>
      </template>
    </el-table-column>

    <el-table-column
      align="center"
      label="Jenis Instansi"
      sortable
      prop="institution_type"
    />
    <el-table-column
      align="center"
      label="Nama Instansi"
      sortable
      prop="name"
    />
    <el-table-column
      align="center"
      label="Email Instansi"
      sortable
      prop="email"
    />

    <el-table-column align="center" label="Aksi">
      <template slot-scope="scope">
        <el-button
          type="text"
          href="#"
          icon="el-icon-edit"
          @click="handleEdit(scope.row.id)"
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
  name: 'GovernmentInstitutionTable',
  props: {
    list: {
      type: Array,
      default: () => [],
    },
    loading: Boolean,
  },
  methods: {
    handleEdit(id) {
      this.$emit('handleEdit', { id });
    },
    handleDelete(id, name) {
      this.$emit('handleDelete', { id, name });
    },
  },
};
</script>

<style scoped>
/* .expand-container {
  display: flex;
  justify-content: space-around;
} */
.expand-container div {
  width: 50%;
  padding: 1rem 3rem;
}
.expand-container__right {
  text-align: right;
}
</style>
