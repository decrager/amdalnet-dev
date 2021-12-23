<template>
  <el-card class="box-card">
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

    TransactionTable
