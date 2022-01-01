<template>
  <el-card class="box-card">
    <div style="margin: 0 0 2em 0; font-weight:bold;">Daftar Pemrakarsa Usaha/Kegiatan</div>
    <transaction-table />
  </el-card>
</template>
<script>
import TransactionTable from './TransactionTable.vue';
export default {
  name: 'InitiatorList',
  components: {
    TransactionTable,
  },
};
</script>
