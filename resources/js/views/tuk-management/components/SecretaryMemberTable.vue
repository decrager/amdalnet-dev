<template>
  <el-table
    v-loading="loading"
    :data="list"
    fit
    :header-cell-style="{ background: '#3AB06F', color: 'white' }"
  >
    <el-table-column label="No." width="54px">
      <template slot-scope="scope">
        <span>{{ scope.$index + 1 }}</span>
      </template>
    </el-table-column>

    <el-table-column label="Nama">
      <template slot-scope="scope">
        <span>{{ scope.row.name }}</span>
      </template>
    </el-table-column>

    <el-table-column label="NIK" align="center">
      <template slot-scope="scope">
        <span>{{ scope.row.nik }}</span>
      </template>
    </el-table-column>

    <el-table-column label="Jabatan" align="center">
      <template slot-scope="scope">
        <span>{{ scope.row.position }}</span>
      </template>
    </el-table-column>

    <el-table-column label="Instansi" align="center">
      <template slot-scope="scope">
        <span>{{ scope.row.institution }}</span>
      </template>
    </el-table-column>

    <el-table-column label="Aksi" align="center">
      <template slot-scope="scope">
        <el-button
          type="warning"
          size="mini"
          @click.prevent="handleEdit(scope.row.id)"
        >
          Edit
        </el-button>
        <el-button
          type="danger"
          size="mini"
          @click.prevent="handleDelete(scope.row.id)"
        >
          Hapus
        </el-button>
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
export default {
  name: 'SecretaryMemberTable',
  props: {
    list: {
      type: Array,
      default: () => [],
    },
    loading: Boolean,
    teamtype: {
      type: String,
      default: () => '',
    },
    expertemployee: {
      type: Array,
      default: () => [],
    },
    deletedtuk: {
      type: Array,
      default: () => [],
    },
  },
  methods: {
    download(url) {
      window.open(url, '_blank').focus();
    },
    handleEdit(id) {
      this.$router.push({ name: 'editTukSecretaryMember', params: { id }});
    },
    handleDelete(id) {
      this.$emit('handleDeleteSecretaryMember', { id });
    },
  },
};
</script>
