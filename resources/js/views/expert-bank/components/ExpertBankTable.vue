<template>
  <el-table
    v-loading="loading"
    :data="list"
    fit
    highlight-current-row
    :header-cell-style="{ background: '#3AB06F', color: 'white' }"
  >
    <el-table-column align="center" label="Nama" sortable prop="name" />
    <el-table-column align="center" label="Alamat" sortable prop="address" />
    <el-table-column align="center" label="Email" sortable prop="email" />
    <el-table-column
      align="center"
      label="No. HP"
      sortable
      prop="mobile_phone_no"
    />
    <el-table-column
      align="center"
      label="Bidang Keahlian"
      sortable
      prop="expertise"
    />
    <el-table-column
      align="center"
      label="Instansi"
      sortable
      prop="institution"
    />

    <el-table-column align="center" label="Status" prop="status" sortable>
      <template slot-scope="scope">
        <el-tag :type="scope.row.status | statusFilter">
          {{ scope.row.status }}
        </el-tag>
      </template>
    </el-table-column>

    <el-table-column align="center" label="Aksi">
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
    handleEditForm(id) {
      this.$emit('handleEditForm', id);
    },
    handleDelete(id, nama) {
      this.$emit('handleDelete', { id, nama });
    },
  },
};
</script>
