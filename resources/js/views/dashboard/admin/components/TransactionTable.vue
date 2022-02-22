<template>
  <div>
    <el-table
      v-loading="loading"
      :data="list"
      :stripe="true"
      style="width: 100%"
      class="initiator-list"
    >
      <el-table-column width="50">
        <template slot-scope="scope">
          {{ scope.$index + 1 + page * limit - limit }}
        </template>
      </el-table-column>
      <el-table-column label="Nama">
        <template slot-scope="scope">
          {{ scope.row.initiator !== null ? scope.row.initiator.name : '' }}
        </template>
      </el-table-column>
      <el-table-column label="Tanggal Pengajuan">
        <template slot-scope="scope">
          {{ scope.row.filling_date }}
        </template>
      </el-table-column>
      <el-table-column label="Batas Pengajuan">
        <template slot-scope="scope">
          {{ scope.row.submission_deadline }}
        </template>
      </el-table-column>
      <el-table-column label="Status">
        <template slot-scope="scope">
          {{ checkStatus(scope.row.feasibility_test) }}
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>

<script>
// import { fetchList } from '@/api/order';

export default {
  filters: {
    statusFilter(status) {
      const statusMap = {
        approved: 'success',
        processing: 'warning',
        rejected: 'danger',
      };
      return statusMap[status];
    },
    orderNoFilter(str) {
      return str;
    },
  },
  props: {
    list: {
      type: Array,
      default: () => [],
    },
    loading: Boolean,
    page: {
      type: Number,
      default: () => 1,
    },
    limit: {
      type: Number,
      default: () => 5,
    },
  },
  methods: {
    checkStatus(data) {
      if (data !== null) {
        return 'Disetujui';
      }

      return 'Proses Review';
    },
  },
};
</script>
<style lang="scss" scoped>
.initiator-list {
  .cell { font-weight: bolder;}
}
</style>
