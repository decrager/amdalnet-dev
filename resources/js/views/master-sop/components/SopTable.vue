<template>
  <el-table
    v-loading="loading"
    :data="list"
    border
    fit
    highlight-current-row
    :header-cell-style="{ background: '#3AB06F', color: 'white' }"
  >
    <el-table-column align="center" label="ID Komponen">
      <template slot-scope="scope">
        <span>{{ scope.row.id_component }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="ID Rona Awal">
      <template slot-scope="scope">
        <span>{{ scope.row.id_rona_awal }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="Bentuk Pengelolaan">
      <template slot-scope="scope">
        <span v-html="scope.row.mgmt_form" />
      </template>
    </el-table-column>

    <el-table-column align="center" label="Periode Pengelolaan">
      <template slot-scope="scope">
        <span>{{ scope.row.mgmt_period }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="Bentuk Pengawasan">
      <template slot-scope="scope">
        <span>{{ scope.row.monitoring_form }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="Waktu Pengawasan">
      <template slot-scope="scope">
        <span>{{ scope.row.monitoring_time }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="Frekwensi Pengawasan">
      <template slot-scope="scope">
        <span>{{ scope.row.monitoring_freq }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="Nama">
      <template slot-scope="scope">
        <span>{{ scope.row.name }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="Dampak">
      <template slot-scope="scope">
        <span>{{ scope.row.impact }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="Dampak Lainnya">
      <template slot-scope="scope">
        <span>{{ scope.row.other_impact }}</span>
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
  name: 'SopTable',
  filters: {
    statusFilter(status) {
      const statusMap = {
        Aktif: 'success',
        NonAktif: 'danger',
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
