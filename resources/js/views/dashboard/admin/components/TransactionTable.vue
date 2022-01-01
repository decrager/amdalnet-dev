<template>
  <el-table
    :data="list"
    :stripe="true"
    style="width: 100%"
    class="initiator-list"
  >
    <el-table-column
      type="index"
      width="50"
    />
    <el-table-column
      prop="name"
      label="Nama"
    />
    <el-table-column
      prop="submission_date"
      label="Tanggal Pengajuan"
      width="180"
    />
    <el-table-column
      prop="end_date"
      label="Batas Pengajuan"
    />
    <el-table-column
      prop="status"
      label="Status"
    />
  </el-table>
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
  data() {
    return {
      list: [
        { name: 'PT Rejeki Berkah Alam', submission_date: '10 Oktober 2020', end_date: '10 November 2020', status: 'Disetujui' },
        { name: 'PT Sumber Tani', submission_date: '18 Juli 2021', end_date: '18 Agustus 2021', status: 'Proses Review' },
        { name: 'CV Mandiri Raya', submission_date: '10 Februari 2020', end_date: '10 Maret 2020', status: 'Disetujui' },
        { name: 'PT Nusa Mandiri', submission_date: '21 April 2021', end_date: '21 Mei 2021', status: 'Ditolak' },
        { name: 'PT Hijau Nusantara', submission_date: '19 Oktober 2020', end_date: '19 November 2020', status: 'Proses Review' },
      ],
      loading: true,
    };
  },
  created() {
    this.fetchData();
  },
  methods: {
    async fetchData() {
      // const { data } = await fetchList();
      // this.list = data.items.slice(0, 8);
      this.loading = false;
    },
  },
};
</script>
<style lang="scss" scoped>
.initiator-list {
  .cell { font-weight: bolder;}
}
</style>
