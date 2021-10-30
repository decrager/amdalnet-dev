<template>
  <el-table
    v-loading="loading"
    :data="list"
    border
    fit
    highlight-current-row
    :header-cell-style="{ background: '#3AB06F', color: 'white' }"
  >
    <el-table-column align="center" label="Nama">
      <template slot-scope="scope">
        <span>{{ scope.row.name }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="Alamat">
      <template slot-scope="scope">
        <span>{{ scope.row.address }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="Email">
      <template slot-scope="scope">
        <span>{{ scope.row.email }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="No. HP">
      <template slot-scope="scope">
        <span>{{ scope.row.mobile_phone_no }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="Bidang Keahlian">
      <template slot-scope="scope">
        <span>{{ scope.row.expertise }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="Instansi">
      <template slot-scope="scope">
        <span>{{ scope.row.institution }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="Status">
      <template slot-scope="scope">
        <el-tag :type="calculateStatus(scope.row.status) | statusFilter">
          {{ calculateStatus(scope.row.status) }}
        </el-tag>
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
  name: 'ExpertBankTable',
  filters: {
    statusFilter(status) {
      const statusMap = {
        Aktif: 'success',
        'Tidak Aktif': 'danger',
      };
      return statusMap[status];
    },
  },
  props: {
    list: {
      type: Array,
      default: () => [],
    },
    loading: Boolean,
  },
  methods: {
    calculateStatus(status) {
      if (status) {
        return 'Aktif';
      }

      return 'Tidak Aktif';
    },
    handleEditForm(id) {
      this.$emit('handleEditForm', id);
    },
    handleDelete(id, nama) {
      this.$emit('handleDelete', { id, nama });
    },
  },
};
</script>
