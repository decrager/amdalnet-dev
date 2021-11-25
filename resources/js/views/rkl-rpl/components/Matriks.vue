<template>
  <el-collapse v-model="activeName" accordion>
    <el-collapse-item name="1">
      <template slot="title">
        <span class="title">PENDEKATAN PENGELOLAAN LINGKUNGAN</span>
      </template>
      <ManageApproach v-if="activeName === '1'" />
    </el-collapse-item>
    <el-collapse-item name="2">
      <template slot="title">
        <span class="title">MATRIKS RENCANA PENGELOLAAN LINGKUNGAN (RKL)</span>
      </template>
      <TableRKL v-if="activeName === '2'" />
    </el-collapse-item>
    <el-collapse-item name="3">
      <template slot="title">
        <span class="title">MATRIKS RENCANA PEMANTAUAN LINGKUNGAN (RPL)</span>
      </template>
      <TableRPL v-if="activeName === '3'" />
    </el-collapse-item>
  </el-collapse>
</template>

<script>
import ManageApproach from '@/views/rkl-rpl/components/matriks-table/ManageApproach';
import TableRKL from '@/views/rkl-rpl/components/matriks-table/TableRKL';
import TableRPL from '@/views/rkl-rpl/components/matriks-table/TableRPL';

export default {
  name: 'Matriks',
  components: {
    ManageApproach,
    TableRKL,
    TableRPL,
  },
  data() {
    return {
      activeName: '0',
    };
  },
};
</script>

<style>
.el-collapse-item__header {
  /* background-color: #296d36; */
  background-color: #1e5128;
  padding-left: 10px;
  font-size: large;
  font-weight: bold;
  color: rgb(196, 196, 196);
}
.el-collapse-item__content {
  padding-top: 10px;
}
</style>
